@extends('layouts.app')

@section('title', 'Detail Pengajuan Izin')
@section('page-title', 'Detail Pengajuan Izin')
@section('page-subtitle', 'Verifikasi bukti dan rincian izin siswa')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="content-card mb-4">
            <div class="content-card-header">
                <h6 class="content-card-title"><i class="fas fa-file-alt me-2 text-primary"></i>Informasi Permohonan</h6>
                <a href="{{ route('admin.pengajuan-izin.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="p-4">
                <div class="d-flex align-items-center justify-content-between p-3 rounded-3 mb-4" style="background:#f8fafc;border:1px solid #e2e8f0;">
                    <div>
                        <div class="text-muted small">Status Permohonan</div>
                        <div class="mt-1">
                            @if($pengajuanIzin->status == 'menunggu')
                                <span class="badge bg-warning text-dark fs-6 px-3 py-1"><i class="fas fa-clock me-1"></i> Menunggu Konfirmasi</span>
                            @elseif($pengajuanIzin->status == 'disetujui')
                                <span class="badge bg-success fs-6 px-3 py-1"><i class="fas fa-check-circle me-1"></i> Disetujui</span>
                            @else
                                <span class="badge bg-danger fs-6 px-3 py-1"><i class="fas fa-times-circle me-1"></i> Ditolak</span>
                            @endif
                        </div>
                    </div>
                    @if($pengajuanIzin->diprosesOleh)
                        <div class="text-end">
                            <div class="text-muted small">Diverifikasi oleh:</div>
                            <div class="fw-semibold">{{ $pengajuanIzin->diprosesOleh->name }}</div>
                            <div class="text-muted small">{{ $pengajuanIzin->diproses_pada?->format('d/m/Y H:i') }}</div>
                        </div>
                    @endif
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="text-muted small">Nama Siswa</label>
                        <div class="fw-bold fs-6">{{ $pengajuanIzin->siswa?->nama }}</div>
                        <div class="text-muted small">NIS: {{ $pengajuanIzin->siswa?->nis ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Kelas</label>
                        <div class="fw-bold fs-6">{{ $pengajuanIzin->siswa?->kelas ? $pengajuanIzin->siswa->kelas->tingkat . ' ' . $pengajuanIzin->siswa->kelas->nama : '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Jenis Ketidakhadiran</label>
                        <div>
                            <span class="badge bg-{{ $pengajuanIzin->jenis == 'sakit' ? 'warning' : 'info' }}-subtle text-{{ $pengajuanIzin->jenis == 'sakit' ? 'warning' : 'info' }} fs-6 px-3 py-1 text-uppercase">
                                {{ $pengajuanIzin->jenis }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Periode Tanggal</label>
                        <div class="fw-semibold">
                            {{ $pengajuanIzin->tanggal_mulai ? \Carbon\Carbon::parse($pengajuanIzin->tanggal_mulai)->format('d F Y') : '-' }}
                            @if($pengajuanIzin->tanggal_mulai != $pengajuanIzin->tanggal_selesai)
                                s/d {{ \Carbon\Carbon::parse($pengajuanIzin->tanggal_selesai)->format('d F Y') }}
                            @endif
                        </div>
                        <div class="badge bg-secondary-subtle text-secondary mt-1">Durasi: {{ $pengajuanIzin->jumlah_hari }} Hari</div>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small">Alasan / Keterangan Siswa</label>
                        <div class="p-3 bg-light rounded-3 mt-1 border text-dark">{{ $pengajuanIzin->keterangan }}</div>
                    </div>

                    @if($pengajuanIzin->catatan_admin)
                        <div class="col-12">
                            <label class="text-danger small fw-bold">Catatan Penolakan oleh Guru</label>
                            <div class="p-3 bg-danger-subtle text-danger rounded-3 mt-1 border border-danger-subtle">{{ $pengajuanIzin->catatan_admin }}</div>
                        </div>
                    @endif
                </div>

                {{-- Admin Monitoring Footer --}}
                <div class="border-top pt-4 mt-2 d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div class="text-muted small">
                        <i class="fas fa-info-circle text-info me-1"></i>
                        <strong>Catatan:</strong> Persetujuan (ACC) atau penolakan izin siswa dilakukan oleh <strong>Guru</strong>. Admin hanya memantau data.
                    </div>
                    <a href="{{ route('admin.pengajuan-izin.index') }}" class="btn btn-secondary px-4">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Pemantauan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
