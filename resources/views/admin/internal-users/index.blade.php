@extends('layouts.admin')
@section('title', 'Pengguna Internal · SIPORA')
@section('content')
<div class="space-y-6">
    <x-ui.page-header eyebrow="Sistem" title="Pengguna Internal" description="Kelola akun Administrator dan Verifier. Akun Youth tidak termasuk dalam halaman ini."><x-slot:actions><a class="btn-primary" href="{{ route('admin.internal-users.create') }}">Tambah Akun Internal</a></x-slot:actions></x-ui.page-header>
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-ui.metric-card title="Verifier Aktif" :value="$summary['verifiers']" :href="route('admin.internal-users.index',['role'=>'verifier','active'=>'1'])" action="Lihat akun" icon="shield-check" />
        <x-ui.metric-card title="Administrator Aktif" :value="$summary['admins']" :href="route('admin.internal-users.index',['role'=>'admin','active'=>'1'])" action="Lihat akun" icon="users" />
        <x-ui.metric-card title="Belum Verifikasi" :value="$summary['unverified']" :href="route('admin.internal-users.index',['verified'=>'0'])" action="Lihat akun" icon="mail" />
        <x-ui.metric-card title="Akun Nonaktif" :value="$summary['inactive']" :href="route('admin.internal-users.index',['active'=>'0'])" action="Lihat akun" icon="user" />
    </div>
    <form method="GET" class="sipora-card grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
        <label><span class="field-label">Cari nama/email</span><input class="field" name="q" maxlength="120" value="{{ $filters['q'] ?? '' }}"></label>
        <label><span class="field-label">Role</span><select class="field" name="role"><option value="">Semua</option><option value="admin" @selected(($filters['role'] ?? '')==='admin')>Administrator</option><option value="verifier" @selected(($filters['role'] ?? '')==='verifier')>Verifier</option></select></label>
        <label><span class="field-label">Status akun</span><select class="field" name="active"><option value="">Semua</option><option value="1" @selected(($filters['active'] ?? '')==='1')>Aktif</option><option value="0" @selected(($filters['active'] ?? '')==='0')>Nonaktif</option></select></label>
        <label><span class="field-label">Verifikasi email</span><select class="field" name="verified"><option value="">Semua</option><option value="1" @selected(($filters['verified'] ?? '')==='1')>Terverifikasi</option><option value="0" @selected(($filters['verified'] ?? '')==='0')>Belum terverifikasi</option></select></label>
        <div class="flex flex-wrap items-end gap-3"><button class="btn-primary">Terapkan</button><a class="btn-outline" href="{{ route('admin.internal-users.index') }}">Reset</a></div>
    </form>
    <section class="table-shell"><div class="overflow-x-auto" tabindex="0" role="region" aria-label="Tabel pengguna internal"><table class="w-full min-w-[980px]"><thead><tr><th scope="col">Nama</th><th scope="col">Email</th><th scope="col">Role</th><th scope="col">Status</th><th scope="col">Verifikasi Email</th><th scope="col">Bergabung</th><th scope="col">Aksi</th></tr></thead><tbody>
    @forelse($users as $target)<tr class="border-t border-border"><th scope="row" class="px-5 py-4 text-left font-semibold">{{ $target->name }}</th><td class="break-all">{{ $target->email }}</td><td><x-ui.badge :tone="$target->hasRole('admin')?'primary':'info'">{{ $target->hasRole('admin')?'Administrator':'Verifier' }}</x-ui.badge></td><td><x-ui.badge :tone="$target->internal_active?'success':'danger'">{{ $target->internal_active?'Aktif':'Nonaktif' }}</x-ui.badge></td><td><x-ui.badge :tone="$target->hasVerifiedEmail()?'success':'warning'">{{ $target->hasVerifiedEmail()?'Terverifikasi':'Belum terverifikasi' }}</x-ui.badge></td><td>{{ $target->created_at?->timezone(config('sipora.display_timezone'))->format('d M Y') }}</td><td><a class="btn-outline btn-sm" href="{{ route('admin.internal-users.show',$target) }}">Lihat akun</a></td></tr>
    @empty<tr><td colspan="7"><x-ui.empty-state icon="users" title="Tidak ada akun yang sesuai" description="Ubah filter atau tambahkan akun internal baru." /></td></tr>@endforelse
    </tbody></table></div></section>{{ $users->links() }}
</div>
@endsection
