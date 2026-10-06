@extends('layouts.app')

@section('title', 'Detail Siswa - ' . $siswa->nama)
@section('page-title', 'Detail Siswa')
@section('page-subtitle', 'Informasi profil dan riwayat kehadiran siswa')

@section('content')
<div class="row g-4">
    {{-- Profil Siswa --}}
    <div class="col-lg-4">
        <div class="content-card mb-4 text-center p-4">
            <div class="avatar-lg mx-auto mb-3" style="width:80px;height:80px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:32px;font-weight:700;">
                {{ strtoupper(substr($siswa->nama, 0, 1)) }}
            </div>
            <h5 class="fw-bold mb-1">{{ $siswa->nama }}</h5>
            <p class="text-muted small mb-2">{{ $siswa->kelas ? $siswa->kelas->tingkat . ' ' . $siswa->kelas->nama : 'Belum Ada Kelas' }}</p>
            <span class="badge bg-{{ $siswa->status == 'aktif' ? 'success' : 'secondary' }}-subtle text-{{ $siswa->status == 'aktif' ? 'success' : 'secondary' }} px-3 py-1">
                Status: {{ ucfirst($siswa->status) }}
            </span>

            <div class="d-flex justify-content-center gap-2 mt-4">
                <a href="{{ route('admin.siswa.edit', $siswa->id) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit me-1"></i> Edit Siswa
                </a>
                <a href="{{ route('admin.siswa.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="content-card p-4">
            <h6 class="fw-bold mb-3"><i class="fas fa-id-card me-2 text-primary"></i>Biodata</h6>
            <table class="table table-sm table-borderless small mb-0">
                <tr><td class="text-muted" style="width:40%;">NIS</td><td class="fw-semibold">{{ $siswa->nis ?? '-' }}</td></tr>
                <tr><td class="text-muted">NISN</td><td class="fw-semibold">{{ $siswa->nisn ?? '-' }}</td></tr>
                <tr><td class="text-muted">Email</td><td class="fw-semibold">{{ $siswa->user?->email ?? '-' }}</td></tr>
                <tr><td class="text-muted">Gender</td><td class="fw-semibold">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : ($siswa->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</td></tr>
                <tr><td class="text-muted">TTL</td><td class="fw-semibold">{{ $siswa->tempat_lahir ?? '-' }}, {{ $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('d/m/Y') : '-' }}</td></tr>
                <tr><td class="text-muted">No. HP</td><td class="fw-semibold">{{ $siswa->no_hp ?? '-' }}</td></tr>
                <tr><td class="text-muted">HP Ortu</td><td class="fw-semibold">{{ $siswa->no_hp_ortu ?? '-' }}</td></tr>
                <tr><td class="text-muted">Alamat</td><td class="fw-semibold">{{ $siswa->alamat ?? '-' }}</td></tr>
            </table>
        </div>
    </div>

    {{-- Statistik & Riwayat Absensi --}}
    <div class="col-lg-8">
        {{-- Ringkasan Kehadiran --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-md-3">
                <div class="stat-card p-3 text-center">
                    <div class="stat-value text-success fs-3">{{ $rekap['hadir'] }}</div>
                    <div class="stat-label">Hadir</div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="stat-card p-3 text-center">
                    <div class="stat-value text-info fs-3">{{ $rekap['izin'] }}</div>
                    <div class="stat-label">Izin</div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="stat-card p-3 text-center">
                    <div class="stat-value text-warning fs-3">{{ $rekap['sakit'] }}</div>
                    <div class="stat-label">Sakit</div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="stat-card p-3 text-center">
                    <div class="stat-value text-danger fs-3">{{ $rekap['alpha'] }}</div>
                    <div class="stat-label">Alpa</div>
                </div>
            </div>
        </div>

        {{-- Progress Bar --}}
        <div class="content-card p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold">Tingkat Kehadiran:</span>
                <span class="fw-bold fs-5 text-primary">{{ $rekap['persentase'] }}%</span>
            </div>
            <div class="progress" style="height: 12px; border-radius: 6px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $rekap['persentase'] }}%;"></div>
            </div>
        </div>

        {{-- Riwayat Absensi Terakhir --}}
        <div class="content-card">
            <div class="content-card-header">
                <h6 class="content-card-title"><i class="fas fa-history me-2 text-primary"></i>Riwayat Absensi Terakhir</h6>
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
                        @forelse($siswa->absensis->take(15) as $abs)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($abs->tanggal)->format('d/m/Y') }}</td>
                                <td>
                                    <strong>{{ $abs->jadwal?->mataPelajaran?->nama ?? '-' }}</strong>
                                    <div class="text-muted small">{{ $abs->jadwal?->guru?->nama }}</div>
                                </td>
                                <td>
                                    @php
                                        $badgeColor = match($abs->status) {
                                            'hadir' => 'success',
                                            'izin'  => 'info',
                                            'sakit' => 'warning',
                                            default => 'danger'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badgeColor }}-subtle text-{{ $badgeColor }} px-2 py-1">
                                        {{ ucfirst($abs->status) }}
                                    </span>
                                </td>
                                <td>{{ $abs->keterangan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Belum ada riwayat absensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
