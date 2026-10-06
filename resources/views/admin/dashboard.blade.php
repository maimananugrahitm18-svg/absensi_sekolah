@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang, ' . auth()->user()->name)

@section('sidebar-menu')
    <div class="nav-section-title">Utama</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <span class="nav-icon"><i class="fas fa-home"></i></span> Dashboard
    </a>

    <div class="nav-section-title mt-2">Data Master</div>
    <a href="{{ route('admin.siswa.index') }}" class="nav-link {{ request()->routeIs('admin.siswa*') ? 'active' : '' }}">
        <span class="nav-icon"><i class="fas fa-user-graduate"></i></span> Data Siswa
    </a>
    <a href="{{ route('admin.guru.index') }}" class="nav-link {{ request()->routeIs('admin.guru*') ? 'active' : '' }}">
        <span class="nav-icon"><i class="fas fa-chalkboard-teacher"></i></span> Data Guru
    </a>
    <a href="{{ route('admin.kelas.index') }}" class="nav-link {{ request()->routeIs('admin.kelas*') ? 'active' : '' }}">
        <span class="nav-icon"><i class="fas fa-door-open"></i></span> Data Kelas
    </a>
    <a href="{{ route('admin.mapel.index') }}" class="nav-link {{ request()->routeIs('admin.mapel*') ? 'active' : '' }}">
        <span class="nav-icon"><i class="fas fa-book"></i></span> Mata Pelajaran
    </a>

    <div class="nav-section-title mt-2">Akademik</div>
    <a href="{{ route('admin.tahun-ajaran.index') }}" class="nav-link {{ request()->routeIs('admin.tahun-ajaran*') ? 'active' : '' }}">
        <span class="nav-icon"><i class="fas fa-calendar-alt"></i></span> Tahun Ajaran
    </a>
    <a href="{{ route('admin.jadwal.index') }}" class="nav-link {{ request()->routeIs('admin.jadwal*') ? 'active' : '' }}">
        <span class="nav-icon"><i class="fas fa-clock"></i></span> Jadwal Pelajaran
    </a>

    <div class="nav-section-title mt-2">Absensi</div>
    <a href="{{ route('admin.rekap.index') }}" class="nav-link {{ request()->routeIs('admin.rekap*') ? 'active' : '' }}">
        <span class="nav-icon"><i class="fas fa-chart-bar"></i></span> Rekap Absensi
    </a>
    <a href="{{ route('admin.pengajuan-izin.index') }}" class="nav-link {{ request()->routeIs('admin.pengajuan-izin*') ? 'active' : '' }}">
        <span class="nav-icon"><i class="fas fa-file-alt"></i></span> Pengajuan Izin
    </a>
@endsection

@section('content')
{{-- Bar Aksi Reset Data Demo --}}
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4 p-3 rounded-3 border bg-light shadow-sm">
    <div class="d-flex align-items-center gap-2">
        <span class="badge bg-primary">Demo Showcase</span>
        <span class="text-muted small">Data dapat dicoba bebas oleh penguji. Ingin membersihkan seluruh data uji coba?</span>
    </div>
    <form action="{{ route('admin.demo.reset') }}" method="POST" onsubmit="return confirm('PERHATIAN: Apakah Anda yakin ingin mereset seluruh database kembali ke kondisi awal demo seeder yang bersih?')">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-danger fw-semibold px-3 shadow-sm" title="Kembalikan semua data ke sampel awal">
            <i class="fas fa-redo-alt me-1"></i> Reset Database Demo ke Awal
        </button>
    </form>
</div>

<div class="row g-4 mb-4">
    {{-- Stats --}}
    <div class="col-12 col-sm-6 col-xl-3 fade-in-up">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eef2ff;">
                <i class="fas fa-user-graduate" style="color:#6366f1;"></i>
            </div>
            <div class="stat-value">{{ $stats['total_siswa'] }}</div>
            <div class="stat-label">Total Siswa Aktif</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3 fade-in-up fade-in-up-delay-1">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;">
                <i class="fas fa-chalkboard-teacher" style="color:#10b981;"></i>
            </div>
            <div class="stat-value">{{ $stats['total_guru'] }}</div>
            <div class="stat-label">Total Guru Aktif</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3 fade-in-up fade-in-up-delay-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fffbeb;">
                <i class="fas fa-door-open" style="color:#f59e0b;"></i>
            </div>
            <div class="stat-value">{{ $stats['total_kelas'] }}</div>
            <div class="stat-label">Total Kelas</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3 fade-in-up fade-in-up-delay-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef2f2;">
                <i class="fas fa-file-alt" style="color:#ef4444;"></i>
            </div>
            <div class="stat-value">{{ $pengajuanMenunggu }}</div>
            <div class="stat-label">Pengajuan Menunggu</div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Absensi Hari Ini --}}
    <div class="col-12 col-lg-5 fade-in-up fade-in-up-delay-2">
        <div class="content-card h-100">
            <div class="content-card-header">
                <h6 class="content-card-title"><i class="fas fa-clipboard-check me-2 text-primary"></i>Absensi Hari Ini</h6>
                <small class="text-muted">{{ now()->locale('id')->isoFormat('D MMMM Y') }}</small>
            </div>
            <div class="content-card-body p-4">
                @php
                    $hadirCount = $absensiHariIni['hadir']  ?? 0;
                    $izinCount  = $absensiHariIni['izin']   ?? 0;
                    $sakitCount = $absensiHariIni['sakit']  ?? 0;
                    $alphaCount = $absensiHariIni['alpha']  ?? 0;
                    $totalHariIni = $hadirCount + $izinCount + $sakitCount + $alphaCount;
                @endphp

                @if($totalHariIni === 0)
                    <div class="text-center py-4">
                        <i class="fas fa-clipboard fa-3x text-muted mb-3 d-block"></i>
                        <p class="text-muted mb-0">Belum ada data absensi hari ini</p>
                    </div>
                @else
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 rounded-3" style="background:#dcfce7;">
                                <div style="font-size:22px;font-weight:800;color:#15803d;">{{ $hadirCount }}</div>
                                <div style="font-size:12px;color:#15803d;font-weight:600;">Hadir</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-3" style="background:#dbeafe;">
                                <div style="font-size:22px;font-weight:800;color:#1d4ed8;">{{ $izinCount }}</div>
                                <div style="font-size:12px;color:#1d4ed8;font-weight:600;">Izin</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-3" style="background:#fef9c3;">
                                <div style="font-size:22px;font-weight:800;color:#a16207;">{{ $sakitCount }}</div>
                                <div style="font-size:12px;color:#a16207;font-weight:600;">Sakit</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-3" style="background:#fee2e2;">
                                <div style="font-size:22px;font-weight:800;color:#b91c1c;">{{ $alphaCount }}</div>
                                <div style="font-size:12px;color:#b91c1c;font-weight:600;">Alpha</div>
                            </div>
                        </div>
                    </div>
                    @if($totalHariIni > 0)
                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Persentase Hadir</small>
                            <small class="fw-bold text-success">{{ round(($hadirCount/$totalHariIni)*100,1) }}%</small>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width:{{ ($hadirCount/$totalHariIni)*100 }}%"></div>
                        </div>
                    </div>
                    @endif
                @endif

                @if($pengajuanMenunggu > 0)
                    <div class="alert alert-warning mt-3 mb-0 py-2 px-3" style="border-radius:10px;">
                        <i class="fas fa-bell me-1"></i>
                        <strong>{{ $pengajuanMenunggu }}</strong> pengajuan izin menunggu persetujuan.
                        <a href="{{ route('admin.pengajuan-izin.index') }}" class="alert-link ms-1">Lihat →</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Absensi Terbaru --}}
    <div class="col-12 col-lg-7 fade-in-up fade-in-up-delay-3">
        <div class="content-card h-100">
            <div class="content-card-header">
                <h6 class="content-card-title"><i class="fas fa-history me-2 text-primary"></i>Absensi Terbaru</h6>
                <a href="{{ route('admin.rekap.index') }}" class="btn btn-sm btn-primary">Lihat Rekap</a>
            </div>
            <div class="content-card-body">
                @if($recentAbsensi->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                        <p class="text-muted">Belum ada data absensi</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Siswa</th>
                                    <th>Kelas / Mapel</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAbsensi as $abs)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="avatar-sm">{{ strtoupper(substr($abs->siswa->nama ?? 'S', 0, 1)) }}</span>
                                            <span>{{ $abs->siswa->nama ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-size:12px;">{{ $abs->jadwal->kelas->nama ?? '-' }}</div>
                                        <div style="font-size:11px;color:#94a3b8;">{{ $abs->jadwal->mataPelajaran->nama ?? '-' }}</div>
                                    </td>
                                    <td style="font-size:12px;color:#64748b;">{{ $abs->tanggal?->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $abs->status }}">
                                            {{ ucfirst($abs->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
