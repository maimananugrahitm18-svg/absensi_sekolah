@extends('layouts.app')

@section('title', 'Tambah Kelas')
@section('page-title', 'Tambah Kelas')
@section('page-subtitle', 'Tambahkan ruang kelas baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="content-card">
            <div class="content-card-header">
                <h6 class="content-card-title"><i class="fas fa-plus-circle me-2 text-primary"></i>Form Tambah Kelas</h6>
                <a href="{{ route('admin.kelas.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="p-4">
                <form action="{{ route('admin.kelas.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tingkat <span class="text-danger">*</span></label>
                            <select name="tingkat" class="form-select @error('tingkat') is-invalid @enderror" required>
                                <option value="">-- Pilih Tingkat --</option>
                                <option value="X" {{ old('tingkat') == 'X' ? 'selected' : '' }}>Kelas X (Sepuluh)</option>
                                <option value="XI" {{ old('tingkat') == 'XI' ? 'selected' : '' }}>Kelas XI (Sebelas)</option>
                                <option value="XII" {{ old('tingkat') == 'XII' ? 'selected' : '' }}>Kelas XII (Dua Belas)</option>
                            </select>
                            @error('tingkat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama / Rombel <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required placeholder="Contoh: IPA 1 atau MIPA A">
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jurusan</label>
                            <input type="text" name="jurusan" class="form-control" value="{{ old('jurusan') }}" placeholder="Contoh: MIPA, IPS, dll">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Wali Kelas</label>
                            <select name="wali_kelas" class="form-select">
                                <option value="">-- Pilih Wali Kelas --</option>
                                @foreach($gurus as $guru)
                                    <option value="{{ $guru->nama }}" {{ old('wali_kelas') == $guru->nama ? 'selected' : '' }}>
                                        {{ $guru->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kapasitas Maksimal</label>
                            <input type="number" name="kapasitas" class="form-control" value="{{ old('kapasitas', 36) }}" min="1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tahun Ajaran</label>
                            <select name="tahun_ajaran_id" class="form-select">
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                @foreach($tahunAjarans as $ta)
                                    <option value="{{ $ta->id }}" {{ old('tahun_ajaran_id') == $ta->id || ($ta->is_aktif && !old('tahun_ajaran_id')) ? 'selected' : '' }}>
                                        {{ $ta->nama }} ({{ ucfirst($ta->semester) }}) {{ $ta->is_aktif ? '★ Aktif' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top mt-4 pt-3">
                        <a href="{{ route('admin.kelas.index') }}" class="btn btn-light px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> Simpan Kelas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
