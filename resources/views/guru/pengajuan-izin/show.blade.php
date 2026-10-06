@extends('layouts.app')

@section('title', 'Verifikasi Pengajuan Izin Siswa')
@section('page-title', 'Detail Permohonan Izin Siswa')
@section('page-subtitle', 'Tinjau rincian alasan dan berikan keputusan persetujuan (ACC)')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="content-card mb-4 shadow-sm border-0">
            <div class="content-card-header d-flex justify-content-between align-items-center">
                <h6 class="content-card-title mb-0">
                    <i class="fas fa-file-medical text-primary me-2"></i>Rincian Pengajuan Izin
                </h6>
                <a href="{{ route('guru.pengajuan-izin.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                </a>
            </div>

            <div class="p-4">
                {{-- Status Box --}}
                <div class="d-flex align-items-center justify-content-between p-3 rounded-3 mb-4" style="background:#f8fafc; border:1px solid #e2e8f0;">
                    <div>
                        <div class="text-muted small">Status Permohonan Saat Ini:</div>
                        <div class="mt-1">
                            @if($pengajuanIzin->status == 'menunggu')
                                <span class="badge bg-warning text-dark fs-6 px-3 py-1">
                                    <i class="fas fa-clock me-1"></i> Menunggu Keputusan Guru
                                </span>
                            @elseif($pengajuanIzin->status == 'disetujui')
                                <span class="badge bg-success fs-6 px-3 py-1">
                                    <i class="fas fa-check-circle me-1"></i> Telah Disetujui (ACC)
                                </span>
                            @else
                                <span class="badge bg-danger fs-6 px-3 py-1">
                                    <i class="fas fa-times-circle me-1"></i> Telah Ditolak
                                </span>
                            @endif
                        </div>
                    </div>
                    @if($pengajuanIzin->diprosesOleh)
                        <div class="text-end">
                            <div class="text-muted small">Diproses oleh:</div>
                            <div class="fw-bold text-dark">{{ $pengajuanIzin->diprosesOleh->name }}</div>
                            <div class="text-muted small">{{ $pengajuanIzin->diproses_pada?->format('d/m/Y H:i') }} WIB</div>
                        </div>
                    @endif
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="text-muted small d-block">Nama Siswa</label>
                        <div class="fw-bold fs-6 text-dark">{{ $pengajuanIzin->siswa?->nama ?? '-' }}</div>
                        <div class="text-muted small">NIS: {{ $pengajuanIzin->siswa?->nis ?? '-' }} &bull; {{ $pengajuanIzin->siswa?->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small d-block">Kelas</label>
                        <div class="fw-bold fs-6 text-primary">
                            {{ $pengajuanIzin->siswa?->kelas ? $pengajuanIzin->siswa->kelas->tingkat . ' ' . $pengajuanIzin->siswa->kelas->nama : '-' }}
                        </div>
                        <div class="text-muted small">Jurusan: {{ $pengajuanIzin->siswa?->kelas?->jurusan ?? '-' }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small d-block">Jenis Ketidakhadiran</label>
                        <div>
                            <span class="badge bg-{{ $pengajuanIzin->jenis == 'sakit' ? 'warning' : 'info' }}-subtle text-{{ $pengajuanIzin->jenis == 'sakit' ? 'warning' : 'info' }} fs-6 px-3 py-1 text-uppercase fw-bold">
                                <i class="fas fa-{{ $pengajuanIzin->jenis == 'sakit' ? 'head-side-cough' : 'calendar-day' }} me-1"></i>
                                {{ $pengajuanIzin->jenis }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small d-block">Rentang Waktu / Tanggal</label>
                        <div class="fw-bold text-dark">
                            {{ $pengajuanIzin->tanggal_mulai ? \Carbon\Carbon::parse($pengajuanIzin->tanggal_mulai)->format('d F Y') : '-' }}
                            @if($pengajuanIzin->tanggal_mulai != $pengajuanIzin->tanggal_selesai)
                                <br><span class="text-muted fw-normal">sampai</span> {{ \Carbon\Carbon::parse($pengajuanIzin->tanggal_selesai)->format('d F Y') }}
                            @endif
                        </div>
                        <div class="badge bg-secondary-subtle text-secondary mt-1">
                            Durasi: {{ $pengajuanIzin->jumlah_hari }} Hari
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="text-muted small d-block">Alasan / Penjelasan Siswa</label>
                        <div class="p-3 bg-light rounded-3 mt-1 border text-dark" style="font-size:14px; line-height:1.6;">
                            {{ $pengajuanIzin->keterangan }}
                        </div>
                    </div>

                    @if($pengajuanIzin->catatan_admin)
                        <div class="col-12">
                            <label class="text-danger small fw-bold d-block">Catatan Penolakan</label>
                            <div class="p-3 bg-danger-subtle text-danger rounded-3 mt-1 border border-danger-subtle">
                                <i class="fas fa-info-circle me-1"></i> {{ $pengajuanIzin->catatan_admin }}
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Action buttons for GURU --}}
                @if($pengajuanIzin->status == 'menunggu')
                    <div class="border-top pt-4 mt-2 d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="text-muted small">
                            <i class="fas fa-shield-alt text-primary me-1"></i>
                            Sebagai Guru, Anda memiliki hak penuh untuk menyetujui atau menolak permohonan ini.
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-danger px-3" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="fas fa-times me-1"></i> Tolak Permohonan
                            </button>
                            <form action="{{ route('guru.pengajuan-izin.approve', $pengajuanIzin->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MENYETUJUI (ACC) pengajuan izin siswa ini?')">
                                @csrf
                                <button type="submit" class="btn btn-success px-4 fw-semibold shadow-sm">
                                    <i class="fas fa-check-circle me-1"></i> Setujui (ACC) Izin
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="border-top pt-3 d-flex justify-content-end">
                        <a href="{{ route('guru.pengajuan-izin.index') }}" class="btn btn-secondary px-4">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Izin
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('guru.pengajuan-izin.reject', $pengajuanIzin->id) }}" method="POST">
            @csrf
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fs-6 fw-bold">
                        <i class="fas fa-times-circle me-1"></i> Tolak Pengajuan Izin
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">
                        Tuliskan catatan atau alasan penolakan agar siswa dapat mengetahui kendala atau alasan mengapa surat izinnya tidak disetujui.
                    </p>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="catatan" class="form-control" rows="3" required placeholder="Contoh: Alasan izin kurang jelas, atau tanggal izin bentrok dengan jadwal ujian."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger px-4">Tolak Izin Siswa</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
