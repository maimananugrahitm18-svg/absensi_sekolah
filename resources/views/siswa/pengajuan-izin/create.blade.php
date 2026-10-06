@extends('layouts.app')

@section('title', 'Form Pengajuan Izin')
@section('page-title', 'Form Pengajuan Izin / Sakit')
@section('page-subtitle', 'Kirim pemberitahuan permohonan izin atau sakit kepada pihak sekolah')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="content-card">
            <div class="content-card-header">
                <h6 class="content-card-title"><i class="fas fa-file-medical me-2 text-primary"></i>Isi Data Pengajuan</h6>
                <a href="{{ route('siswa.pengajuan-izin.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="p-4">
                <form action="{{ route('siswa.pengajuan-izin.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Jenis Pengajuan <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3">
                                <div class="form-check p-3 border rounded-3 flex-fill">
                                    <input class="form-check-input" type="radio" name="jenis" id="jenis_izin" value="izin" {{ old('jenis', 'izin') == 'izin' ? 'checked' : '' }} required>
                                    <label class="form-check-label fw-bold d-block" for="jenis_izin">
                                        <i class="fas fa-envelope-open-text text-info me-1"></i> Izin
                                        <div class="text-muted fw-normal small mt-1">Keperluan keluarga, lomba, atau hal mendesak lainnya.</div>
                                    </label>
                                </div>
                                <div class="form-check p-3 border rounded-3 flex-fill">
                                    <input class="form-check-input" type="radio" name="jenis" id="jenis_sakit" value="sakit" {{ old('jenis') == 'sakit' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold d-block" for="jenis_sakit">
                                        <i class="fas fa-procedures text-warning me-1"></i> Sakit
                                        <div class="text-muted fw-normal small mt-1">Kondisi kesehatan sedang terganggu / rawat jalan.</div>
                                    </label>
                                </div>
                            </div>
                            @error('jenis') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" value="{{ old('tanggal_mulai', today()->toDateString()) }}" required>
                            @error('tanggal_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" value="{{ old('tanggal_selesai', today()->toDateString()) }}" required>
                            @error('tanggal_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Alasan / Penjelasan Rinci <span class="text-danger">*</span></label>
                            <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="4" required placeholder="Tuliskan secara jelas alasan izin atau kondisi gejala sakit yang dialami (minimal 10 karakter)...">{{ old('keterangan') }}</textarea>
                            @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>


                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top mt-4 pt-3">
                        <a href="{{ route('siswa.pengajuan-izin.index') }}" class="btn btn-light px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-paper-plane me-1"></i> Kirim Permohonan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
