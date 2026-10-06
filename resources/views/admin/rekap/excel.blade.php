<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body { font-family: 'Calibri', 'Arial', sans-serif; font-size: 11pt; }
        .title { font-size: 14pt; font-weight: bold; text-align: center; }
        .subtitle { font-size: 11pt; text-align: center; color: #555555; }
        .class-banner {
            background-color: #312e81;
            color: #ffffff;
            font-size: 12pt;
            font-weight: bold;
            padding: 8px 12px;
            vertical-align: middle;
        }
        .class-meta {
            background-color: #e0e7ff;
            color: #1e1b4b;
            font-size: 10pt;
            font-style: italic;
            padding: 4px 12px;
        }
        .header-cell {
            background-color: #4f46e5;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            border: 0.5pt solid #1e1b4b;
            padding: 6px;
        }
        .data-cell {
            border: 0.5pt solid #cbd5e1;
            padding: 5px;
            vertical-align: middle;
        }
        .subtotal-cell {
            background-color: #f1f5f9;
            font-weight: bold;
            border: 0.5pt solid #94a3b8;
            padding: 6px;
            vertical-align: middle;
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .str { mso-number-format: "\@"; }
        .badge-hadir { background-color: #dcfce7; color: #15803d; font-weight: bold; }
        .badge-izin { background-color: #e0f2fe; color: #0369a1; font-weight: bold; }
        .badge-sakit { background-color: #fef3c7; color: #b45309; font-weight: bold; }
        .badge-alpha { background-color: #fee2e2; color: #b91c1c; font-weight: bold; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="9" class="title">LAPORAN REKAPITULASI ABSENSI SISWA PER KELAS</td>
        </tr>
        <tr>
            <td colspan="9" class="subtitle">SMA NUSANTARA</td>
        </tr>
        <tr>
            <td colspan="9" class="subtitle">
                Periode: {{ $tahunAjaran ? $tahunAjaran->nama . ' (' . ucfirst($tahunAjaran->semester) . ')' : 'Semua Periode' }}
                @if($kelasFilter) | Filter Kelas: {{ $kelasFilter->nama_lengkap }} @endif
                @if($bulan) | Bulan: {{ \Carbon\Carbon::create(null, $bulan)->locale('id')->isoFormat('MMMM') }} @endif
            </td>
        </tr>
        <tr>
            <td colspan="9" style="text-align:right; font-size:9pt; color:#666;">Tanggal Unduh: {{ now()->locale('id')->isoFormat('D MMMM Y, HH:mm') }} WIB</td>
        </tr>
        <tr><td></td></tr>

        @forelse($rekapPerKelas as $group)
            @php
                $k = $group['kelas'];
            @endphp
            {{-- Class Header Banner --}}
            <tr>
                <td colspan="9" class="class-banner">
                    KELAS: {{ $k ? strtoupper($k->nama_lengkap) : 'SISWA TANPA KELAS' }}
                </td>
            </tr>
            @if($k)
                <tr>
                    <td colspan="9" class="class-meta">
                        Wali Kelas: {{ $k->wali_kelas ?? '-' }} | Jurusan: {{ $k->jurusan ?? '-' }} | Total: {{ $group['total_siswa'] }} Siswa | Rata-rata Kehadiran: {{ $group['avg_pct'] }}%
                    </td>
                </tr>
            @endif
            <thead>
                <tr>
                    <th class="header-cell" style="width: 40px;">No</th>
                    <th class="header-cell" style="width: 100px;">NIS</th>
                    <th class="header-cell" style="width: 240px;">Nama Siswa</th>
                    <th class="header-cell" style="width: 70px;">Hadir</th>
                    <th class="header-cell" style="width: 70px;">Izin</th>
                    <th class="header-cell" style="width: 70px;">Sakit</th>
                    <th class="header-cell" style="width: 70px;">Alpa</th>
                    <th class="header-cell" style="width: 80px;">Total</th>
                    <th class="header-cell" style="width: 90px;">% Hadir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($group['items'] as $idx => $row)
                    @php
                        $s = $row['siswa'];
                        $pct = $row['pct'];
                    @endphp
                    <tr>
                        <td class="data-cell text-center">{{ $idx + 1 }}</td>
                        <td class="data-cell text-center str">{{ $s->nis ?? '-' }}</td>
                        <td class="data-cell text-left">{{ $s->nama }}</td>
                        <td class="data-cell text-center fw-bold badge-hadir">{{ $row['hadir'] }}</td>
                        <td class="data-cell text-center fw-bold badge-izin">{{ $row['izin'] }}</td>
                        <td class="data-cell text-center fw-bold badge-sakit">{{ $row['sakit'] }}</td>
                        <td class="data-cell text-center fw-bold badge-alpha">{{ $row['alpha'] }}</td>
                        <td class="data-cell text-center fw-bold">{{ $row['total'] }}</td>
                        <td class="data-cell text-center fw-bold" style="color: {{ $pct >= 80 ? '#15803d' : ($pct >= 60 ? '#b45309' : '#b91c1c') }};">
                            {{ $pct }}%
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="data-cell text-center" style="padding: 10px;">Tidak ada data siswa untuk kelas ini.</td>
                    </tr>
                @endforelse
                {{-- Subtotal Row --}}
                @if(count($group['items']) > 0)
                    <tr>
                        <td colspan="3" class="subtotal-cell text-right">SUBTOTAL KELAS {{ $k ? strtoupper($k->nama_lengkap) : '' }}:</td>
                        <td class="subtotal-cell text-center badge-hadir">{{ $group['sum_hadir'] }}</td>
                        <td class="subtotal-cell text-center badge-izin">{{ $group['sum_izin'] }}</td>
                        <td class="subtotal-cell text-center badge-sakit">{{ $group['sum_sakit'] }}</td>
                        <td class="subtotal-cell text-center badge-alpha">{{ $group['sum_alpha'] }}</td>
                        <td class="subtotal-cell text-center">{{ $group['total_semua'] }}</td>
                        <td class="subtotal-cell text-center">{{ $group['avg_pct'] }}%</td>
                    </tr>
                @endif
            </tbody>
            {{-- Spacer between classes --}}
            <tr><td colspan="9" style="height: 15px;"></td></tr>
        @empty
            <tr>
                <td colspan="9" class="data-cell text-center" style="padding: 20px;">Tidak ada data rekapitulasi kehadiran.</td>
            </tr>
        @endforelse
    </table>
</body>
</html>
