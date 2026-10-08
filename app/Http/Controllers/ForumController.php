<?php

namespace App\Http\Controllers;

use App\Models\ForumCategory;
use App\Models\ForumReaction;
use App\Models\ForumReply;
use App\Models\ForumReport;
use App\Models\ForumThread;
use App\Notifications\ForumThreadReplied;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

final class ForumController extends Controller
{
    public function index(Request $request): View
    {
        $filters = validator($request->query(), [
            'category' => ['nullable', 'string', 'max:120'],
            'sort' => ['nullable', Rule::in(['latest', 'discussed'])],
        ])->validate();
        $category = $filters['category'] ?? null;
        $sort = $filters['sort'] ?? 'latest';
        $categories = ForumCategory::query()->where('is_active', true)->orderBy('name')->get();
        $threads = ForumThread::query()->whereIn('status', [ForumThread::ACTIVE, ForumThread::LOCKED])
            ->when($category, fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $category)->where('is_active', true)))
            ->with(['category', 'author.profile', 'author.profileVisibility'])->withCount(['replies' => fn ($q) => $q->where('is_hidden', false)])
            ->when($sort === 'discussed', fn ($q) => $q->orderByDesc('replies_count'))
            ->orderByDesc('created_at')->paginate(15)->withQueryString();
        return view('forum.index', compact('threads', 'categories', 'category', 'sort'));
    }

    public function show(Request $request, ForumThread $thread): View
    {
        abort_if($thread->status === ForumThread::HIDDEN || ! $thread->category()->where('is_active', true)->exists(), 404);
        $thread->load(['category', 'author.profile', 'author.profileVisibility'])->loadCount('reactions');
        $replies = $thread->replies()->where('is_hidden', false)->with(['author.profile', 'author.profileVisibility'])->orderBy('created_at')->paginate(20);
        $helpful = $request->user()?->getKey()
            ? $thread->reactions()->where('user_id', $request->user()->getKey())->exists() : false;
        return view('forum.show', compact('thread', 'replies', 'helpful'));
    }

    public function create(): View
    {
        return view('forum.form', ['thread' => null, 'categories' => ForumCategory::query()->where('is_active', true)->orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedThread($request);
        $thread = ForumThread::create([...$data, 'author_id' => $request->user()->getKey(), 'status' => ForumThread::ACTIVE]);
        activity()->causedBy($request->user())->performedOn($thread)->event('forum_thread_created')->log('Diskusi Forum dibuat');
        return to_route('forum.show', $thread)->with('status', 'Diskusi berhasil dibuat.');
    }

    public function edit(Request $request, ForumThread $thread): View
    {
        $this->ensureOwner($request, $thread);
        return view('forum.form', ['thread' => $thread, 'categories' => ForumCategory::query()->where('is_active', true)->orderBy('name')->get()]);
    }

    public function update(Request $request, ForumThread $thread): RedirectResponse
    {
        $this->ensureOwner($request, $thread);
        $thread->update($this->validatedThread($request));
        activity()->causedBy($request->user())->performedOn($thread)->event('forum_thread_updated')->log('Diskusi Forum diperbarui');
        return to_route('forum.show', $thread)->with('status', 'Diskusi berhasil diperbarui.');
    }

    public function destroy(Request $request, ForumThread $thread): RedirectResponse
    {
        $this->ensureOwner($request, $thread);
        $thread->update(['status' => ForumThread::HIDDEN]);
        activity()->causedBy($request->user())->performedOn($thread)->event('forum_thread_removed')->log('Diskusi Forum disembunyikan penulis');
        return to_route('forum.index')->with('status', 'Diskusi dihapus dari Forum publik.');
    }

    public function reply(Request $request, ForumThread $thread): RedirectResponse
    {
        abort_unless($thread->status === ForumThread::ACTIVE && $thread->category()->where('is_active', true)->exists(), 403);
        $data = $request->validate(['body' => ['required', 'string', 'min:3', 'max:5000']]);
        $body = $this->plain($data['body']);
        if (mb_strlen($body) < 3) throw ValidationException::withMessages(['body' => 'Balasan harus berisi teks.']);
        DB::transaction(function () use ($thread, $request, $body) {
            $reply = ForumReply::create(['thread_id' => $thread->getKey(), 'author_id' => $request->user()->getKey(), 'body' => $body]);
            activity()->causedBy($request->user())->performedOn($reply)->event('forum_reply_created')->log('Balasan Forum dibuat');
            if ($thread->author_id !== $request->user()->getKey()) $thread->author->notify(new ForumThreadReplied($thread));
        });
        return to_route('forum.show', $thread)->with('status', 'Balasan terkirim.');
    }

    public function helpful(Request $request, ForumThread $thread): RedirectResponse
    {
        abort_if($thread->status === ForumThread::HIDDEN, 404);
        $existing = $thread->reactions()->where('user_id', $request->user()->getKey())->first();
        if ($existing) $existing->delete();
        else ForumReaction::query()->firstOrCreate(['thread_id' => $thread->getKey(), 'user_id' => $request->user()->getKey()]);
        return back();
    }

    public function report(Request $request, ForumThread $thread): RedirectResponse
    {
        abort_if($thread->status === ForumThread::HIDDEN, 404);
        $data = $request->validate([
            'reply_id' => ['nullable', 'uuid'],
            'reason' => ['required', Rule::in(['spam', 'harassment', 'inappropriate', 'misinformation', 'other'])],
            'details' => ['nullable', 'string', 'max:1000'],
        ]);
        $reply = isset($data['reply_id']) ? ForumReply::query()->where('thread_id', $thread->getKey())->where('is_hidden', false)->findOrFail(\App\Support\BinaryUuid::bytesOrFail($data['reply_id'], ForumReply::class)) : null;
        $exists = ForumReport::query()->where('thread_id', $thread->getKey())->where('reporter_id', $request->user()->getKey())
            ->where('reply_id', $reply?->getKey())->where('status', 'pending')->exists();
        if ($exists) return back()->with('status', 'Laporan sebelumnya masih ditinjau.');
        $report = ForumReport::create(['thread_id' => $thread->getKey(), 'reply_id' => $reply?->getKey(),
            'reporter_id' => $request->user()->getKey(), 'reason' => $data['reason'],
            'details' => isset($data['details']) ? $this->plain($data['details']) : null]);
        activity()->causedBy($request->user())->performedOn($report)->event('forum_content_reported')->log('Konten Forum dilaporkan');
        return back()->with('status', 'Laporan diterima untuk ditinjau Admin.');
    }

    private function validatedThread(Request $request): array
    {
        $data = $request->validate([
            'category_id' => ['required', 'uuid'], 'title' => ['required', 'string', 'min:5', 'max:180'],
            'body' => ['required', 'string', 'min:10', 'max:10000'],
        ]);
        $category = ForumCategory::query()->where('is_active', true)->findOrFail(\App\Support\BinaryUuid::bytesOrFail($data['category_id'], ForumCategory::class));
        $title = $this->plain($data['title']); $body = $this->plain($data['body']);
        if (mb_strlen($title) < 5 || mb_strlen($body) < 10) throw ValidationException::withMessages(['body' => 'Isi diskusi harus berupa teks yang jelas.']);
        return ['category_id' => $category->getKey(), 'title' => $title, 'body' => $body];
    }

    private function ensureOwner(Request $request, ForumThread $thread): void
    {
        abort_unless($thread->author_id === $request->user()->getKey() && $thread->status === ForumThread::ACTIVE, 403);
    }

    private function plain(string $text): string
    {
        return trim(preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '', strip_tags($text)));
    }
}
