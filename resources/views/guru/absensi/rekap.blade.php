@extends('layouts.app')

@section('title', 'Rekap Kehadiran Kelas')
@section('page-title', 'Rekap Kehadiran Kelas')
@section('page-subtitle', 'Laporan ringkasan absensi per mata pelajaran & kelas')

@section('content')
<div class="content-card mb-4 d-print-none">
    <div class="content-card-header">
        <h6 class="content-card-title"><i class="fas fa-filter me-2 text-primary"></i>Pilih Jadwal & Periode</h6>
    </div>
    <div class="p-3">
        <form method="GET" action="{{ route('guru.absensi.rekap') }}" class="row g-3">
            <div class="col-md-7">
                <label class="form-label small fw-semibold">Pilih Jadwal Pelajaran <span class="text-danger">*</span></label>
                <select name="jadwal_id" class="form-select" required>
                    <option value="">-- Pilih Jadwal --</option>
                    @foreach($jadwals as $j)
                        <option value="{{ $j->id }}" {{ request('jadwal_id') == $j->id ? 'selected' : '' }}>
                            {{ $j->mataPelajaran?->nama }} - {{ $j->kelas?->tingkat }} {{ $j->kelas?->nama }} ({{ $j->hari }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Bulan (Opsional)</label>
                <select name="bulan" class="form-select">
                    <option value="">Semua Bulan</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ sprintf('%02d', $m) }}" {{ request('bulan') == sprintf('%02d', $m) ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m)->locale('id')->isoFormat('MMMM') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

@if($selectedJadwal)
    <div class="content-card">
        <div class="content-card-header d-flex justify-content-between align-items-center">
            <div>
                <h6 class="content-card-title mb-1">
                    <i class="fas fa-chart-bar me-2 text-primary"></i>
                    {{ $selectedJadwal->mataPelajaran?->nama }} - {{ $selectedJadwal->kelas?->tingkat }} {{ $selectedJadwal->kelas?->nama }}
                </h6>
                <div class="text-muted small">Guru Pengampu: {{ auth()->user()->guru?->nama }}</div>
            </div>
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm d-print-none">
                <i class="fas fa-print me-1"></i> Cetak Laporan
            </button>
        </div>
        <div class="content-card-body p-0">
            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:50px;">No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th class="text-center text-success">Hadir</th>
                            <th class="text-center text-info">Izin</th>
                            <th class="text-center text-warning">Sakit</th>
                            <th class="text-center text-danger">Alpa</th>
                            <th class="text-center">Total Pertemuan</th>
                            <th class="text-center" style="width:140px;">% Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekapData as $index => $row)
                            @php
                                $pct = $row['total'] > 0 ? round(($row['hadir'] / $row['total']) * 100, 1) : 0;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="text-muted small">{{ $row['siswa']->nis ?? '-' }}</span></td>
                                <td><strong>{{ $row['siswa']->nama }}</strong></td>
                                <td class="text-center fw-bold text-success">{{ $row['hadir'] }}</td>
                                <td class="text-center fw-bold text-info">{{ $row['izin'] }}</td>
                                <td class="text-center fw-bold text-warning">{{ $row['sakit'] }}</td>
                                <td class="text-center fw-bold text-danger">{{ $row['alpha'] }}</td>
                                <td class="text-center fw-bold">{{ $row['total'] }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 6px;">
                                            <div class="progress-bar {{ $pct >= 80 ? 'bg-success' : ($pct >= 60 ? 'bg-warning' : 'bg-danger') }}" style="width: {{ $pct }}%;"></div>
                                        </div>
                                        <span class="small fw-bold">{{ $pct }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Belum ada catatan absensi untuk jadwal ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
    <div class="content-card p-5 text-center text-muted">
        <i class="fas fa-hand-pointer fs-1 text-primary opacity-50 mb-3 d-block"></i>
        <h5>Pilih Jadwal Mengajar</h5>
        <p class="small mb-0">Silakan pilih salah satu jadwal mengajar di atas untuk memuat statistik rekapitulasi kehadiran siswa.</p>
    </div>
@endif

<style>
@media print {
    .sidebar, .topbar, .d-print-none { display: none !important; }
    .main-content { margin-left: 0 !important; }
    .content-card { border: none !important; box-shadow: none !important; }
}
</style>
@endsection
