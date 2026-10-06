<div class="nav-section-title">Utama</div>
<a href="{{ route('guru.dashboard') }}" class="nav-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fas fa-home"></i></span> Dashboard
</a>

<div class="nav-section-title mt-2">Aktivitas Guru</div>
<a href="{{ route('guru.presensi.index') }}" class="nav-link {{ request()->routeIs('guru.presensi*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fas fa-user-clock"></i></span> Presensi Masuk & Pulang
</a>

<div class="nav-section-title mt-2">Absensi Siswa</div>
<a href="{{ route('guru.absensi.index') }}" class="nav-link {{ request()->routeIs('guru.absensi.index') || request()->routeIs('guru.absensi.create') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fas fa-clipboard-check"></i></span> Jadwal & Absensi Siswa
</a>
<a href="{{ route('guru.absensi.rekap') }}" class="nav-link {{ request()->routeIs('guru.absensi.rekap') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fas fa-chart-pie"></i></span> Rekap Kehadiran Kelas
</a>
<a href="{{ route('guru.pengajuan-izin.index') }}" class="nav-link {{ request()->routeIs('guru.pengajuan-izin*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fas fa-file-medical"></i></span> Persetujuan Izin Siswa
    @php
        $pendingIzinCount = \App\Models\PengajuanIzin::where('status', 'menunggu')->count();
    @endphp
    @if($pendingIzinCount > 0)
        <span class="badge bg-warning text-dark ms-auto fw-bold" style="font-size:11px;">{{ $pendingIzinCount }}</span>
    @endif
</a>
