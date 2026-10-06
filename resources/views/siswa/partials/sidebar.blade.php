<div class="nav-section-title">Utama</div>
<a href="{{ route('siswa.dashboard') }}" class="nav-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fas fa-home"></i></span> Dashboard
</a>

<div class="nav-section-title mt-2">Absensi Saya</div>
<a href="{{ route('siswa.riwayat') }}" class="nav-link {{ request()->routeIs('siswa.riwayat') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fas fa-history"></i></span> Riwayat Kehadiran
</a>
<a href="{{ route('siswa.pengajuan-izin.index') }}" class="nav-link {{ request()->routeIs('siswa.pengajuan-izin*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fas fa-file-medical"></i></span> Pengajuan Izin / Sakit
</a>
