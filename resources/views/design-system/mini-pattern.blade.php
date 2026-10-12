<h3>{{ $patternTitle }}</h3>
@switch($kind)
@case('youth-dashboard')
    <div class="sipora-card !p-4"><p class="text-xs text-interactive font-bold">Perjalanan Youth</p><p class="text-lg font-extrabold mt-2">3 dari 5 tahap selesai</p><div class="h-3 bg-surface-soft mt-3"><div class="h-full bg-interactive w-3/5"></div></div></div><div class="ds-mini-row mt-4"><strong>Pendaftaran terbaru</strong><x-activity.registration-badge status="accepted" /></div><div class="ds-mini-row"><strong>Activity Passport terbaru</strong><span>08 selesai</span></div><p class="ds-caption">Main content + aside: kelengkapan profil / terbaru</p>
@break
@case('readiness')
    <div class="sipora-card !p-4 flex gap-4 items-center"><div class="grid size-20 shrink-0 place-items-center rounded-full p-2" style="background:conic-gradient(#f59e0b 80%,#e8eaf4 0)"><div class="grid size-full place-items-center rounded-full bg-white"><strong class="text-[#243378]">80%</strong></div></div><div><p class="text-xs font-bold text-orange-600">Kelengkapan profil</p><p class="font-bold mt-2">Lengkapi profilmu</p><p class="text-xs text-text-secondary mt-2">Nilai ini bukan peringkat.</p></div></div>
@break
@case('queues')
    <p class="text-xs font-bold mb-4">{{ $patternTitle === 'Verifier Dashboard' ? 'Ringkasan antrean' : 'Antrean operasional' }}</p><div class="grid grid-cols-2 gap-3">@foreach(['Community'=>4,'Activity'=>8,'Proposal'=>3,'E-LPJ'=>2] as $label => $count)<div class="surface-card !p-3"><p class="text-xs text-text-secondary">{{ $label }}</p><strong class="block text-2xl mt-2">{{ $count }}</strong><p class="text-xs text-primary mt-2">Buka antrean</p></div>@endforeach</div>
@break
@case('passport')
    <div class="sipora-card !p-4"><x-activity.completion-badge status="completed" /><p class="font-bold text-sm mt-3">Workshop Web Development</p><p class="text-xs text-text-secondary mt-2">Penyelenggara · periode · peran · lokasi</p><div class="ds-mini-row mt-4"><span>3 dari 3 sesi hadir</span><span>Sertifikat</span></div></div><p class="ds-caption">Data tepercaya SIPORA · catatan privat</p>
@break
@case('certificate')
    <div class="sipora-card !p-4"><p class="text-xs text-orange-600 font-bold">SIPORA Verified Certificate</p><p class="text-lg font-bold mt-2">Workshop Web Development</p><span class="status-badge badge-success mt-3">Terverifikasi</span><p class="font-mono text-xs mt-4">SIPORA-CERT-2026-001</p><div class="ds-row mt-4 text-xs font-bold"><span>Lihat</span><span>Unduh PDF</span><span>Verifikasi</span></div></div>
@break
@case('program-list')
    <div class="border border-border bg-white p-4"><p class="text-xs text-orange-600">Teknologi</p><p class="font-bold text-sm mt-2">Pemuda Digital 2026</p><p class="text-xs text-text-muted mt-2">Okt – Des 2026 · 8 Activity</p><x-program.status-badge status="running" class="mt-4" /></div>
@break
@case('logbook')
    <div class="ds-row mb-3 text-xs"><span>Total Activity: 8</span><span>Progres dilaporkan: 60%</span></div><div class="sipora-card !p-4"><p class="text-xs text-orange-600 font-bold">11 Oktober 2026</p><p class="text-sm font-bold mt-2">Ringkasan pelaksanaan program</p><p class="text-xs text-text-muted mt-2">Pengelola · 3 media · 60%</p><x-program.logbook-status-badge status="submitted" class="mt-3" /></div>
@break
@case('session')
    <div class="sipora-card !p-4"><p class="font-bold text-sm">Sesi 01 · Belajar bersama</p><p class="text-xs text-text-muted mt-2">14 November · Creative Hub</p><div class="ds-row mt-4 text-xs font-bold"><span>Presensi</span><span>Edit</span><span>Lainnya</span></div></div>
@break
@case('hero')
    <div class="ds-mini-hero"><p class="font-display text-2xl font-bold">Temukan ruang untuk<br>bertumbuh bersama.</p><div class="ds-row mt-4"><span class="btn-accent btn-sm">Jelajahi Activity</span><span class="px-sprite px-knight" aria-hidden="true"></span></div></div><div class="grid grid-cols-3 gap-3">@foreach(['Activity','Community','Opportunity'] as $text)<div class="border border-border p-3 text-xs font-bold">{{ $text }}</div>@endforeach</div>
@break
@case('listing')
    <div class="border border-border p-3 text-xs text-text-muted mb-4">Search · Kategori · Kecamatan</div><div class="grid grid-cols-2 gap-3">@foreach(['Belajar bersama','Ruang kolaborasi'] as $text)<div class="border border-border bg-surface p-3"><span class="status-badge badge-accent">Terbuka</span><p class="font-bold text-sm mt-3">{{ $text }}</p><p class="text-xs text-text-muted mt-2">Pemalang · data contoh</p></div>@endforeach</div>
@break
@case('detail')
    <div class="border-b border-border pb-4"><span class="status-badge badge-success">Terverifikasi</span><p class="text-sm mt-3 text-text-secondary">Profil / deskripsi / metadata</p></div><div class="ds-mini-row"><span>Informasi</span><span>Dokumen</span></div><div class="ds-mini-row"><span>Riwayat</span><span>Lihat detail</span></div>
@break
@case('stats')
    <div class="grid grid-cols-2 gap-3"><x-public.stat-card value="128" label="Pemuda · contoh" icon="users" /><x-public.stat-card value="24" label="Activity · contoh" icon="activity" /></div><div class="border border-border mt-4 p-3"><svg viewBox="0 0 320 70" role="img" aria-label="Ilustrasi tren contoh"><path d="M5 60H315M5 10V60" stroke="var(--color-border)" fill="none"/><path d="M10 50 65 45 115 48 170 28 225 32 305 10" stroke="#FE7743" stroke-width="3" fill="none"/></svg></div>
@break
@case('map')
    <div class="border border-border p-3 text-xs">Youth · Community · Activity · Program</div><div class="eco-map-canvas ds-map-placeholder !min-h-0 !h-36 mt-3"><x-ui.icon name="map-pin" class="size-8 text-primary" /><span class="ds-caption">Agregat kecamatan · tanpa Youth pin</span></div>
@break
@case('form')
    <div class="space-y-3"><div><span class="field-label !text-xs">Informasi utama</span><div class="field text-text-muted">Data contoh</div></div><div><span class="field-label !text-xs">Keterangan</span><div class="field !min-h-16">Deskripsi / dokumen</div></div><span class="btn-primary btn-sm">Simpan</span></div>
@break
@case('chips')
    <p class="text-xs text-text-secondary mb-4">Pilih sesuai minat atau keahlianmu.</p><div class="ds-row">@foreach(['Teknologi','Olahraga','Kreativitas'] as $text)<span class="interest-chip">{{ $text }}</span>@endforeach</div><span class="btn-primary btn-sm mt-6">Simpan</span>
@break
@case('progress')
    <div class="ds-mini-row"><strong>01 · Arah Pengembanganmu</strong><span>Minat</span></div><div class="ds-mini-row"><strong>02 · Jejak pengembanganmu</strong><span>Skill / pengalaman</span></div><div class="ds-mini-row"><strong>03 · Langkah Berikutnya</strong><span>Rekomendasi</span></div><div class="ds-mini-row"><strong>04 · Riwayat Pengembangan</strong><span>Activity</span></div><p class="text-xs mt-3 text-text-secondary">Berdasarkan minat pilihan sendiri; bukan penilaian kemampuan.</p>
@break
@case('forum')
    <div class="ds-mini-row"><span class="font-bold">Kategori</span><span>Diskusi terbaru</span></div><div class="sipora-card !p-4 mt-3"><p class="text-xs text-interactive">Teknologi</p><p class="text-sm font-bold mt-2">Mulai belajar web dari mana?</p><p class="text-xs text-text-muted mt-3">4 balasan · data contoh</p></div>
@break
@case('feed')
    <div class="sipora-card !p-4"><p class="text-sm font-bold">Komunitas Kreatif Pemalang</p><p class="text-xs text-text-muted mt-1">Update Community</p><p class="text-sm mt-4">Belajar dan berkarya bersama pemuda.</p><div class="ds-row border-t border-border mt-4 pt-3 text-xs"><span>Suka · 12</span><span>Komentar · 3</span></div></div>
@break
@default
    <div class="table-shell"><table><thead><tr><th>Nama</th><th>Status</th></tr></thead><tbody><tr><td>Data contoh A</td><td><x-activity.registration-badge status="accepted" /></td></tr><tr><td>Data contoh B</td><td><x-activity.registration-badge status="pending" /></td></tr></tbody></table></div>
@endswitch
