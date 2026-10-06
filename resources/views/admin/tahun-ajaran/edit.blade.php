@extends('layouts.app')

@section('title', 'Edit Tahun Ajaran')
@section('page-title', 'Edit Tahun Ajaran')
@section('page-subtitle', 'Perbarui data tahun ajaran ' . $tahunAjaran->nama)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="content-card">
            <div class="content-card-header">
                <h6 class="content-card-title"><i class="fas fa-edit me-2 text-primary"></i>Edit Tahun Ajaran</h6>
                <a href="{{ route('admin.tahun-ajaran.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="p-4">
                <form action="{{ route('admin.tahun-ajaran.update', $tahunAjaran->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $tahunAjaran->nama) }}" required>
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Semester <span class="text-danger">*</span></label>
                            <select name="semester" class="form-select @error('semester') is-invalid @enderror" required>
                                <option value="ganjil" {{ old('semester', $tahunAjaran->semester) == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                                <option value="genap" {{ old('semester', $tahunAjaran->semester) == 'genap' ? 'selected' : '' }}>Genap</option>
                            </select>
                            @error('semester') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" value="{{ old('tanggal_mulai', $tahunAjaran->tanggal_mulai?->format('Y-m-d')) }}" required>
                            @error('tanggal_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" value="{{ old('tanggal_selesai', $tahunAjaran->tanggal_selesai?->format('Y-m-d')) }}" required>
                            @error('tanggal_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 mt-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_aktif" value="1" id="isAktifSwitch" {{ old('is_aktif', $tahunAjaran->is_aktif) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="isAktifSwitch">
                                    Set sebagai Tahun Ajaran Aktif Saat Ini
                                </label>
                                <div class="text-muted small">Jika dicentang, tahun ajaran aktif lainnya akan otomatis dinonaktifkan.</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top mt-4 pt-3">
                        <a href="{{ route('admin.tahun-ajaran.index') }}" class="btn btn-light px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> Update Tahun Ajaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
