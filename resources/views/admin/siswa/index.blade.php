@extends('layouts.app')

@section('title', 'Data Siswa')
@section('page-title', 'Data Siswa')
@section('page-subtitle', 'Kelola data siswa')

@section('sidebar-menu')
    @include('admin.partials.sidebar')
@endsection

@section('content')
<div class="content-card">
    <div class="content-card-header">
        <h6 class="content-card-title"><i class="fas fa-user-graduate me-2 text-primary"></i>Daftar Siswa</h6>
        <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Tambah Siswa
        </a>
    </div>
    <div class="content-card-body p-3">
        {{-- Filter --}}
        <form method="GET" class="row g-2 mb-3">
            <div class="col-12 col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Cari nama, NIS, NISN..." value="{{ request('search') }}">
            </div>
            <div class="col-12 col-md-3">
                <select name="kelas_id" class="form-select">
                    <option value="">Semua Kelas</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->tingkat }} {{ $k->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aktif"   {{ request('status') == 'aktif'   ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="lulus"   {{ request('status') == 'lulus'   ? 'selected' : '' }}>Lulus</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">Cari</button>
                <a href="{{ route('admin.siswa.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $i => $siswa)
                    <tr>
                        <td>{{ $siswas->firstItem() + $i }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar-sm">{{ strtoupper(substr($siswa->nama, 0, 1)) }}</span>
                                <div>
                                    <div style="font-weight:600;">{{ $siswa->nama }}</div>
                                    <div style="font-size:11px;color:#94a3b8;">{{ $siswa->user->email ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $siswa->nis ?? '-' }}</td>
                        <td>{{ $siswa->kelas ? $siswa->kelas->tingkat . ' ' . $siswa->kelas->nama : '-' }}</td>
                        <td>
                            <span class="badge" style="background:{{ $siswa->status=='aktif' ? '#dcfce7' : '#fee2e2' }};color:{{ $siswa->status=='aktif' ? '#15803d' : '#b91c1c' }};">
                                {{ ucfirst($siswa->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.siswa.show', $siswa) }}" class="btn btn-sm btn-outline-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.siswa.edit', $siswa) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.siswa.destroy', $siswa) }}"
                                      onsubmit="return confirm('Hapus siswa {{ $siswa->nama }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                            Tidak ada data siswa ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3 px-2">
            <small class="text-muted">Menampilkan {{ $siswas->firstItem() ?? 0 }}–{{ $siswas->lastItem() ?? 0 }} dari {{ $siswas->total() }} data</small>
            {{ $siswas->links() }}
        </div>
    </div>
</div>
@endsection
