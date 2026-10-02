@php($editing = $program->exists)
<form method="POST" action="{{ $editing ? route('manager.programs.update', [$organization, $program]) : route('manager.programs.store', $organization) }}" class="mt-6 space-y-6 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
    @csrf
    @if($editing) @method('PATCH') @endif
    @if($categories->isEmpty())
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">Master kategori Program belum tersedia. Data kategori pengembangan perlu disiapkan terlebih dahulu.</div>
    @endif
    <div class="grid gap-5 md:grid-cols-2">
        <h2 class="border-b border-slate-200 pb-2 text-lg font-bold text-primary-dark md:col-span-2">Identitas Program</h2>
        <label class="md:col-span-2"><span class="label">Nama Program</span><input class="field" name="title" value="{{ old('title', $program->title) }}" required maxlength="220"></label>
        <label><span class="label">Kategori</span><select class="field" name="category_id" required><option value="">Pilih kategori</option>@foreach($categories as $category)<option value="{{ $category->uuid() }}" @selected(old('category_id', $program->category?->uuid()) === $category->uuid())>{{ $category->name }}</option>@endforeach</select></label>
        <div><span class="label">Status</span><div class="field flex items-center"><x-program.status-badge :status="$program->execution_status ?: 'planned'" /></div><p class="mt-1 text-xs text-slate-500">Status Program mengikuti alur Proposal pada fase berikutnya.</p></div>
        <label><span class="label">Tanggal mulai</span><input class="field" type="date" name="start_date" value="{{ old('start_date', $program->start_date?->format('Y-m-d')) }}"></label>
        <h2 class="border-b border-slate-200 pb-2 pt-3 text-lg font-bold text-primary-dark md:col-span-2">Periode dan tujuan</h2>
        <label><span class="label">Tanggal selesai</span><input class="field" type="date" name="end_date" value="{{ old('end_date', $program->end_date?->format('Y-m-d')) }}"></label>
        <label class="md:col-span-2"><span class="label">Deskripsi</span><textarea class="field min-h-32" name="description" maxlength="10000">{{ old('description', $program->description) }}</textarea></label>
        <label class="md:col-span-2"><span class="label">Maksud dan tujuan</span><textarea class="field min-h-36" name="objectives" maxlength="10000">{{ old('objectives', $program->objectives) }}</textarea></label>
    </div>
    @if($errors->any())<div class="rounded-xl bg-red-50 p-4 text-sm text-red-800"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <div class="flex flex-wrap justify-end gap-3"><a class="btn-secondary" href="{{ route('manager.programs.index', $organization) }}">Batal</a><button class="btn-primary" @disabled($categories->isEmpty())>Simpan Rencana</button></div>
</form>
