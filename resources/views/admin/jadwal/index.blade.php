@extends('layouts.app')

@section('title', 'Jadwal Pelajaran')
@section('page-title', 'Jadwal Pelajaran')
@section('page-subtitle', 'Kelola jadwal belajar mengajar mingguan')

@section('content')
<div class="content-card">
    <div class="content-card-header">
        <h6 class="content-card-title"><i class="fas fa-clock me-2 text-primary"></i>Daftar Jadwal Pelajaran</h6>
        <a href="{{ route('admin.jadwal.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Tambah Jadwal
        </a>
    </div>
    <div class="content-card-body p-3">
        {{-- Filter --}}
        <form method="GET" class="row g-2 mb-3">
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
            <div class="col-12 col-md-3">
                <select name="guru_id" class="form-select">
                    <option value="">Semua Guru</option>
                    @foreach($gurus as $g)
                        <option value="{{ $g->id }}" {{ request('guru_id') == $g->id ? 'selected' : '' }}>
                            {{ $g->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select name="tahun_ajaran_id" class="form-select">
                    <option value="">Semua Tahun Ajaran</option>
                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->id }}" {{ request('tahun_ajaran_id') == $ta->id ? 'selected' : '' }}>
                            {{ $ta->nama }} ({{ ucfirst($ta->semester) }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="fas fa-filter me-1"></i> Filter</button>
                @if(request()->anyFilled(['kelas_id', 'guru_id', 'tahun_ajaran_id']))
                    <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>
                @endif
            </div>
        </form>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Hari & Jam</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru Pengampu</th>
                        <th>Ruangan</th>
                        <th>Status</th>
                        <th class="text-end" style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwals as $j)
                        <tr>
                            <td>
                                <span class="badge bg-indigo-subtle text-indigo fw-bold" style="background:#e0e7ff;color:#4338ca;">{{ $j->hari }}</span>
                                <div class="small fw-semibold mt-1">{{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}</div>
                            </td>
                            <td><span class="fw-bold">{{ $j->kelas?->tingkat }} {{ $j->kelas?->nama }}</span></td>
                            <td>
                                <strong>{{ $j->mataPelajaran?->nama }}</strong>
                                <div class="text-muted small">{{ $j->mataPelajaran?->kode }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $j->guru?->nama }}</div>
                                <div class="text-muted small">NIP: {{ $j->guru?->nip ?? '-' }}</div>
                            </td>
                            <td>{{ $j->ruangan ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $j->status == 'aktif' ? 'success' : 'secondary' }}-subtle text-{{ $j->status == 'aktif' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($j->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.jadwal.edit', $j->id) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.jadwal.destroy', $j->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
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
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data jadwal pelajaran yang sesuai filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($jadwals->hasPages())
            <div class="mt-3">
                {{ $jadwals->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
