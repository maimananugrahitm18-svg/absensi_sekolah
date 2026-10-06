<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Presensi Guru</title>
    <style>
        table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; font-size: 12px; }
        th { background-color: #4f46e5; color: #ffffff; padding: 8px; text-align: center; border: 1px solid #333333; }
        td { padding: 6px 8px; border: 1px solid #cccccc; }
        .text-center { text-align: center; }
        .title { font-size: 16px; font-weight: bold; margin-bottom: 5px; }
        .subtitle { font-size: 12px; color: #555555; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="title">LAPORAN PRESENSI GURU & STAF</div>
    <div class="subtitle">Tanggal: {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}</div>

    <table>
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th style="width: 120px;">NIP</th>
                <th style="width: 220px;">Nama Lengkap Guru</th>
                <th style="width: 100px;">Jam Masuk</th>
                <th style="width: 100px;">Jam Pulang</th>
                <th style="width: 110px;">Status</th>
                <th style="width: 200px;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($presensi as $index => $p)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td style="mso-number-format:'\@';">{{ $p->guru->nip ?? '-' }}</td>
                    <td>{{ $p->guru->nama ?? '-' }}</td>
                    <td class="text-center">{{ $p->jam_masuk ? substr($p->jam_masuk, 0, 5) : '-' }}</td>
                    <td class="text-center">{{ $p->jam_pulang ? substr($p->jam_pulang, 0, 5) : '-' }}</td>
                    <td class="text-center">{{ strtoupper($p->status) }}</td>
                    <td>{{ $p->keterangan ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data presensi pada tanggal ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
