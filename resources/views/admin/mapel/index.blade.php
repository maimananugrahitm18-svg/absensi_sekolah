@extends('layouts.app')

@section('title', 'Mata Pelajaran')
@section('page-title', 'Mata Pelajaran')
@section('page-subtitle', 'Kelola daftar kurikulum mata pelajaran')

@section('content')
<div class="content-card">
    <div class="content-card-header">
        <h6 class="content-card-title"><i class="fas fa-book me-2 text-primary"></i>Daftar Mata Pelajaran</h6>
        <a href="{{ route('admin.mapel.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Tambah Mata Pelajaran
        </a>
    </div>
    <div class="content-card-body p-3">
        {{-- Search --}}
        <form method="GET" class="row g-2 mb-3">
            <div class="col-12 col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau kode mata pelajaran..." value="{{ request('search') }}">
            </div>
            <div class="col-12 col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="fas fa-search me-1"></i> Cari</button>
                @if(request()->filled('search'))
                    <a href="{{ route('admin.mapel.index') }}" class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>
                @endif
            </div>
        </form>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Kode</th>
                        <th>Nama Mata Pelajaran</th>
                        <th>Jam/SKS</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th class="text-end" style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mapels as $index => $m)
                        <tr>
                            <td>{{ $mapels->firstItem() + $index }}</td>
                            <td><span class="badge bg-secondary-subtle text-secondary font-monospace">{{ $m->kode }}</span></td>
                            <td><strong>{{ $m->nama }}</strong></td>
                            <td>{{ $m->sks ?? 2 }} JP</td>
                            <td><span class="text-muted small">{{ Str::limit($m->deskripsi, 40) ?? '-' }}</span></td>
                            <td>
                                <span class="badge bg-{{ $m->status == 'aktif' ? 'success' : 'secondary' }}-subtle text-{{ $m->status == 'aktif' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($m->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.mapel.edit', $m->id) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.mapel.destroy', $m->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus mata pelajaran ini?')">
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
                            <td colspan="7" class="text-center text-muted py-4">Belum ada mata pelajaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($mapels->hasPages())
            <div class="mt-3">
                {{ $mapels->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
