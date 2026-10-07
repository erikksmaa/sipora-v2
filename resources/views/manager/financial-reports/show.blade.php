@extends('layouts.manager')

@section('title', 'E-LPJ '.$program->title)

@section('manager-content')
    <div class="space-y-6">
        <x-workspace.page-header eyebrow="E-LPJ versi {{ $report->version }}" title="Laporan Keuangan" :description="$program->title">
            <x-slot:actions>
                <a class="btn-secondary" href="{{ route('manager.programs.show', [$organization, $program]) }}">Detail Program</a>
                <x-program.financial-status-badge :status="$report->status" />
            </x-slot:actions>
        </x-workspace.page-header>

        <section class="rounded-2xl bg-white p-5 shadow-sm sm:p-6">
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ([
                    ['label' => 'ANGGARAN PROPOSAL', 'value' => $summary['proposal_budget'], 'box' => 'bg-[#eef0ff]', 'text' => 'text-[#14205c]'],
                    ['label' => 'REALISASI BELANJA', 'value' => $summary['realization_total'], 'box' => 'bg-orange-50', 'text' => 'text-orange-700'],
                    ['label' => 'PENERIMAAN', 'value' => $summary['income_total'], 'box' => 'bg-emerald-50', 'text' => 'text-emerald-700'],
                    ['label' => 'SELISIH ANGGARAN', 'value' => $summary['difference'], 'box' => $summary['over_budget'] ? 'bg-rose-50' : 'bg-slate-50', 'text' => $summary['over_budget'] ? 'text-rose-700' : 'text-[#14205c]'],
                ] as $metric)
                    <div class="rounded-xl p-4 {{ $metric['box'] }}">
                        <p class="text-xs font-bold text-slate-500">{{ $metric['label'] }}</p>
                        <p class="rupiah mt-1 text-xl font-black {{ $metric['text'] }}">{{ $money->rupiah($metric['value']) }}</p>
                    </div>
                @endforeach
            </div>

            @if ($summary['over_budget'])
                <p class="mt-3 rounded-xl border border-rose-200 bg-rose-50 p-3 text-sm font-semibold text-rose-700">Realisasi belanja melebihi anggaran proposal. Periksa kembali rincian sebelum mengajukan.</p>
            @endif

            @if ($report->notes)
                <div class="mt-5">
                    <h2 class="font-extrabold text-[#14205c]">Catatan umum</h2>
                    <p class="mt-2 whitespace-pre-line text-slate-600">{{ $report->notes }}</p>
                </div>
            @endif

            @if ($report->review_notes)
                <div class="mt-5 rounded-xl border border-orange-200 bg-orange-50 p-4">
                    <strong class="text-orange-700">Catatan Verifier</strong>
                    <p class="mt-1 whitespace-pre-line text-sm">{{ $report->review_notes }}</p>
                </div>
            @endif

            <div class="mt-5 flex flex-wrap gap-3">
                @can('update', $report)
                    <a class="btn-secondary" href="{{ route('manager.financial-reports.edit', [$organization, $program, $report]) }}">Edit catatan</a>
                    <form method="POST" action="{{ route('manager.financial-reports.submit', [$organization, $program, $report]) }}">
                        @csrf
                        <button class="btn-primary">Ajukan E-LPJ</button>
                    </form>
                @endcan
                @can('revise', $report)
                    <form method="POST" action="{{ route('manager.financial-reports.revise', [$organization, $program, $report]) }}">
                        @csrf
                        <button class="btn-primary">Buat versi revisi</button>
                    </form>
                @endcan
            </div>
            <x-input-error class="mt-3" :messages="$errors->get('report')" />
        </section>

        <section class="rounded-2xl bg-white p-5 shadow-sm sm:p-6">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <p class="text-sm font-bold text-orange-600">RINCIAN TRANSAKSI</p>
                    <h2 class="mt-1 text-xl font-black text-[#14205c]">{{ $summary['item_count'] }} item keuangan</h2>
                </div>
                <p class="text-sm text-slate-500">{{ $summary['evidence_count'] }} bukti terlampir</p>
            </div>

            <div class="mt-5 space-y-4">
                @forelse ($report->items as $item)
                    <article class="rounded-xl border border-slate-200 p-4">
                        <div class="flex flex-wrap justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-xs font-bold uppercase {{ $item->transaction_type === 'expense' ? 'text-orange-600' : 'text-emerald-700' }}">
                                    {{ $item->transaction_type === 'expense' ? 'Belanja' : 'Penerimaan' }} · {{ $item->transaction_date->translatedFormat('d M Y') }}
                                </p>
                                <p class="mt-1 break-words font-bold text-[#14205c]">{{ $item->description }}</p>
                            </div>
                            <p class="rupiah font-black text-[#14205c]">{{ $money->rupiah($item->amount) }}</p>
                        </div>
                        @if ($item->receipt_path)
                            <x-media.receipt :url="route('manager.financial-reports.items.receipt', [$organization, $program, $report, $item])" :image="in_array(strtolower(pathinfo($item->receipt_path, PATHINFO_EXTENSION)), ['jpg','jpeg','png','webp'])" />
                        @endif
                        @can('update', $report)
                            <details class="mt-4">
                                <summary class="cursor-pointer text-sm font-bold text-[#243378]">Edit item</summary>
                                <form class="mt-4 grid gap-3 md:grid-cols-2" enctype="multipart/form-data" method="POST" action="{{ route('manager.financial-reports.items.update', [$organization, $program, $report, $item]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <select class="field" name="transaction_type">
                                        <option value="expense" @selected($item->transaction_type === 'expense')>Belanja</option>
                                        <option value="income" @selected($item->transaction_type === 'income')>Penerimaan</option>
                                    </select>
                                    <input class="field" type="date" name="transaction_date" value="{{ $item->transaction_date->format('Y-m-d') }}">
                                    <input class="field md:col-span-2" name="description" value="{{ $item->description }}">
                                    <input class="field" name="amount" inputmode="decimal" value="{{ $item->amount }}">
                                    <input class="field" type="file" name="receipt" accept=".jpg,.jpeg,.png,.webp,.pdf">
                                    <button class="btn-secondary">Simpan item</button>
                                </form>
                                <form class="mt-2" method="POST" action="{{ route('manager.financial-reports.items.destroy', [$organization, $program, $report, $item]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-sm font-bold text-rose-600">Hapus item</button>
                                </form>
                            </details>
                        @endcan
                    </article>
                @empty
                    <p class="rounded-xl border border-dashed border-slate-300 p-8 text-center text-sm text-slate-500">Belum ada transaksi.</p>
                @endforelse
            </div>

            @can('update', $report)
                <form class="mt-6 grid gap-3 md:grid-cols-2" enctype="multipart/form-data" method="POST" action="{{ route('manager.financial-reports.items.store', [$organization, $program, $report]) }}">
                    @csrf
                    <select class="field" name="transaction_type"><option value="expense">Belanja</option><option value="income">Penerimaan</option></select>
                    <input class="field" type="date" name="transaction_date" value="{{ old('transaction_date', now()->toDateString()) }}">
                    <input class="field md:col-span-2" name="description" placeholder="Uraian transaksi" value="{{ old('description') }}">
                    <input class="field" name="amount" inputmode="decimal" placeholder="Nominal, contoh 150000.00" value="{{ old('amount') }}">
                    <input class="field" type="file" name="receipt" accept=".jpg,.jpeg,.png,.webp,.pdf">
                    <div class="md:col-span-2"><button class="btn-primary">Tambah item</button></div>
                </form>
                @foreach (['transaction_type', 'transaction_date', 'description', 'amount', 'receipt'] as $field)
                    <x-input-error :messages="$errors->get($field)" />
                @endforeach
            @endcan
        </section>
    </div>
@endsection
