<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumReply;
use App\Models\ForumReport;
use App\Models\ForumThread;
use App\Notifications\ForumContentModerated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

final class ForumModerationController extends Controller
{
    public function index(Request $request): View
    {
        $status = validator($request->query(), ['status' => ['nullable', Rule::in(['pending', 'resolved', 'all'])]])->validate()['status'] ?? 'pending';
        $reports = ForumReport::query()->with(['thread.author', 'reply.author', 'reporter'])
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->orderByDesc('created_at')->paginate(20)->withQueryString();
        return view('admin.forum.index', compact('reports', 'status'));
    }

    public function thread(Request $request, ForumThread $thread): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', Rule::in([ForumThread::ACTIVE, ForumThread::LOCKED, ForumThread::HIDDEN])]]);
        $before = $thread->status;
        $thread->update(['status' => $data['status']]);
        activity()->causedBy($request->user())->performedOn($thread)->event('forum_thread_moderated')
            ->withProperties(['before' => $before, 'after' => $thread->status])->log('Status diskusi Forum diubah');
        if ($before !== $thread->status) $thread->author->notify(new ForumContentModerated($thread, $thread->status));
        return back()->with('status', 'Status diskusi diperbarui.');
    }

    public function reply(Request $request, ForumReply $reply): RedirectResponse
    {
        $data = $request->validate(['is_hidden' => ['required', 'boolean']]);
        $before = $reply->is_hidden;
        $reply->update(['is_hidden' => (bool) $data['is_hidden']]);
        activity()->causedBy($request->user())->performedOn($reply)->event('forum_reply_moderated')
            ->withProperties(['before' => $before, 'after' => $reply->is_hidden])->log('Status balasan Forum diubah');
        if ($before !== $reply->is_hidden) $reply->author->notify(new ForumContentModerated($reply->thread, $reply->is_hidden ? 'hidden' : 'active'));
        return back()->with('status', 'Status balasan diperbarui.');
    }

    public function resolve(Request $request, ForumReport $report): RedirectResponse
    {
        abort_unless($report->status === 'pending', 403);
        $report->update(['status' => 'resolved', 'resolved_by' => $request->user()->getKey(), 'resolved_at' => now()]);
        activity()->causedBy($request->user())->performedOn($report)->event('forum_report_resolved')->log('Laporan Forum ditutup');
        return back()->with('status', 'Laporan ditutup.');
    }
}
