@php($genderLabels = ['male' => 'Laki-laki', 'female' => 'Perempuan', 'other' => 'Lainnya', 'prefer_not_to_say' => 'Memilih tidak menyebutkan', 'unknown' => 'Belum diisi'])
@php($statusLabels = ['scheduled' => 'Terjadwal', 'ongoing' => 'Berlangsung', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan', 'active' => 'Aktif', 'inactive' => 'Tidak aktif', 'planned' => 'Direncanakan', 'running' => 'Berjalan'])
<div class="stats-rows" aria-label="Rincian data {{ $kind }}">
    @forelse($rows as $row)
        <div><span>{{ $kind === 'gender' ? ($genderLabels[$row['label']] ?? $row['label']) : ($kind === 'status' ? ($statusLabels[$row['label']] ?? $row['label']) : $row['label']) }}</span><strong>{{ $row['count'] === null ? '<'.$threshold : number_format($row['count']) }}</strong></div>
    @empty
        <p>Belum ada data untuk kategori ini.</p>
    @endforelse
</div>
