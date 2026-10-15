<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Program;
use App\Models\ProgramApplication;
use App\Services\Community\ManagedCommunityService;
use App\Services\Program\ProgramIntakeRules;
use App\Services\Program\ProgramIntakeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

final class ProgramIntakeController extends Controller
{
    public function programs(Request $request)
    {
        $programs = Program::whereHas('latestProposal', fn ($q) => $q->where('status', 'approved'))->with('organization')->withCount(['applications', 'participants'])->latest()->paginate(15);

        return view('programs.intake-index', compact('programs'));
    }

    public function managerIndex(Request $request, Organization $organization, Program $program, ProgramIntakeService $intake, ManagedCommunityService $managed)
    {
        return $this->index($request, $program, $intake, $managed, $organization);
    }

    public function managerReview(Request $request, Organization $organization, Program $program, ProgramApplication $application, ProgramIntakeService $intake)
    {
        return $this->review($request, $program, $application, $intake, $organization);
    }

    public function edit(Request $request, Organization $organization, Program $program, ManagedCommunityService $managed)
    {
        abort_unless($program->organization_id === $organization->getKey(), 404);
        Gate::authorize('configureIntake', $program);

        return view('manager.programs.intake', ['program' => $program, 'organization' => $organization, 'managedCommunities' => $managed->forUser($request->user())]);
    }

    public function update(Request $request, Organization $organization, Program $program, ProgramIntakeService $intake)
    {
        abort_unless($program->organization_id === $organization->getKey(), 404);
        Gate::authorize('configureIntake', $program);
        $intake->configure($request->user(), $program, $request->validate(ProgramIntakeRules::rules()));

        return to_route('manager.programs.applications.index', [$organization, $program])->with('status', 'Pengaturan pendaftaran disimpan.');
    }

    public function index(Request $request, Program $program, ProgramIntakeService $intake, ManagedCommunityService $managed, ?Organization $organization = null)
    {
        if ($organization) {
            abort_unless($program->organization_id === $organization->getKey(), 404);
        }
        Gate::authorize('manageApplications', $program);
        $input = $request->validate(['status' => ['nullable', Rule::in(array_merge(['all'], array_keys(ProgramApplication::LABELS)))], 'q' => ['nullable', 'string', 'max:120']]);
        $status = $input['status'] ?? 'all';
        $search = trim($input['q'] ?? '');
        $applications = $program->applications()->with(['user.profile', 'user.profileVisibility', 'user.interests', 'user.skills'])
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->when($search !== '', fn ($q) => $q->whereHas('user', fn ($u) => $u->where('name', 'like', '%'.addcslashes($search, '\\%_').'%')->orWhere('email', 'like', '%'.addcslashes($search, '\\%_').'%')))
            ->latest()->orderByDesc('id')->paginate(15)->withQueryString();

        return view('programs.applications', ['program' => $program, 'organization' => $organization, 'managedCommunities' => $organization ? $managed->forUser($request->user()) : collect(), 'summary' => $intake->summary($program), 'applications' => $applications, 'status' => $status, 'search' => $search]);
    }

    public function review(Request $request, Program $program, ProgramApplication $application, ProgramIntakeService $intake, ?Organization $organization = null)
    {
        if ($organization) {
            abort_unless($program->organization_id === $organization->getKey(), 404);
        }
        Gate::authorize('manageApplications', $program);
        $data = $request->validate(['decision' => ['required', Rule::in(['under_review', 'accepted', 'waitlisted', 'rejected'])], 'expected_status' => ['required', Rule::in(array_keys(ProgramApplication::LABELS))], 'notes' => ['nullable', 'string', 'max:2000']]);
        $intake->review($request->user(), $program, $application, $data['decision'], $data['expected_status'], $data['notes'] ?? null);

        return back()->with('status','Keputusan seleksi disimpan.');
    }
}
