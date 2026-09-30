<?php

namespace App\Http\Controllers\Manager;

use App\Actions\Manager\SaveProgramAction;
use App\Actions\Manager\StartProgramExecutionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\StoreProgramRequest;
use App\Http\Requests\Manager\UpdateProgramRequest;
use App\Models\Organization;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Services\Community\ManagedCommunityService;
use App\Services\FinancialReportTotalsService;
use App\Services\ProgramProgressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final class ProgramController extends Controller
{
    public function index(Request $request, Organization $organization, ManagedCommunityService $managed): View
    {
        Gate::authorize('manageMemberships', $organization);

        return view('manager.programs.index', [
            'organization' => $organization,
            'programs' => $organization->programs()->with(['category'])->withCount('activities')->latest()->paginate(15),
            'managedCommunities' => $managed->forUser($request->user()),
        ]);
    }

    public function create(Request $request, Organization $organization, ManagedCommunityService $managed): View
    {
        Gate::authorize('manageMemberships', $organization);

        return $this->formView($request, $organization, $managed, new Program);
    }

    public function store(StoreProgramRequest $request, Organization $organization, SaveProgramAction $action): RedirectResponse
    {
        $program = $action->execute($request->user(), $organization, $request->validated());

        return to_route('manager.programs.show', [$organization, $program])->with('status', 'Rencana Program berhasil disimpan.');
    }

    public function show(Request $request, Organization $organization, Program $program, ManagedCommunityService $managed, ProgramProgressService $progress, FinancialReportTotalsService $totals): View
    {
        $this->ensureBelongs($organization, $program);
        Gate::authorize('view', $program);
        $program->load(['category', 'creator', 'activities.category', 'proposals.reviewer', 'latestProposal', 'logbooks.creator', 'logbooks.media', 'financialReports.items', 'financialReports.reviewer', 'latestFinancialReport.items', 'evaluations.verifier', 'latestEvaluation.verifier']);

        return view('manager.programs.show', [
            'organization' => $organization,
            'program' => $program,
            'metrics' => $progress->summarize($program),
            'finalFinancialSummary' => $program->latestFinancialReport ? $totals->summarize($program->latestFinancialReport) : null,
            'managedCommunities' => $managed->forUser($request->user()),
        ]);
    }

    public function startExecution(Request $request, Organization $organization, Program $program, StartProgramExecutionAction $action): RedirectResponse
    {
        $this->ensureBelongs($organization, $program);
        Gate::authorize('startExecution', $program);
        $action->execute($request->user(), $program);

        return back()->with('status', 'Eksekusi Program dimulai.');
    }

    public function edit(Request $request, Organization $organization, Program $program, ManagedCommunityService $managed): View
    {
        $this->ensureBelongs($organization, $program);
        Gate::authorize('update', $program);

        return $this->formView($request, $organization, $managed, $program);
    }

    public function update(UpdateProgramRequest $request, Organization $organization, Program $program, SaveProgramAction $action): RedirectResponse
    {
        $action->execute($request->user(), $organization, $request->validated(), $program);

        return to_route('manager.programs.show', [$organization, $program])->with('status', 'Rencana Program berhasil diperbarui.');
    }

    private function formView(Request $request, Organization $organization, ManagedCommunityService $managed, Program $program): View
    {
        return view($program->exists ? 'manager.programs.edit' : 'manager.programs.create', [
            'organization' => $organization,
            'program' => $program,
            'categories' => ProgramCategory::query()->orderBy('name')->get(),
            'managedCommunities' => $managed->forUser($request->user()),
        ]);
    }

    private function ensureBelongs(Organization $organization, Program $program): void
    {
        abort_unless($program->organization_id === $organization->getKey(), 404);
    }
}
