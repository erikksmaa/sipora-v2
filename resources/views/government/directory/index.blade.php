@extends($workspace === 'admin' ? 'layouts.admin' : 'layouts.verifier')
@section('title', $title.' · SIPORA')
@section($workspace === 'admin' ? 'content' : 'verifier-content')
<div class="space-y-6">
    <x-ui.page-header eyebrow="Data Ekosistem" :title="$title" description="Direktori data terdaftar SIPORA, terpisah dari antrean verifikasi." />
    <p class="text-sm text-text-secondary">{{ number_format($records->total()) }} record sesuai filter. Halaman ini hanya untuk membaca data. @if($kind === 'youth')Portfolio privat dan bagian yang disembunyikan tetap dibatasi; dokumen identitas tidak ditampilkan.@endif</p>
    <x-workspace.filter-bar :reset-href="route($workspace.'.directory.'.$kind.'.index')">
        <label><span class="label">Cari nama {{ $kind === 'youth' ? 'pemuda' : 'atau judul' }}</span><input class="field" name="q" maxlength="120" value="{{ $filters['q'] ?? '' }}" placeholder="Cari di direktori"></label>
        @if($kind === 'youth')
            <label><span class="label">Kecamatan</span><select class="field" name="district"><option value="">Semua kecamatan</option>@foreach($districts as $district)<option value="{{ $district->code }}" @selected(($filters['district'] ?? '') === $district->code)>{{ $district->name }}</option>@endforeach</select></label>
            <label><span class="label">Visibilitas Portfolio</span><select class="field" name="profile"><option value="">Semua</option><option value="public" @selected(($filters['profile'] ?? '') === 'public')>Publik</option><option value="private" @selected(($filters['profile'] ?? '') === 'private')>Privat</option></select></label>
            @if($workspace === 'admin')<label><span class="label">Status identitas</span><select class="field" name="identity"><option value="">Semua</option>@foreach(['unverified'=>'Belum terverifikasi','pending'=>'Menunggu review','verified'=>'Terverifikasi','revision'=>'Perlu revisi','rejected'=>'Ditolak'] as $value=>$label)<option value="{{ $value }}" @selected(($filters['identity'] ?? '') === $value)>{{ $label }}</option>@endforeach</select></label>@endif
        @else
            <label><span class="label">{{ $kind === 'programs' ? 'Pelaksanaan' : 'Status verifikasi' }}</span><select class="field" name="status"><option value="">Semua</option>@foreach($kind === 'programs' ? ['planned'=>'Direncanakan','running'=>'Berjalan','completed'=>'Selesai','cancelled'=>'Dibatalkan'] : ['draft'=>'Draft','pending_review'=>'Menunggu review','revision'=>'Perlu revisi','rejected'=>'Ditolak','approved'=>'Disetujui'] as $value=>$label)<option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>@endforeach</select></label>
            @if($kind === 'communities')
                <label><span class="label">Kategori</span><select class="field" name="category"><option value="">Semua kategori</option>@foreach($categories as $category)<option value="{{ $category->slug }}" @selected(($filters['category'] ?? '') === $category->slug)>{{ $category->name }}</option>@endforeach</select></label>
                <label><span class="label">Operasional</span><select class="field" name="operational"><option value="">Semua</option><option value="active" @selected(($filters['operational'] ?? '') === 'active')>Aktif</option><option value="inactive" @selected(($filters['operational'] ?? '') === 'inactive')>Tidak aktif</option></select></label>
            @elseif($kind === 'activities')<label><span class="label">Publikasi</span><select class="field" name="publication"><option value="">Semua</option>@foreach(['published'=>'Terbit','unpublished'=>'Belum terbit','archived'=>'Diarsipkan'] as $value=>$label)<option value="{{ $value }}" @selected(($filters['publication'] ?? '') === $value)>{{ $label }}</option>@endforeach</select></label>@endif
        @endif
    </x-workspace.filter-bar>
    <section class="table-shell">
        <div class="overflow-x-auto" tabindex="0" role="region" aria-label="Tabel {{ $title }}, geser horizontal jika diperlukan">
            <table class="w-full min-w-[960px] text-left"><caption class="sr-only">{{ $title }}</caption><thead><tr><th scope="col">{{ $kind === 'youth' || $kind === 'communities' ? 'Nama' : 'Judul' }}</th>@foreach($columns as $column)<th scope="col">{{ $column }}</th>@endforeach<th scope="col">Detail</th></tr></thead><tbody>
            @forelse($records as $record)
                <tr class="border-t border-border hover:bg-surface-soft"><th scope="row" class="min-w-56 px-5 py-4 text-left font-semibold"><a href="{{ $record['url'] }}" class="flex items-center gap-3 text-text-primary hover:text-interactive">@if($record['photo'])<img data-media-fallback src="{{ $record['photo'] }}" alt="" class="size-11 shrink-0 rounded border border-border bg-white object-contain" loading="lazy">@endif<span class="break-words">{{ $record['name'] }}</span></a></th>@foreach($record['cells'] as $cell)<td class="min-w-28 max-w-xs break-words">{{ $cell }}</td>@endforeach<td><a class="btn-outline btn-sm whitespace-nowrap" href="{{ $record['url'] }}" aria-label="Detail {{ $record['name'] }}">Lihat detail</a></td></tr>
            @empty<tr><td colspan="{{ count($columns) + 2 }}"><x-ui.empty-state icon="users" title="Tidak ada data yang sesuai" description="Belum ada record terdaftar atau tidak ada yang cocok dengan filter ini." /></td></tr>@endforelse
            </tbody></table>
        </div>
    </section>
    {{ $records->links() }}
    @if($kind === 'youth' && $workspace === 'verifier')<p class="text-xs text-text-secondary">Pencarian nama dan kecamatan hanya mencocokkan Portfolio publik. Profil privat tetap tercatat dengan nama netral, tanpa minat, skill, atau detail partisipasi yang disembunyikan.</p>@endif
</div>
@endsection
