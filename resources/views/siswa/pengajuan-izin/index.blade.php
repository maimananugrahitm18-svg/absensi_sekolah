@extends('layouts.app')

@section('title', 'Daftar Pengajuan Izin')
@section('page-title', 'Pengajuan Izin / Sakit')
@section('page-subtitle', 'Kelola dan pantau status surat izin ketidakhadiran')

@section('content')
<div class="content-card">
    <div class="content-card-header d-flex justify-content-between align-items-center">
        <h6 class="content-card-title"><i class="fas fa-file-medical me-2 text-primary"></i>Riwayat Pengajuan Izin</h6>
        <a href="{{ route('siswa.pengajuan-izin.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Ajukan Izin Baru
        </a>
    </div>
    <div class="content-card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Jenis</th>
                        <th>Rentang Tanggal</th>
                        <th>Alasan / Keterangan</th>
                        <th>Status</th>
                        <th>Respon / Catatan Guru</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $index => $p)
                        <tr>
                            <td>{{ $pengajuans->firstItem() + $index }}</td>
                            <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="badge bg-{{ $p->jenis == 'sakit' ? 'warning' : 'info' }}-subtle text-{{ $p->jenis == 'sakit' ? 'warning' : 'info' }} text-uppercase">
                                    {{ $p->jenis }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }}</strong>
                                @if($p->tanggal_mulai != $p->tanggal_selesai)
                                    <div class="text-muted small">s/d {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}</div>
                                @endif
                            </td>
                            <td>{{ $p->keterangan }}</td>
                            <td>
                                @if($p->status == 'menunggu')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Menunggu Guru</span>
                                @elseif($p->status == 'disetujui')
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Disetujui Guru</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Ditolak</span>
                                @endif
                            </td>
                            <td>
                                @if($p->diprosesOleh)
                                    <div class="fw-semibold small text-dark">
                                        <i class="fas fa-chalkboard-teacher text-primary me-1"></i> {{ $p->diprosesOleh->name }}
                                    </div>
                                @endif
                                @if($p->catatan_admin)
                                    <div class="text-danger small mt-1">
                                        <i class="fas fa-comment-dots me-1"></i> {{ $p->catatan_admin }}
                                    </div>
                                @elseif(!$p->diprosesOleh)
                                    <span class="text-muted small">Sedang ditinjau Guru</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                Kamu belum pernah membuat pengajuan izin atau sakit.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pengajuans->hasPages())
            <div class="p-3">
                {{ $pengajuans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
