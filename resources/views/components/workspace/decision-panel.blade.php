@props(['title' => 'Keputusan review', 'description' => 'Pilih keputusan berdasarkan bukti yang tersedia. Validasi status tetap dilakukan server.', 'action', 'notesName' => 'notes', 'notesLabel' => 'Catatan keputusan', 'approveLabel' => 'Setujui', 'approveValue' => 'approved'])
<section x-data="{ decision: '{{ old('decision', $approveValue) }}' }" {{ $attributes->class(['rounded-2xl border border-border bg-white p-5 shadow-sm sm:p-6']) }}>
    <h2 class="text-xl font-bold text-text-primary">{{ $title }}</h2><p class="mt-1 text-sm leading-6 text-text-secondary">{{ $description }}</p>
    <form class="mt-5 space-y-4" method="POST" action="{{ $action }}">@csrf
        <fieldset><legend class="field-label">Keputusan</legend><div class="grid gap-2 sm:grid-cols-3">
            @foreach([$approveValue => [$approveLabel, 'success'], 'revision' => ['Minta revisi', 'warning'], 'rejected' => ['Tolak', 'danger']] as $value => [$label, $tone])
                <label @class(['cursor-pointer rounded-xl border p-3 text-sm font-bold transition', 'border-success bg-success-soft text-success' => $tone === 'success', 'border-warning bg-warning-soft text-[#8A5A0A]' => $tone === 'warning', 'border-danger bg-danger-soft text-danger' => $tone === 'danger'])><input class="mr-2" type="radio" name="decision" value="{{ $value }}" x-model="decision">{{ $label }}</label>
            @endforeach
        </div></fieldset>
        <label><span class="field-label">{{ $notesLabel }} <span x-show="decision !== '{{ $approveValue }}'" class="text-danger">wajib untuk revisi/penolakan</span></span><textarea class="field min-h-28" name="{{ $notesName }}" maxlength="3000" :required="decision !== '{{ $approveValue }}'" placeholder="Tuliskan alasan yang spesifik dan dapat ditindaklanjuti.">{{ old($notesName) }}</textarea></label>
        <x-input-error :messages="$errors->get($notesName)" /><button class="btn-primary" type="submit">Simpan keputusan</button>
    </form>
</section>
