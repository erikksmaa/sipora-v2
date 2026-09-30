<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\ChangeOpportunityPublicationAction;
use App\Actions\Admin\SaveOpportunityAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveOpportunityRequest;
use App\Models\AdministrativeArea;
use App\Models\Opportunity;
use App\Models\OpportunityCategory;
use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

final class OpportunityController extends Controller
{
    public function index(Request $request): View
    {
        $filters = validator($request->query(), ['status' => ['nullable', Rule::in(['all', Opportunity::STATUS_DRAFT, Opportunity::STATUS_PUBLISHED, Opportunity::STATUS_ARCHIVED])], 'q' => ['nullable', 'string', 'max:120']])->validate();
        $status = $filters['status'] ?? 'all';
        $search = trim($filters['q'] ?? '');
        $opportunities = Opportunity::query()->with(['category', 'administrativeArea'])
            ->when($status !== 'all', fn ($query) => $query->where('publication_status', $status))
            ->when($search !== '', fn ($query) => $query->where(fn ($nested) => $nested->where('title', 'like', "%{$search}%")->orWhere('provider_name', 'like', "%{$search}%")))
            ->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('admin.opportunities.index', compact('opportunities', 'status', 'search'));
    }

    public function create(): View
    {
        return view('admin.opportunities.create', $this->formData());
    }

    public function store(SaveOpportunityRequest $request, SaveOpportunityAction $action): RedirectResponse
    {
        $opportunity = $action->execute($request->user(), $request->validated());

        return to_route('admin.opportunities.show', $opportunity)->with('status', 'Draft Opportunity berhasil dibuat.');
    }

    public function show(Opportunity $opportunity): View
    {
        $opportunity->load(['category', 'organization', 'administrativeArea', 'creator']);

        return view('admin.opportunities.show', compact('opportunity'));
    }

    public function edit(Opportunity $opportunity): View
    {
        return view('admin.opportunities.edit', ['opportunity' => $opportunity, ...$this->formData()]);
    }

    public function update(SaveOpportunityRequest $request, Opportunity $opportunity, SaveOpportunityAction $action): RedirectResponse
    {
        $action->execute($request->user(), $request->validated(), $opportunity);

        return to_route('admin.opportunities.show', $opportunity)->with('status', 'Opportunity berhasil diperbarui.');
    }

    public function publish(Request $request, Opportunity $opportunity, ChangeOpportunityPublicationAction $action): RedirectResponse
    {
        $action->execute($request->user(), $opportunity, Opportunity::STATUS_PUBLISHED);

        return back()->with('status', 'Opportunity berhasil dipublikasikan.');
    }

    public function archive(Request $request, Opportunity $opportunity, ChangeOpportunityPublicationAction $action): RedirectResponse
    {
        $action->execute($request->user(), $opportunity, Opportunity::STATUS_ARCHIVED);

        return back()->with('status', 'Opportunity berhasil diarsipkan.');
    }

    private function formData(): array
    {
        return [
            'categories' => OpportunityCategory::query()->orderBy('name')->get(),
            'organizations' => Organization::query()->where('review_status', Organization::REVIEW_APPROVED)->orderBy('name')->get(),
            'areas' => AdministrativeArea::query()->where('area_level', 'district')->orderBy('name')->get(),
        ];
    }
}
