@extends('layouts.app')

@section('title', 'Data Kelas')
@section('page-title', 'Data Kelas')
@section('page-subtitle', 'Kelola daftar kelas dan wali kelas')

@section('content')
<div class="content-card">
    <div class="content-card-header">
        <h6 class="content-card-title"><i class="fas fa-door-open me-2 text-primary"></i>Daftar Kelas</h6>
        <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Tambah Kelas
        </a>
    </div>
    <div class="content-card-body p-3">
        {{-- Search --}}
        <form method="GET" class="row g-2 mb-3">
            <div class="col-12 col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Cari nama kelas..." value="{{ request('search') }}">
            </div>
            <div class="col-12 col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="fas fa-search me-1"></i> Cari</button>
                @if(request()->filled('search'))
                    <a href="{{ route('admin.kelas.index') }}" class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>
                @endif
            </div>
        </form>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Nama Kelas</th>
                        <th>Tingkat</th>
                        <th>Jurusan</th>
                        <th>Wali Kelas</th>
                        <th>Jumlah Siswa</th>
                        <th>Tahun Ajaran</th>
                        <th class="text-end" style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelas as $index => $k)
                        <tr>
                            <td>{{ $kelas->firstItem() + $index }}</td>
                            <td>
                                <span class="fw-bold text-dark">{{ $k->tingkat }} {{ $k->nama }}</span>
                            </td>
                            <td>{{ $k->tingkat }}</td>
                            <td>{{ $k->jurusan ?? '-' }}</td>
                            <td>{{ $k->wali_kelas ?? '-' }}</td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary">
                                    <i class="fas fa-users me-1"></i> {{ $k->siswas->count() }} siswa
                                </span>
                            </td>
                            <td>{{ $k->tahunAjaran?->nama ?? '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.kelas.edit', $k->id) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada data kelas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kelas->hasPages())
            <div class="mt-3">
                {{ $kelas->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
