<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $heading }} — {{ $title }}</title>
    <style>
        @page { size: A4 portrait; margin: 15mm; }
        * { box-sizing: border-box; }
        body { margin: 0; color: #000; background: #fff; font: 11pt/1.4 Arial, sans-serif; }
        .sheet { max-width: 180mm; margin: 24px auto; padding: 0 12px; }
        h1 { font-size: 14pt; text-align: center; margin: 0 0 6px; }
        h2 { font-size: 12pt; text-align: center; margin: 0 0 24px; }
        dl { display: grid; grid-template-columns: 32mm 1fr; gap: 5px 8px; margin: 0 0 18px; }
        dt { font-weight: bold; } dd { margin: 0; overflow-wrap: anywhere; }
        table { border-collapse: collapse; width: 100%; table-layout: fixed; }
        th, td { border: 1px solid #000; padding: 6px 8px; overflow-wrap: anywhere; }
        th { text-align: center; } td { height: 10mm; } .number { width: 10mm; text-align: center; }
        .signature { width: 40%; } .phone { width: 32mm; }
        thead { display: table-header-group; } tr { break-inside: avoid; page-break-inside: avoid; }
        .controls { max-width: 180mm; margin: 20px auto; padding: 12px; border: 1px solid #aaa; }
        .controls form { display: inline-block; margin: 0 16px 10px 0; }
        button { padding: 8px 12px; cursor: pointer; } .controls p { margin: 4px 0; font-size: 10pt; }
        @media print { .controls { display: none; } .sheet { max-width: none; margin: 0; padding: 0; } }
    </style>
</head>
<body>
    <aside class="controls" aria-label="Pengaturan cetak">
        <form method="get">
            <label><input type="checkbox" name="include_phone" value="1" @checked($includePhone)> Sertakan nomor HP</label>
            <button type="submit">Terapkan</button>
        </form>
        <button type="button" onclick="window.print()">Cetak / Simpan PDF</button>
        <p>Hanya peserta yang diterima. Lembar fisik ini tidak mengubah presensi digital.</p>
    </aside>
    <main class="sheet">
        <h1>{{ $heading }}</h1>
        <h2>{{ $title }}</h2>
        <dl>
            <dt>Nama {{ $heading === 'DAFTAR HADIR KEGIATAN' ? 'Kegiatan' : 'Program' }}</dt><dd>{{ $title }}</dd>
            <dt>Penyelenggara</dt><dd>{{ $organizer }}</dd>
            <dt>Tanggal</dt><dd>{{ $date ?: '—' }}</dd>
            <dt>Tempat</dt><dd>{{ $venue ?: '—' }}</dd>
        </dl>
        <table>
            <thead><tr><th scope="col" class="number">No</th><th scope="col">Nama</th>@if($includePhone)<th scope="col" class="phone">No. HP</th>@endif<th scope="col" class="signature">Tanda Tangan</th></tr></thead>
            <tbody>
                @forelse($rows as $row)
                    <tr><td class="number">{{ $loop->iteration }}</td><td>{{ $row['name'] }}</td>@if($includePhone)<td>{{ $row['phone'] }}</td>@endif<td></td></tr>
                @empty
                    <tr><td colspan="{{ $includePhone ? 4 : 3 }}">Belum ada peserta yang diterima.</td></tr>
                @endforelse
            </tbody>
        </table>
    </main>
</body>
</html>
