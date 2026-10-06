@extends('layouts.app')

@section('title', 'Tahun Ajaran')
@section('page-title', 'Tahun Ajaran')
@section('page-subtitle', 'Kelola periode tahun ajaran & semester')

@section('content')
<div class="content-card">
    <div class="content-card-header">
        <h6 class="content-card-title"><i class="fas fa-calendar-alt me-2 text-primary"></i>Daftar Tahun Ajaran</h6>
        <a href="{{ route('admin.tahun-ajaran.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Tambah Tahun Ajaran
        </a>
    </div>
    <div class="content-card-body p-3">
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Tahun Ajaran</th>
                        <th>Semester</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th class="text-end" style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tahunAjarans as $index => $ta)
                        <tr>
                            <td>{{ $tahunAjarans->firstItem() + $index }}</td>
                            <td><strong class="fs-6">{{ $ta->nama }}</strong></td>
                            <td>
                                <span class="badge bg-info-subtle text-info text-uppercase px-2 py-1">
                                    Semester {{ $ta->semester }}
                                </span>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($ta->tanggal_mulai)->format('d M Y') }} - 
                                {{ \Carbon\Carbon::parse($ta->tanggal_selesai)->format('d M Y') }}
                            </td>
                            <td>
                                @if($ta->is_aktif)
                                    <span class="badge bg-success px-3 py-2"><i class="fas fa-check-circle me-1"></i> Aktif Berjalan</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary px-2 py-1">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.tahun-ajaran.edit', $ta->id) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.tahun-ajaran.destroy', $ta->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus tahun ajaran ini?')">
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
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data tahun ajaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tahunAjarans->hasPages())
            <div class="mt-3">
                {{ $tahunAjarans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
