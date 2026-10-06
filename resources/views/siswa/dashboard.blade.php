@extends('layouts.app')

@section('title', 'Dashboard Siswa')
@section('page-title', 'Dashboard Siswa')
@section('page-subtitle', 'Selamat datang, ' . ($siswa ? $siswa->nama : auth()->user()->name))

@section('content')
{{-- Kartu Ringkasan & Profil Siswa --}}
<div class="row g-4 mb-4">
    <div class="col-12 col-md-3 fade-in-up">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;">
                <i class="fas fa-check-circle" style="color:#10b981;"></i>
            </div>
            <div class="stat-value text-success">{{ $rekap['hadir'] ?? 0 }}</div>
            <div class="stat-label">Total Hadir</div>
        </div>
    </div>
    <div class="col-12 col-md-3 fade-in-up fade-in-up-delay-1">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff;">
                <i class="fas fa-envelope-open-text" style="color:#3b82f6;"></i>
            </div>
            <div class="stat-value text-info">{{ $rekap['izin'] ?? 0 }}</div>
            <div class="stat-label">Izin</div>
        </div>
    </div>
    <div class="col-12 col-md-3 fade-in-up fade-in-up-delay-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fffbeb;">
                <i class="fas fa-procedures" style="color:#f59e0b;"></i>
            </div>
            <div class="stat-value text-warning">{{ $rekap['sakit'] ?? 0 }}</div>
            <div class="stat-label">Sakit</div>
        </div>
    </div>
    <div class="col-12 col-md-3 fade-in-up fade-in-up-delay-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef2f2;">
                <i class="fas fa-times-circle" style="color:#ef4444;"></i>
            </div>
            <div class="stat-value text-danger">{{ $rekap['alpha'] ?? 0 }}</div>
            <div class="stat-label">Alpa</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        {{-- Persentase Kehadiran --}}
        <div class="content-card p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold mb-1"><i class="fas fa-chart-pie me-2 text-primary"></i>Persentase Kehadiran Kamu</h6>
                    <div class="text-muted small">Tahun Ajaran: {{ $tahunAktif ? $tahunAktif->nama . ' (' . ucfirst($tahunAktif->semester) . ')' : '-' }}</div>
                </div>
                <div class="display-6 fw-bold text-primary">{{ $persentase }}%</div>
            </div>
            <div class="progress" style="height: 14px; border-radius: 8px;">
                <div class="progress-bar {{ $persentase >= 80 ? 'bg-success' : ($persentase >= 60 ? 'bg-warning' : 'bg-danger') }}" role="progressbar" style="width: {{ $persentase }}%;"></div>
            </div>
            <div class="mt-2 text-muted small">
                @if($persentase >= 85)
                    <i class="fas fa-star text-warning me-1"></i> Kehadiran sangat baik! Pertahankan kedisiplinanmu.
                @elseif($persentase >= 75)
                    <i class="fas fa-info-circle text-info me-1"></i> Kehadiran mencukupi standar minimum kehadiran sekolah.
                @else
                    <i class="fas fa-exclamation-triangle text-danger me-1"></i> Perhatian: Tingkat kehadiranmu di bawah batas wajar (75%).
                @endif
            </div>
        </div>

        {{-- Riwayat Absensi Terakhir --}}
        <div class="content-card">
            <div class="content-card-header d-flex justify-content-between align-items-center">
                <h6 class="content-card-title"><i class="fas fa-history me-2 text-primary"></i>Riwayat Kehadiran Terakhir</h6>
                <a href="{{ route('siswa.riwayat') }}" class="btn btn-outline-primary btn-sm">Lihat Semua Riwayat</a>
            </div>
            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Mata Pelajaran</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatTerbaru as $abs)
                            @php
                                $badgeColor = match($abs->status) {
                                    'hadir' => 'success',
                                    'izin'  => 'info',
                                    'sakit' => 'warning',
                                    default => 'danger'
                                };
                            @endphp
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($abs->tanggal)->locale('id')->isoFormat('dddd, D MMM Y') }}</td>
                                <td>
                                    <strong>{{ $abs->jadwal?->mataPelajaran?->nama ?? '-' }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $badgeColor }}-subtle text-{{ $badgeColor }} px-3 py-1 text-uppercase">
                                        {{ $abs->status }}
                                    </span>
                                </td>
                                <td>{{ $abs->keterangan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Belum ada riwayat absensi terbaru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Profil & Action Cepat --}}
    <div class="col-lg-4">
        <div class="content-card p-4 text-center mb-4">
            <div class="avatar-lg mx-auto mb-3" style="width:75px;height:75px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:30px;font-weight:700;">
                {{ strtoupper(substr($siswa ? $siswa->nama : auth()->user()->name, 0, 1)) }}
            </div>
            <h6 class="fw-bold mb-1">{{ $siswa ? $siswa->nama : auth()->user()->name }}</h6>
            <div class="text-muted small mb-2">NIS: {{ $siswa?->nis ?? '-' }} / NISN: {{ $siswa?->nisn ?? '-' }}</div>
            <span class="badge bg-primary-subtle text-primary px-3 py-1">
                {{ $siswa?->kelas ? $siswa->kelas->tingkat . ' ' . $siswa->kelas->nama : 'Kelas Belum Ditentukan' }}
            </span>

            <div class="p-3 bg-light rounded-3 text-start small mt-4">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Wali Kelas:</span>
                    <span class="fw-semibold">{{ $siswa?->kelas?->wali_kelas ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Jurusan:</span>
                    <span class="fw-semibold">{{ $siswa?->kelas?->jurusan ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Status:</span>
                    <span class="badge bg-success-subtle text-success">{{ ucfirst($siswa?->status ?? 'Aktif') }}</span>
                </div>
            </div>

            <div class="d-grid mt-4">
                <a href="{{ route('siswa.pengajuan-izin.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-1"></i> Ajukan Izin / Sakit
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
