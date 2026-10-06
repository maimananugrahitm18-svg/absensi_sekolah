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

<div class="nav-section-title mt-2">Absensi Siswa</div>
<a href="{{ route('admin.rekap.index') }}" class="nav-link {{ request()->routeIs('admin.rekap*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fas fa-chart-bar"></i></span> Rekap Absensi Siswa
</a>
<a href="{{ route('admin.pengajuan-izin.index') }}" class="nav-link {{ request()->routeIs('admin.pengajuan-izin*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fas fa-eye"></i></span> Pantau Izin Siswa
</a>

<div class="nav-section-title mt-2">Aktivitas Guru</div>
<a href="{{ route('admin.presensi-guru.index') }}" class="nav-link {{ request()->routeIs('admin.presensi-guru*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="fas fa-user-check"></i></span> Presensi Guru
</a>
