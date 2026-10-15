<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditEntry;
use App\Models\User;
use App\Policies\InternalUserPolicy;
use App\Services\InternalAccountService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

final class InternalUserController extends Controller
{
    public function __construct(private readonly InternalUserPolicy $policy, private readonly InternalAccountService $accounts) {}

    public function index(Request $request)
    {
        abort_unless($this->policy->viewAny($request->user()), 403);
        $filters = $request->validate(['q' => ['nullable', 'string', 'max:120'], 'role' => ['nullable', Rule::in(['admin', 'verifier'])], 'active' => ['nullable', Rule::in(['1', '0'])], 'verified' => ['nullable', Rule::in(['1', '0'])]]);
        $query = $this->accounts->query()->with('roles');
        if (filled($filters['q'] ?? null)) {
            $like = '%'.addcslashes(trim($filters['q']), '\\%_').'%';
            $query->where(fn ($q) => $q->where('name', 'like', $like)->orWhere('email', 'like', $like));
        }
        if (filled($filters['role'] ?? null)) {
            $query->role($filters['role']);
        }
        if (isset($filters['active'])) {
            $query->where('internal_active', $filters['active'] === '1');
        }
        if (isset($filters['verified'])) {
            $filters['verified'] === '1' ? $query->whereNotNull('email_verified_at') : $query->whereNull('email_verified_at');
        }
        $users = $query->latest()->orderByDesc('id')->paginate(15)->withQueryString();
        $summary = [
            'verifiers' => $this->accounts->query()->role('verifier')->where('internal_active', true)->count(),
            'admins' => $this->accounts->query()->role('admin')->where('internal_active', true)->count(),
            'unverified' => $this->accounts->query()->whereNull('email_verified_at')->count(),
            'inactive' => $this->accounts->query()->where('internal_active', false)->count(),
        ];

        return view('admin.internal-users.index', compact('users', 'filters', 'summary'));
    }

    public function create(Request $request)
    {
        abort_unless($this->policy->viewAny($request->user()), 403);

        return view('admin.internal-users.form', ['target' => null]);
    }

    public function store(Request $request)
    {
        abort_unless($this->policy->viewAny($request->user()), 403);
        $data = $this->data($request);
        $data['role'] = $request->validate(['role' => ['required', Rule::in(['admin', 'verifier'])]])['role'];

        return $this->temporaryResult($this->accounts->create($request->user(), $data));
    }

    public function show(Request $request, User $user)
    {
        abort_unless($this->policy->view($request->user(), $user), 403);

        return view('admin.internal-users.show', ['target' => $user->load('roles'), 'canManage' => $this->policy->manage($request->user(), $user)]);
    }

    public function edit(Request $request, User $user)
    {
        abort_unless($this->policy->manage($request->user(), $user), 403);

        return view('admin.internal-users.form', ['target' => $user]);
    }

    public function update(Request $request, User $user)
    {
        abort_unless($this->policy->manage($request->user(), $user), 403);
        $request->validate(['role' => ['prohibited']]);
        $this->accounts->edit($request->user(), $user, $this->data($request, $user));

        return to_route('admin.internal-users.show', $user)->with('status', 'Data akun internal disimpan. Email baru perlu diverifikasi ulang.');
    }

    public function active(Request $request, User $user)
    {
        $data = $request->validate(['active' => ['required', 'boolean'], 'admin_password' => ['nullable', 'string', 'max:72']]);
        $this->accounts->setActive($request->user(), $user, (bool) $data['active'], $data['admin_password'] ?? '');

        return back()->with('status', 'Status akun internal diperbarui.');
    }

    public function temporary(Request $request, User $user)
    {
        $data = $request->validate(['admin_password' => ['nullable', 'string', 'max:72']]);

        return $this->temporaryResult($this->accounts->temporaryReset($request->user(), $user, $data['admin_password'] ?? ''));
    }

    public function resetLink(Request $request, User $user)
    {
        $data = $request->validate(['admin_password' => ['nullable', 'string', 'max:72']]);
        $this->accounts->sendResetLink($request->user(), $user, $data['admin_password'] ?? '');

        return back()->with('status', 'Link reset password telah diminta melalui layanan email SIPORA.');
    }

    public function verification(Request $request, User $user)
    {
        $data = $request->validate(['admin_password' => ['nullable', 'string', 'max:72']]);
        $this->accounts->resendVerification($request->user(), $user, $data['admin_password'] ?? '');

        return back()->with('status', 'Tautan verifikasi email telah diminta.');
    }

    public function activityLog(Request $request)
    {
        abort_unless($this->policy->viewAny($request->user()), 403);
        $entries = AuditEntry::where('log_name', 'internal_accounts')->with(['causer', 'subject'])->latest()->orderByDesc('id')->paginate(20);

        return view('admin.internal-users.activity-log', compact('entries'));
    }

    private function data(Request $request, ?User $user = null): array
    {
        if (is_string($request->input('email'))) {
            $request->merge(['email' => mb_strtolower(trim($request->input('email')))]);
        }

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->where(fn ($query) => $user ? $query->where('id', '!=', $user->getKey()) : $query)],
            'admin_password' => ['nullable', 'string', 'max:72'],
            'roles' => ['prohibited'], 'permissions' => ['prohibited'], 'password' => ['prohibited'],
            'email_verified_at' => ['prohibited'], 'must_change_password' => ['prohibited'], 'internal_active' => ['prohibited'], 'internal_session_version' => ['prohibited'],
        ]);
    }

    private function temporaryResult(array $data)
    {
        return response()->view('admin.internal-users.temporary', $data)->header('Cache-Control', 'no-store, private')->header('Pragma', 'no-cache');
    }
}
