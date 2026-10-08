<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\SocialComment;
use App\Models\SocialFollow;
use App\Models\SocialLike;
use App\Models\SocialPost;
use App\Models\SocialReport;
use App\Models\User;
use App\Notifications\SocialFeedNotification;
use App\Services\Community\ManagedCommunityService;
use App\Services\SocialFeedService;
use App\Support\BinaryUuid;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class SocialFeedController extends Controller
{
    public function index(Request $request, SocialFeedService $feed): View
    {
        $tab = validator($request->query(), ['tab' => ['nullable', Rule::in(['all', 'following', 'mine', 'activity', 'community', 'opportunity'])]])->validate()['tab'] ?? 'all';
        if (in_array($tab, ['following', 'mine'], true)) {
            if (! $request->user()) {
                return view('social.index', ['tab' => $tab, 'entries' => null]);
            }
            abort_unless($request->user()->hasRole('youth'), 403);
        }

        return view('social.index', ['entries' => $feed->paginate($tab, $request->user()), 'tab' => $tab]);
    }

    public function managerIndex(Request $request, Organization $organization, ManagedCommunityService $managed): View
    {
        Gate::authorize('manageMemberships', $organization);
        $posts = SocialPost::where('organization_id', $organization->getKey())->with(['author.profile', 'author.profileVisibility', 'organization'])->latest()->paginate(12);

        return view('manager.social.index', ['organization' => $organization, 'managedCommunities' => $managed->forUser($request->user()), 'posts' => $posts]);
    }

    public function managerStore(Request $request, Organization $organization): RedirectResponse
    {
        Gate::authorize('manageMemberships', $organization);
        $request->merge(['organization_id' => $organization->slug, 'visibility' => 'public']);
        $this->store($request);

        return to_route('manager.social.index', $organization)->with('status', 'Update Community dibagikan.');
    }

    public function create(Request $request, ManagedCommunityService $managed): View
    {
        return view('social.form', ['post' => null, 'communities' => $managed->forUser($request->user())->where('review_status', 'approved')->where('operational_status', 'active')]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $path = $this->imagePath($request);
        try {
            $post = DB::transaction(function () use ($request, $data, $path) {
                $post = SocialPost::create([...$data, 'author_id' => $request->user()->getKey(), 'image_path' => $path]);
                $this->audit($request, $post, 'social_post_created');

                return $post;
            });
        } catch (\Throwable $e) {
            if ($path) {
                Storage::disk('private')->delete($path);
            }
            throw $e;
        }

        return to_route('social.show', $post)->with('status', 'Post berhasil dibagikan.');
    }

    public function show(Request $request, SocialPost $post): View
    {
        $this->visible($request, $post);
        $post->load(['author.profile', 'author.profileVisibility', 'organization'])->loadCount('likes');
        $comments = $post->comments()->where('is_hidden', false)->with(['author.profile', 'author.profileVisibility'])->orderBy('created_at')->orderBy('id')->paginate(20);
        $liked = $request->user() && $post->likes()->where('user_id', $request->user()->getKey())->exists();
        $followed = $request->user() && SocialFollow::where('user_id', $request->user()->getKey())
            ->where($post->organization_id ? 'organization_id' : 'youth_id', $post->organization_id ?? $post->author_id)->exists();
        $canEdit = $this->owns($request, $post);

        return view('social.show', compact('post', 'comments', 'liked', 'followed', 'canEdit'));
    }

    public function edit(Request $request, SocialPost $post, ManagedCommunityService $managed): View
    {
        abort_unless($this->owns($request, $post) && ! $post->is_hidden && ! $post->is_removed, 403);

        return view('social.form', ['post' => $post, 'communities' => $managed->forUser($request->user())]);
    }

    public function update(Request $request, SocialPost $post): RedirectResponse
    {
        abort_unless($this->owns($request, $post) && ! $post->is_hidden && ! $post->is_removed, 403);
        $data = $this->validated($request, $post);
        $path = $this->imagePath($request);
        $old = $post->image_path;
        try {
            DB::transaction(function () use ($request, $post, $data, $path) {
                $post->update([...$data, 'image_path' => $path ?? ($request->boolean('remove_image') ? null : $post->image_path)]);
                $this->audit($request, $post, 'social_post_updated');
            });
        } catch (\Throwable $e) {
            if ($path) {
                Storage::disk('private')->delete($path);
            }
            throw $e;
        }
        if ($old && $old !== $post->image_path) {
            Storage::disk('private')->delete($old);
        }

        return to_route('social.show', $post)->with('status', 'Post diperbarui.');
    }

    public function destroy(Request $request, SocialPost $post): RedirectResponse
    {
        abort_unless($this->owns($request, $post), 403);
        $post->update(['is_removed' => true]);
        $this->audit($request, $post, 'social_post_removed');

        return to_route('social.index')->with('status', 'Post dihapus dari feed.');
    }

    public function image(Request $request, SocialPost $post): StreamedResponse
    {
        if (! $request->user()?->hasRole('admin')) {
            $this->visible($request, $post);
        }
        abort_unless($post->image_path && Storage::disk('private')->exists($post->image_path), 404);
        $mime = Storage::disk('private')->mimeType($post->image_path);
        abort_unless(in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true), 404);

        return Storage::disk('private')->response($post->image_path, 'post-image', ['Content-Type' => $mime,
            'Cache-Control' => 'private, no-store', 'X-Content-Type-Options' => 'nosniff', 'Content-Security-Policy' => "default-src 'none'; sandbox"]);
    }

    public function like(Request $request, SocialPost $post): RedirectResponse
    {
        $this->visible($request, $post);
        abort_unless($post->visibility === 'public', 403);
        $data = $request->validate(['liked' => ['required', 'boolean']]);
        if ($data['liked']) {
            SocialLike::firstOrCreate(['post_id' => $post->getKey(), 'user_id' => $request->user()->getKey()]);
        } else {
            $post->likes()->where('user_id', $request->user()->getKey())->delete();
        }

        return back();
    }

    public function comment(Request $request, SocialPost $post): RedirectResponse
    {
        $this->visible($request, $post);
        abort_unless($post->visibility === 'public', 403);
        $data = $request->validate(['body' => ['required', 'string', 'max:2000']]);
        $body = $this->plain($data['body']);
        if ($body === '') {
            throw ValidationException::withMessages(['body' => 'Komentar harus berisi teks.']);
        }
        DB::transaction(function () use ($request, $post, $body) {
            $comment = SocialComment::create(['post_id' => $post->getKey(), 'author_id' => $request->user()->getKey(), 'body' => $body]);
            $this->audit($request, $comment, 'social_comment_created');
            if ($post->author_id !== $request->user()->getKey()) {
                $post->author->notify(new SocialFeedNotification('social_commented', 'Post kamu mendapat komentar baru.', $post->uuid()));
            }
        });

        return to_route('social.show', $post)->with('status', 'Komentar terkirim.');
    }

    public function follow(Request $request): RedirectResponse
    {
        $data = $request->validate(['target_type' => ['required', Rule::in(['youth', 'community'])], 'target_id' => ['required', 'string', 'max:180'], 'following' => ['required', 'boolean']]);
        if ($data['target_type'] === 'community') {
            $query = Organization::where('slug', $data['target_id']);
            if ($data['following']) {
                $query->where('review_status', 'approved')->where('operational_status', 'active');
            } else {
                $query->whereIn('id', SocialFollow::where('user_id', $request->user()->getKey())->select('organization_id'));
            }
            $target = $query->firstOrFail();
            $column = 'organization_id';
        } else {
            $query = User::role('youth')->whereHas('profile', fn ($q) => $q->where('public_slug', $data['target_id']));
            if ($data['following']) {
                $query->whereHas('profileVisibility', fn ($q) => $q->where('is_profile_public', true));
            } else {
                $query->whereIn('id', SocialFollow::where('user_id', $request->user()->getKey())->select('youth_id'));
            }
            $target = $query->firstOrFail();
            abort_if($target->getKey() === $request->user()->getKey(), 403);
            $column = 'youth_id';
        }
        $keys = ['user_id' => $request->user()->getKey(), $column => $target->getKey()];
        if ($data['following']) {
            $follow = SocialFollow::firstOrCreate($keys);
            if ($follow->wasRecentlyCreated && $column === 'youth_id') {
                $target->notify(new SocialFeedNotification('social_followed', 'Seorang pemuda mulai mengikuti update publik kamu.'));
            }
        } else {
            SocialFollow::where($keys)->delete();
        }

        return back()->with('status', $data['following'] ? 'Sumber diikuti.' : 'Berhenti mengikuti sumber.');
    }

    public function report(Request $request, SocialPost $post): RedirectResponse
    {
        $this->visible($request, $post);
        abort_unless($post->visibility === 'public', 403);
        $data = $request->validate(['comment_id' => ['nullable', 'uuid'], 'reason' => ['required', Rule::in(['spam', 'harassment', 'inappropriate', 'misinformation', 'other'])], 'details' => ['nullable', 'string', 'max:1000']]);
        $comment = isset($data['comment_id']) ? $post->comments()->where('is_hidden', false)->findOrFail(BinaryUuid::bytesOrFail($data['comment_id'], SocialComment::class)) : null;
        $keys = ['post_id' => $post->getKey(), 'comment_id' => $comment?->getKey(), 'reporter_id' => $request->user()->getKey(), 'status' => 'pending'];
        // Serializes concurrent reports by one author and preserves one pending report per target.
        DB::transaction(function () use ($request, $keys, $data) {
            User::whereKey($request->user()->getKey())->lockForUpdate()->firstOrFail();
            $report = SocialReport::firstOrCreate($keys, ['reason' => $data['reason'], 'details' => isset($data['details']) ? $this->plain($data['details']) : null]);
            if ($report->wasRecentlyCreated) {
                $this->audit($request, $report, 'social_content_reported');
            }
        });

        return back()->with('status', 'Laporan diterima untuk ditinjau Admin.');
    }

    private function validated(Request $request, ?SocialPost $post = null): array
    {
        $data = $request->validate(['body' => ['required', 'string', 'max:3000'], 'visibility' => ['required', Rule::in(['public', 'private'])],
            'organization_id' => ['nullable', 'string', 'max:180'], 'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072', 'dimensions:max_width=4096,max_height=4096'], 'remove_image' => ['nullable', 'boolean']]);
        $body = $this->plain($data['body']);
        if ($body === '') {
            throw ValidationException::withMessages(['body' => 'Post harus berisi teks.']);
        }
        $organizationId = $post?->organization_id;
        if (! $post && filled($data['organization_id'] ?? null)) {
            $organizationId = Organization::where('slug', $data['organization_id'])->firstOrFail()->getKey();
        }
        if ($organizationId) {
            $organization = Organization::findOrFail($organizationId);
            Gate::authorize('manageMemberships', $organization);
            abort_unless($organization->review_status === 'approved' && $organization->operational_status === 'active', 403);
        }

        return ['body' => $body, 'visibility' => $data['visibility'], 'organization_id' => $organizationId];
    }

    private function imagePath(Request $request): ?string
    {
        return $request->hasFile('image') ? $request->file('image')->store('social-images', 'private') : null;
    }

    private function visible(Request $request, SocialPost $post): void
    {
        $public = SocialPost::published()->whereKey($post->getKey())->exists();
        abort_unless($public || (! $post->is_hidden && ! $post->is_removed && $post->visibility === 'private' && $this->owns($request, $post)), 404);
    }

    private function owns(Request $request, SocialPost $post): bool
    {
        if (! $request->user()) {
            return false;
        }

        return $post->organization_id
            ? $post->organization !== null && Gate::forUser($request->user())->allows('manageMemberships', $post->organization)
            : $post->author_id === $request->user()->getKey();
    }

    private function plain(string $text): string
    {
        return trim(preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '', strip_tags($text)) ?? '');
    }

    private function audit(Request $request, $model, string $event): void
    {
        activity()->causedBy($request->user())->performedOn($model)->event($event)->log('Social Feed diperbarui');
    }
}
