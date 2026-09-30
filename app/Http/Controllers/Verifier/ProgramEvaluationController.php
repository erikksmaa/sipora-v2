<?php

namespace App\Http\Controllers\Verifier;

use App\Actions\Verifier\FinalizeProgramEvaluationAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Verifier\StoreProgramEvaluationRequest;
use App\Models\Program;
use App\Models\ProgramEvaluation;
use App\Services\FinancialReportTotalsService;
use App\Services\ProgramEvaluationEligibilityService;
use App\Services\ProgramProgressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final class ProgramEvaluationController extends Controller
{
    public function index(Request $request, ProgramEvaluationEligibilityService $eligibility): View
    {
        $search = trim((string) $request->query('search', ''));
        $programs = Program::query()->with(['organization', 'category', 'latestEvaluation'])->whereIn('execution_status', [Program::STATUS_RUNNING, Program::STATUS_COMPLETED])
            ->when($search !== '', fn ($query) => $query->where(fn ($nested) => $nested->where('title', 'like', "%{$search}%")->orWhereHas('organization', fn ($organization) => $organization->where('name', 'like', "%{$search}%"))))
            ->orderByRaw("FIELD(execution_status, 'running', 'completed')")->latest()->paginate(15)->withQueryString();

        $assessments = $programs->getCollection()->mapWithKeys(fn (Program $program) => [$program->uuid() => $eligibility->assess($program)]);

        return view('verifier.program-evaluations.index', compact('programs', 'assessments', 'search'));
    }

    public function show(Program $program, ProgramEvaluationEligibilityService $eligibility, ProgramProgressService $progress, FinancialReportTotalsService $totals): View
    {
        Gate::authorize('view', [ProgramEvaluation::class, $program]);
        $program->load(['organization', 'category', 'creator', 'activities.category', 'logbooks.creator', 'latestProposal', 'latestFinancialReport.items', 'evaluations.verifier', 'latestEvaluation.verifier']);
        $financial = $program->latestFinancialReport ? $totals->summarize($program->latestFinancialReport) : null;

        return view('verifier.program-evaluations.show', ['program' => $program, 'assessment' => $eligibility->assess($program), 'metrics' => $progress->summarize($program), 'financial' => $financial, 'money' => $totals]);
    }

    public function store(StoreProgramEvaluationRequest $request, Program $program, FinalizeProgramEvaluationAction $action): RedirectResponse
    {
        $action->execute($request->user(), $program, $request->validated('decision'), $request->validated('evaluation_notes'), $request->validated('expected_latest_evaluation_id'));

        return back()->with('status', 'Evaluasi akhir Program disimpan.');
    }
}
