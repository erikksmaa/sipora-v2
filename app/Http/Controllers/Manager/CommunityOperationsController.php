<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityParticipation;
use App\Models\ActivitySession;
use App\Models\Organization;
use App\Models\ProgramProposal;
use App\Services\Community\ManagedCommunityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final class CommunityOperationsController extends Controller
{
    public function sessions(Request $request, Organization $organization, ManagedCommunityService $managed): View
    {
        return $this->activityPage($request, $organization, $managed, 'sessions');
    }

    public function participants(Request $request, Organization $organization, ManagedCommunityService $managed): View
    {
        return $this->activityPage($request, $organization, $managed, 'participants');
    }

    public function attendance(Request $request, Organization $organization, ManagedCommunityService $managed): View
    {
        Gate::authorize('manageMemberships', $organization);
        $statusOptions = $this->activityStatusOptions();
        $filters = $this->filters($request, $statusOptions);
        $rows = ActivitySession::query()->with('activity')
            ->whereHas('activity', fn ($query) => $query->where('organization_id', $organization->getKey()))
            ->when($filters['q'] !== '', fn ($query) => $query->whereHas('activity', fn ($activity) => $activity->where('title', 'like', '%'.$filters['q'].'%')))
            ->when($filters['status'] !== '', fn ($query) => $query->whereHas('activity', fn ($activity) => $activity->where('execution_status', $filters['status'])))
            ->orderByDesc('start_at')->paginate($filters['per_page'])->withQueryString()
            ->through(fn (ActivitySession $session) => [
                'title' => $session->activity->title.' · Sesi '.$session->session_number,
                'detail' => $session->title.' · '.$session->start_at->translatedFormat('d M Y, H:i'),
                'url' => route('manager.activities.attendance.index', [$organization, $session->activity, $session]),
                'action' => 'Buka presensi',
                'badgeType' => 'activity',
                'badgeStatus' => $session->activity->execution_status,
            ]);

        return $this->page($request, $organization, $managed, $rows, 'Presensi', 'Pilih sesi Activity untuk mencatat kehadiran peserta.', $filters, $statusOptions, 'Status Activity');
    }

    public function proposals(Request $request, Organization $organization, ManagedCommunityService $managed): View
    {
        return $this->programPage($request, $organization, $managed, 'proposals');
    }

    public function logbooks(Request $request, Organization $organization, ManagedCommunityService $managed): View
    {
        return $this->programPage($request, $organization, $managed, 'logbooks');
    }

    public function financialReports(Request $request, Organization $organization, ManagedCommunityService $managed): View
    {
        return $this->programPage($request, $organization, $managed, 'financial-reports');
    }

    private function activityPage(Request $request, Organization $organization, ManagedCommunityService $managed, string $kind): View
    {
        Gate::authorize('manageMemberships', $organization);
        $statusOptions = $this->activityStatusOptions();
        $filters = $this->filters($request, $statusOptions);
        $rows = $organization->activities()
            ->withCount($kind === 'sessions' ? ['sessions'] : [
                'participations',
                'participations as pending_participations_count' => fn ($query) => $query->where('registration_status', ActivityParticipation::REGISTRATION_PENDING),
            ])
            ->when($filters['q'] !== '', fn ($query) => $query->where('title', 'like', '%'.$filters['q'].'%'))
            ->when($filters['status'] !== '', fn ($query) => $query->where('execution_status', $filters['status']))
            ->latest()->paginate($filters['per_page'])->withQueryString()
            ->through(fn ($activity) => [
                'title' => $activity->title,
                'detail' => $kind === 'sessions'
                    ? $activity->sessions_count.' sesi · '.$activity->start_at->translatedFormat('d M Y')
                    : $activity->participations_count.' peserta · '.$activity->pending_participations_count.' menunggu',
                'url' => route($kind === 'sessions' ? 'manager.activities.sessions.index' : 'manager.activities.participants.index', [$organization, $activity]),
                'action' => $kind === 'sessions' ? 'Kelola sesi' : 'Kelola peserta',
                'badgeType' => 'activity',
                'badgeStatus' => $activity->execution_status,
            ]);

        return $this->page($request, $organization, $managed, $rows,
            $kind === 'sessions' ? 'Sesi' : 'Peserta',
            $kind === 'sessions' ? 'Pilih Activity untuk mengatur jadwal sesinya.' : 'Pilih Activity untuk meninjau pendaftaran dan penyelesaian peserta.',
            $filters, $statusOptions, 'Status Activity');
    }

    private function programPage(Request $request, Organization $organization, ManagedCommunityService $managed, string $kind): View
    {
        Gate::authorize('manageMemberships', $organization);
        $statusOptions = $kind === 'logbooks' ? ['with' => 'Ada catatan', 'without' => 'Belum ada catatan'] : [
            'none' => 'Belum ada',
            ProgramProposal::STATUS_DRAFT => 'Draft',
            ProgramProposal::STATUS_SUBMITTED => 'Diajukan',
            ProgramProposal::STATUS_UNDER_REVIEW => 'Sedang ditinjau',
            ProgramProposal::STATUS_REVISION => 'Perlu revisi',
            ProgramProposal::STATUS_REJECTED => 'Ditolak',
            ProgramProposal::STATUS_APPROVED => 'Disetujui',
        ];
        $filters = $this->filters($request, $statusOptions);
        $relation = $kind === 'proposals' ? 'latestProposal' : 'latestFinancialReport';
        $rows = $organization->programs()->with(['latestProposal', 'latestFinancialReport'])
            ->withCount('logbooks')
            ->when($filters['q'] !== '', fn ($query) => $query->where('title', 'like', '%'.$filters['q'].'%'))
            ->when($filters['status'] !== '' && $kind === 'logbooks', fn ($query) => $filters['status'] === 'with' ? $query->has('logbooks') : $query->doesntHave('logbooks'))
            ->when($filters['status'] !== '' && $kind !== 'logbooks', fn ($query) => $filters['status'] === 'none'
                ? $query->doesntHave($relation)
                : $query->whereHas($relation, fn ($record) => $record->where('status', $filters['status'])))
            ->latest()->paginate($filters['per_page'])->withQueryString()
            ->through(function ($program) use ($organization, $kind) {
                $detail = match ($kind) {
                    'proposals' => $program->latestProposal ? 'Proposal versi '.$program->latestProposal->version : 'Proposal belum dibuat',
                    'logbooks' => 'Catatan pelaksanaan Program',
                    default => $program->latestFinancialReport ? 'E-LPJ versi '.$program->latestFinancialReport->version : 'E-LPJ belum dibuat',
                };
                $url = match ($kind) {
                    'logbooks' => route('manager.program-logbooks.index', [$organization, $program]),
                    'financial-reports' => $program->latestFinancialReport
                        ? route('manager.financial-reports.show', [$organization, $program, $program->latestFinancialReport])
                        : route('manager.programs.show', [$organization, $program]).'#financial',
                    default => route('manager.programs.show', [$organization, $program]).'#proposal',
                };

                return ['title' => $program->title, 'detail' => $detail, 'url' => $url,
                    'badgeType' => match ($kind) {
                        'proposals' => 'proposal', 'financial-reports' => 'financial', default => 'logbooks'
                    },
                    'badgeStatus' => match ($kind) {
                        'proposals' => $program->latestProposal?->status, 'financial-reports' => $program->latestFinancialReport?->status, default => $program->logbooks_count
                    },
                    'action' => match ($kind) {
                        'proposals' => 'Buka proposal', 'logbooks' => 'Buka Logbook', default => 'Buka E-LPJ'
                    }];
            });

        return $this->page($request, $organization, $managed, $rows,
            match ($kind) {
                'proposals' => 'Proposal', 'logbooks' => 'Logbook', default => 'E-LPJ'
            },
            'Pilih Program '.$organization->name.' untuk melanjutkan pekerjaan.', $filters, $statusOptions,
            $kind === 'logbooks' ? 'Ketersediaan Logbook' : 'Status '.($kind === 'proposals' ? 'Proposal' : 'E-LPJ'));
    }

    private function page(Request $request, Organization $organization, ManagedCommunityService $managed, $rows, string $title, string $description, array $filters, array $statusOptions, string $statusLabel): View
    {
        return view('manager.operations.index', [
            'organization' => $organization,
            'managedCommunities' => $managed->forUser($request->user()),
            'rows' => $rows,
            'title' => $title,
            'description' => $description,
            'filters' => $filters,
            'statusOptions' => $statusOptions,
            'statusLabel' => $statusLabel,
        ]);
    }

    private function activityStatusOptions(): array
    {
        return [
            Activity::EXECUTION_SCHEDULED => 'Terjadwal',
            Activity::EXECUTION_ONGOING => 'Berlangsung',
            Activity::EXECUTION_COMPLETED => 'Selesai',
            Activity::EXECUTION_CANCELLED => 'Dibatalkan',
        ];
    }

    private function filters(Request $request, array $statusOptions): array
    {
        $status = $request->query('status');
        $perPage = (int) $request->query('per_page', 20);

        return [
            'q' => mb_substr(trim((string) $request->query('q', '')), 0, 80),
            'status' => is_string($status) && array_key_exists($status, $statusOptions) ? $status : '',
            'per_page' => in_array($perPage, [5, 10, 20, 50], true) ? $perPage : 20,
        ];
    }
}
