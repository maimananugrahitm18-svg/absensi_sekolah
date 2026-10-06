@extends('layouts.app')

@section('title', 'Edit Guru')
@section('page-title', 'Edit Guru')
@section('page-subtitle', 'Perbarui data profil guru ' . $guru->nama)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="content-card">
            <div class="content-card-header">
                <h6 class="content-card-title"><i class="fas fa-edit me-2 text-primary"></i>Edit Data Guru</h6>
                <a href="{{ route('admin.guru.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="p-4">
                <form action="{{ route('admin.guru.update', $guru->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <h6 class="fw-bold text-muted mb-3"><i class="fas fa-lock me-2"></i>Akun Pengguna</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $guru->user?->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password Baru <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Biarkan kosong jika tidak diubah">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <h6 class="fw-bold text-muted mb-3"><i class="fas fa-id-card me-2"></i>Data Profil Guru</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $guru->nama) }}" required>
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIP</label>
                            <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip', $guru->nip) }}">
                            @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pendidikan Terakhir</label>
                            <select name="pendidikan_terakhir" class="form-select">
                                <option value="S1" {{ old('pendidikan_terakhir', $guru->pendidikan_terakhir) == 'S1' ? 'selected' : '' }}>S1 (Sarjana)</option>
                                <option value="S2" {{ old('pendidikan_terakhir', $guru->pendidikan_terakhir) == 'S2' ? 'selected' : '' }}>S2 (Magister)</option>
                                <option value="S3" {{ old('pendidikan_terakhir', $guru->pendidikan_terakhir) == 'S3' ? 'selected' : '' }}>S3 (Doktor)</option>
                                <option value="D3" {{ old('pendidikan_terakhir', $guru->pendidikan_terakhir) == 'D3' ? 'selected' : '' }}>D3 (Diploma)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status Kepegawaian</label>
                            <select name="status" class="form-select">
                                <option value="aktif" {{ old('status', $guru->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $guru->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. Handphone / WhatsApp</label>
                            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $guru->no_hp) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat Tinggal</label>
                            <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $guru->alamat) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('admin.guru.index') }}" class="btn btn-light px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> Update Guru</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
