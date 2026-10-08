<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialComment;
use App\Models\SocialPost;
use App\Models\SocialReport;
use App\Notifications\SocialFeedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

final class SocialModerationController extends Controller
{
    public function index(Request $request): View
    {
        $status = validator($request->query(), ['status' => ['nullable', Rule::in(['pending', 'resolved', 'all'])]])->validate()['status'] ?? 'pending';
        $reports = SocialReport::with(['post', 'comment'])->when($status !== 'all', fn ($q) => $q->where('status', $status))->latest()->paginate(20)->withQueryString();

        return view('admin.social.index', compact('reports', 'status'));
    }

    public function post(Request $request, SocialPost $post): RedirectResponse
    {
        return $this->moderate($request, $post, $post);
    }

    public function comment(Request $request, SocialComment $comment): RedirectResponse
    {
        return $this->moderate($request, $comment, $comment->post);
    }

    private function moderate(Request $request, $model, SocialPost $post): RedirectResponse
    {
        $data = $request->validate(['is_hidden' => ['required', 'boolean']]);
        $before = $model->is_hidden;
        $model->update(['is_hidden' => (bool) $data['is_hidden']]);
        activity()->causedBy($request->user())->performedOn($model)->event('social_content_moderated')
            ->withProperties(['before' => $before, 'after' => $model->is_hidden])->log('Moderasi Social Feed');
        if ($before !== $model->is_hidden) {
            $model->author->notify(new SocialFeedNotification('social_moderated', $model->is_hidden ? 'Konten feed kamu disembunyikan oleh Admin.' : 'Konten feed kamu ditampilkan kembali oleh Admin.', $post->uuid()));
        }

        return back()->with('status', 'Moderasi disimpan.');
    }

    public function resolve(Request $request, SocialReport $report): RedirectResponse
    {
        abort_unless($report->status === 'pending', 403);
        $report->update(['status' => 'resolved', 'resolved_by' => $request->user()->getKey(), 'resolved_at' => now()]);
        activity()->causedBy($request->user())->performedOn($report)->event('social_report_resolved')->log('Laporan Social Feed ditutup');

        return back()->with('status', 'Laporan ditutup.');
    }
}
