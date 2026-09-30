<section class="rounded-2xl bg-white p-6 shadow-sm">
    <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
            <p class="text-sm font-bold text-orange-600">EVALUASI AKHIR</p>
            <h2 class="mt-1 text-xl font-black text-[#14205c]">Ringkasan penyelesaian Program</h2>
        </div>
        @if ($program->latestEvaluation)
            <x-program.evaluation-decision-badge :decision="$program->latestEvaluation->decision" />
        @endif
    </div>

    <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-bold text-slate-500">PROPOSAL</p><p class="mt-1 font-extrabold text-[#14205c]">{{ $program->latestProposal?->status ?? 'Belum ada' }}</p></div>
        <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-bold text-slate-500">LOGBOOK DISETUJUI</p><p class="mt-1 font-extrabold text-[#14205c]">{{ $program->logbooks->where('status', 'approved')->count() }}</p></div>
        <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-bold text-slate-500">PROGRES TERAKHIR</p><p class="mt-1 font-extrabold text-[#14205c]">{{ $metrics['latest_reported_progress'] === null ? '—' : $metrics['latest_reported_progress'].'%' }}</p></div>
        <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-bold text-slate-500">E-LPJ TERBARU</p><p class="mt-1 font-extrabold text-[#14205c]">{{ $program->latestFinancialReport?->status ?? 'Belum ada' }}</p></div>
    </div>

    @if ($finalFinancialSummary)
        <div class="mt-4 grid gap-3 sm:grid-cols-3">
            <p class="rounded-xl border border-slate-200 p-3 text-sm">Anggaran<br><strong>{{ app(App\Services\FinancialReportTotalsService::class)->rupiah($finalFinancialSummary['proposal_budget']) }}</strong></p>
            <p class="rounded-xl border border-slate-200 p-3 text-sm">Realisasi<br><strong>{{ app(App\Services\FinancialReportTotalsService::class)->rupiah($finalFinancialSummary['realization_total']) }}</strong></p>
            <p class="rounded-xl border border-slate-200 p-3 text-sm">Selisih<br><strong>{{ app(App\Services\FinancialReportTotalsService::class)->rupiah($finalFinancialSummary['difference']) }}</strong></p>
        </div>
    @endif

    @if ($program->latestEvaluation)
        <div class="mt-5 rounded-xl border border-indigo-100 bg-[#f6f7ff] p-4">
            <div class="flex flex-wrap justify-between gap-2">
                <strong class="text-[#14205c]">Hasil evaluasi {{ $program->latestEvaluation->evaluated_at->translatedFormat('d M Y H:i') }}</strong>
                <span class="text-sm text-slate-500">{{ $program->latestEvaluation->verifier->name }}</span>
            </div>
            <p class="mt-2 whitespace-pre-line text-sm text-slate-600">{{ $program->latestEvaluation->evaluation_notes ?: 'Tidak ada catatan tambahan.' }}</p>
        </div>
    @else
        <p class="mt-5 text-sm text-slate-500">Belum ada evaluasi akhir.</p>
    @endif

    @if ($program->execution_status === 'completed')
        <p class="mt-4 rounded-xl bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">Program selesai dan data administratif historis dikunci.</p>
    @endif
</section>
