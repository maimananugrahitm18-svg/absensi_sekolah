@extends('layouts.app')

@section('title', 'Detail Guru - ' . $guru->nama)
@section('page-title', 'Detail Guru')
@section('page-subtitle', 'Informasi profil dan jadwal mengajar guru')

@section('content')
<div class="row g-4">
    {{-- Profil Guru --}}
    <div class="col-lg-4">
        <div class="content-card mb-4 text-center p-4">
            <div class="avatar-lg mx-auto mb-3" style="width:80px;height:80px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:32px;font-weight:700;">
                {{ strtoupper(substr($guru->nama, 0, 1)) }}
            </div>
            <h5 class="fw-bold mb-1">{{ $guru->nama }}</h5>
            <p class="text-muted small mb-2">NIP: {{ $guru->nip ?? '-' }}</p>
            <span class="badge bg-{{ $guru->status == 'aktif' ? 'success' : 'secondary' }}-subtle text-{{ $guru->status == 'aktif' ? 'success' : 'secondary' }} px-3 py-1">
                Status: {{ ucfirst($guru->status) }}
            </span>

            <div class="d-flex justify-content-center gap-2 mt-4">
                <a href="{{ route('admin.guru.edit', $guru->id) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit me-1"></i> Edit Guru
                </a>
                <a href="{{ route('admin.guru.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="content-card p-4">
            <h6 class="fw-bold mb-3"><i class="fas fa-id-card me-2 text-primary"></i>Informasi Guru</h6>
            <table class="table table-sm table-borderless small mb-0">
                <tr><td class="text-muted" style="width:40%;">Email</td><td class="fw-semibold">{{ $guru->user?->email ?? '-' }}</td></tr>
                <tr><td class="text-muted">Gender</td><td class="fw-semibold">{{ $guru->jenis_kelamin == 'L' ? 'Laki-laki' : ($guru->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</td></tr>
                <tr><td class="text-muted">Pendidikan</td><td class="fw-semibold">{{ $guru->pendidikan_terakhir ?? '-' }}</td></tr>
                <tr><td class="text-muted">No. HP</td><td class="fw-semibold">{{ $guru->no_hp ?? '-' }}</td></tr>
                <tr><td class="text-muted">Alamat</td><td class="fw-semibold">{{ $guru->alamat ?? '-' }}</td></tr>
            </table>
        </div>
    </div>

    {{-- Jadwal Mengajar --}}
    <div class="col-lg-8">
        <div class="content-card">
            <div class="content-card-header">
                <h6 class="content-card-title"><i class="fas fa-calendar-check me-2 text-primary"></i>Jadwal Mengajar</h6>
                <span class="badge bg-primary">{{ $guru->jadwals->count() }} Jadwal</span>
            </div>
            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>Ruangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($guru->jadwals as $jadwal)
                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $jadwal->hari }}</span>
                                </td>
                                <td>{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</td>
                                <td><strong>{{ $jadwal->mataPelajaran?->nama }}</strong></td>
                                <td>{{ $jadwal->kelas?->tingkat }} {{ $jadwal->kelas?->nama }}</td>
                                <td>{{ $jadwal->ruangan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Guru ini belum memiliki jadwal mengajar aktif.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
