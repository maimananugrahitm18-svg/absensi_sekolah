@extends('layouts.app')

@section('title', 'Data Guru')
@section('page-title', 'Data Guru')
@section('page-subtitle', 'Kelola data guru pengajar')

@section('content')
<div class="content-card">
    <div class="content-card-header">
        <h6 class="content-card-title"><i class="fas fa-chalkboard-teacher me-2 text-primary"></i>Daftar Guru</h6>
        <a href="{{ route('admin.guru.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Tambah Guru
        </a>
    </div>
    <div class="content-card-body p-3">
        {{-- Filter & Search --}}
        <form method="GET" class="row g-2 mb-3">
            <div class="col-12 col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau NIP..." value="{{ request('search') }}">
            </div>
            <div class="col-12 col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="fas fa-filter me-1"></i> Filter</button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('admin.guru.index') }}" class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>
                @endif
            </div>
        </form>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Guru</th>
                        <th>NIP</th>
                        <th>Pendidikan</th>
                        <th>Kontak</th>
                        <th>Status</th>
                        <th class="text-end" style="width:140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gurus as $index => $g)
                        <tr>
                            <td>{{ $gurus->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-sm">
                                        {{ strtoupper(substr($g->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.guru.show', $g->id) }}" class="fw-semibold text-dark text-decoration-none hover-primary">
                                            {{ $g->nama }}
                                        </a>
                                        <div class="text-muted small">{{ $g->user?->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $g->nip ?? '-' }}</td>
                            <td>{{ $g->pendidikan_terakhir ?? '-' }}</td>
                            <td>{{ $g->no_hp ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $g->status == 'aktif' ? 'success' : 'secondary' }}-subtle text-{{ $g->status == 'aktif' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($g->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.guru.show', $g->id) }}" class="btn btn-sm btn-outline-info me-1" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.guru.edit', $g->id) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.guru.destroy', $g->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data guru ini?')">
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
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data guru yang sesuai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($gurus->hasPages())
            <div class="mt-3">
                {{ $gurus->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
