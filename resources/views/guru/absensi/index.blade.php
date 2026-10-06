@extends('layouts.app')

@section('title', 'Jadwal Mengajar & Absensi')
@section('page-title', 'Absensi Siswa')
@section('page-subtitle', 'Pilih jadwal pelajaran untuk mengisi absensi kelas')

@section('content')
<div class="content-card">
    <div class="content-card-header d-flex justify-content-between align-items-center">
        <h6 class="content-card-title"><i class="fas fa-clipboard-list me-2 text-primary"></i>Jadwal Mengajar Saya</h6>
        <a href="{{ route('guru.absensi.rekap') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-chart-pie me-1"></i> Lihat Rekap Kelas
        </a>
    </div>
    <div class="content-card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Hari</th>
                        <th>Waktu</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Ruangan</th>
                        <th class="text-end">Aksi Absensi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwals as $j)
                        <tr>
                            <td>
                                <span class="badge bg-indigo-subtle text-indigo px-3 py-2 fw-bold" style="background:#e0e7ff;color:#4338ca;">
                                    {{ $j->hari }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}</span>
                            </td>
                            <td>
                                <strong class="fs-6">{{ $j->mataPelajaran?->nama }}</strong>
                                <div class="text-muted small">{{ $j->mataPelajaran?->kode }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border fs-6 px-3 py-1">
                                    {{ $j->kelas?->tingkat }} {{ $j->kelas?->nama }}
                                </span>
                            </td>
                            <td>{{ $j->ruangan ?? '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('guru.absensi.create', $j->id) }}" class="btn btn-primary btn-sm px-3">
                                    <i class="fas fa-edit me-1"></i> Input / Edit Absensi
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                Anda belum memiliki jadwal mengajar aktif yang terdaftar oleh admin.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
