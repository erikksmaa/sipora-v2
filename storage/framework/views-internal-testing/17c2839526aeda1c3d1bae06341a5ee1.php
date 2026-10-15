<section class="rounded-2xl bg-white p-6 shadow-sm">
    <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
            <p class="text-sm font-bold text-orange-600">EVALUASI AKHIR</p>
            <h2 class="mt-1 text-xl font-black text-[#14205c]">Ringkasan penyelesaian Program</h2>
        </div>
        <?php if($program->latestEvaluation): ?>
            <?php if (isset($component)) { $__componentOriginala6bce03fe02cebbc32a28be3596d5a2b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala6bce03fe02cebbc32a28be3596d5a2b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.program.evaluation-decision-badge','data' => ['decision' => $program->latestEvaluation->decision]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('program.evaluation-decision-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['decision' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($program->latestEvaluation->decision)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala6bce03fe02cebbc32a28be3596d5a2b)): ?>
<?php $attributes = $__attributesOriginala6bce03fe02cebbc32a28be3596d5a2b; ?>
<?php unset($__attributesOriginala6bce03fe02cebbc32a28be3596d5a2b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala6bce03fe02cebbc32a28be3596d5a2b)): ?>
<?php $component = $__componentOriginala6bce03fe02cebbc32a28be3596d5a2b; ?>
<?php unset($__componentOriginala6bce03fe02cebbc32a28be3596d5a2b); ?>
<?php endif; ?>
        <?php endif; ?>
    </div>

    <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-bold text-slate-500">PROPOSAL</p><p class="mt-1 font-extrabold text-[#14205c]"><?php echo e($program->latestProposal?->status ?? 'Belum ada'); ?></p></div>
        <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-bold text-slate-500">LOGBOOK DISETUJUI</p><p class="mt-1 font-extrabold text-[#14205c]"><?php echo e($program->logbooks->where('status', 'approved')->count()); ?></p></div>
        <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-bold text-slate-500">PROGRES TERAKHIR</p><p class="mt-1 font-extrabold text-[#14205c]"><?php echo e($metrics['latest_reported_progress'] === null ? '—' : $metrics['latest_reported_progress'].'%'); ?></p></div>
        <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-bold text-slate-500">E-LPJ TERBARU</p><p class="mt-1 font-extrabold text-[#14205c]"><?php echo e($program->latestFinancialReport?->status ?? 'Belum ada'); ?></p></div>
    </div>

    <?php if($finalFinancialSummary): ?>
        <div class="mt-4 grid gap-3 sm:grid-cols-3">
            <p class="rounded-xl border border-slate-200 p-3 text-sm">Anggaran<br><strong><?php echo e(app(App\Services\FinancialReportTotalsService::class)->rupiah($finalFinancialSummary['proposal_budget'])); ?></strong></p>
            <p class="rounded-xl border border-slate-200 p-3 text-sm">Realisasi<br><strong><?php echo e(app(App\Services\FinancialReportTotalsService::class)->rupiah($finalFinancialSummary['realization_total'])); ?></strong></p>
            <p class="rounded-xl border border-slate-200 p-3 text-sm">Selisih<br><strong><?php echo e(app(App\Services\FinancialReportTotalsService::class)->rupiah($finalFinancialSummary['difference'])); ?></strong></p>
        </div>
    <?php endif; ?>

    <?php if($program->latestEvaluation): ?>
        <div class="mt-5 rounded-xl border border-indigo-100 bg-[#f6f7ff] p-4">
            <div class="flex flex-wrap justify-between gap-2">
                <strong class="text-[#14205c]">Hasil evaluasi <?php echo e($program->latestEvaluation->evaluated_at->translatedFormat('d M Y H:i')); ?></strong>
                <span class="text-sm text-slate-500"><?php echo e($program->latestEvaluation->verifier->name); ?></span>
            </div>
            <p class="mt-2 whitespace-pre-line text-sm text-slate-600"><?php echo e($program->latestEvaluation->evaluation_notes ?: 'Tidak ada catatan tambahan.'); ?></p>
        </div>
    <?php else: ?>
        <p class="mt-5 text-sm text-slate-500">Belum ada evaluasi akhir.</p>
    <?php endif; ?>

    <?php if($program->execution_status === 'completed'): ?>
        <p class="mt-4 rounded-xl bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">Program selesai dan data administratif historis dikunci.</p>
    <?php endif; ?>
</section>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/manager/programs/_evaluation.blade.php ENDPATH**/ ?>