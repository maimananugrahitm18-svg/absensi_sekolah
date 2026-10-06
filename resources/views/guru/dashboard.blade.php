@extends('layouts.app')

@section('title', 'Dashboard Guru')
@section('page-title', 'Dashboard Guru')
@section('page-subtitle', 'Selamat datang, ' . ($guru ? $guru->nama : auth()->user()->name))

@section('content')
@if($izinMenunggu > 0)
    <div class="alert alert-warning border-0 shadow-sm d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4 p-3 rounded-3" style="background:#fffbeb;border-left: 4px solid #f59e0b !important;">
        <div class="d-flex align-items-center gap-3">
            <div style="width:40px;height:40px;background:#fef3c7;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;">
                🔔
            </div>
            <div>
                <strong class="text-dark d-block">Terdapat {{ $izinMenunggu }} Permohonan Izin Siswa Menunggu Keputusan Anda</strong>
                <span class="text-muted small">Siswa membutuhkan persetujuan (ACC) izin/sakit untuk pencatatan absensi sekolah.</span>
            </div>
        </div>
        <a href="{{ route('guru.pengajuan-izin.index') }}" class="btn btn-warning text-dark fw-bold btn-sm px-3 rounded-pill shadow-sm">
            <i class="fas fa-clipboard-check me-1"></i> Tinjau & ACC Izin
        </a>
    </div>
@endif

<div class="row g-4 mb-4">
    <div class="col-12 col-md-4 fade-in-up">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eef2ff;">
                <i class="fas fa-calendar-day" style="color:#6366f1;"></i>
            </div>
            <div class="stat-value">{{ $jadwalsHariIni->count() }}</div>
            <div class="stat-label">Jadwal Mengajar Hari Ini</div>
        </div>
    </div>
    <div class="col-12 col-md-4 fade-in-up fade-in-up-delay-1">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;">
                <i class="fas fa-user-check" style="color:#10b981;"></i>
            </div>
            <div class="stat-value">{{ $totalAbsensiDiisi }}</div>
            <div class="stat-label">Kehadiran Siswa Hari Ini</div>
        </div>
    </div>
    <div class="col-12 col-md-4 fade-in-up fade-in-up-delay-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fdf4ff;">
                <i class="fas fa-chalkboard" style="color:#a855f7;"></i>
            </div>
            <div class="stat-value">{{ $totalJadwal }}</div>
            <div class="stat-label">Total Kelas Diampu</div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Jadwal Hari Ini --}}
    <div class="col-lg-8">
        <div class="content-card">
            <div class="content-card-header d-flex justify-content-between align-items-center">
                <h6 class="content-card-title"><i class="fas fa-clock me-2 text-primary"></i>Jadwal Mengajar Hari Ini ({{ now()->locale('id')->isoFormat('dddd') }})</h6>
                <a href="{{ route('guru.absensi.index') }}" class="btn btn-outline-primary btn-sm">Lihat Semua Jadwal</a>
            </div>
            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Jam</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>Ruangan</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwalsHariIni as $j)
                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}
                                    </span>
                                </td>
                                <td>
                                    <strong>{{ $j->mataPelajaran?->nama }}</strong>
                                </td>
                                <td><span class="fw-semibold text-primary">{{ $j->kelas?->tingkat }} {{ $j->kelas?->nama }}</span></td>
                                <td>{{ $j->ruangan ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('guru.absensi.create', $j->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-clipboard-check me-1"></i> Isi Absensi
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="fas fa-mug-hot fs-3 text-muted mb-2 d-block"></i>
                                    Tidak ada jadwal mengajar pada hari ini. Selamat beristirahat!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Profil Singkat Guru --}}
    <div class="col-lg-4">
        <div class="content-card p-4 text-center mb-4">
            <div class="avatar-lg mx-auto mb-3" style="width:70px;height:70px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:28px;font-weight:700;">
                {{ strtoupper(substr($guru ? $guru->nama : auth()->user()->name, 0, 1)) }}
            </div>
            <h6 class="fw-bold mb-1">{{ $guru ? $guru->nama : auth()->user()->name }}</h6>
            <div class="text-muted small mb-3">NIP: {{ $guru?->nip ?? '-' }}</div>
            <div class="p-3 bg-light rounded-3 text-start small">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Pendidikan:</span>
                    <span class="fw-semibold">{{ $guru?->pendidikan_terakhir ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">No. HP:</span>
                    <span class="fw-semibold">{{ $guru?->no_hp ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Tahun Ajaran:</span>
                    <span class="fw-semibold text-primary">{{ $tahunAktif ? $tahunAktif->nama : '-' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
