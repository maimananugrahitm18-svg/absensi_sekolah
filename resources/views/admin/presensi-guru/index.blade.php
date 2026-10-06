@extends('layouts.app')

@section('title', 'Rekap Presensi Guru')
@section('page-title', 'Rekap Presensi Guru')
@section('page-subtitle', 'Pantau kehadiran harian seluruh guru')

@section('content')
{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="content-card p-3 d-flex align-items-center gap-3">
            <div class="stat-icon rounded-3" style="background:#f0fdf4;width:50px;height:50px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-user-check fa-lg text-success"></i>
            </div>
            <div>
                <div class="text-muted small">Hadir Tepat Waktu</div>
                <div class="fw-bold fs-5 text-success">{{ $hadirHariIni }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="content-card p-3 d-flex align-items-center gap-3">
            <div class="stat-icon rounded-3" style="background:#fffbeb;width:50px;height:50px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-clock fa-lg text-warning"></i>
            </div>
            <div>
                <div class="text-muted small">Terlambat</div>
                <div class="fw-bold fs-5 text-warning">{{ $terlambatHariIni }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="content-card p-3 d-flex align-items-center gap-3">
            <div class="stat-icon rounded-3" style="background:#fef2f2;width:50px;height:50px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-user-times fa-lg text-danger"></i>
            </div>
            <div>
                <div class="text-muted small">Belum Absen Masuk</div>
                <div class="fw-bold fs-5 text-danger">{{ $belumAbsen }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="content-card p-3 d-flex align-items-center gap-3">
            <div class="stat-icon rounded-3" style="background:#f5f3ff;width:50px;height:50px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-users fa-lg" style="color:#8b5cf6;"></i>
            </div>
            <div>
                <div class="text-muted small">Total Guru Aktif</div>
                <div class="fw-bold fs-5">{{ $totalGuru }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="content-card mb-4">
    <div class="content-card-header">
        <h6 class="content-card-title"><i class="fas fa-filter me-2 text-primary"></i>Filter Presensi</h6>
    </div>
    <div class="p-3">
        <form action="{{ route('admin.presensi-guru.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal', $tanggal) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Cari Nama Guru</label>
                <input type="text" name="nama" class="form-control" placeholder="Ketik nama guru..." value="{{ request('nama') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="hadir" {{ request('status')=='hadir'?'selected':'' }}>Hadir</option>
                    <option value="terlambat" {{ request('status')=='terlambat'?'selected':'' }}>Terlambat</option>
                    <option value="izin" {{ request('status')=='izin'?'selected':'' }}>Izin</option>
                    <option value="sakit" {{ request('status')=='sakit'?'selected':'' }}>Sakit</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Tampilkan</button>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="content-card">
    <div class="content-card-header d-flex justify-content-between align-items-center">
        <h6 class="content-card-title">
            <i class="fas fa-table me-2 text-primary"></i>
            Data Presensi — {{ \Carbon\Carbon::parse(request('tanggal', $tanggal))->locale('id')->isoFormat('dddd, D MMMM Y') }}
        </h6>
        <a href="{{ route('admin.presensi-guru.export', request()->all()) }}" class="btn btn-success btn-sm">
            <i class="fas fa-file-excel me-1"></i> Export Excel
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-custom table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Guru</th>
                    <th class="text-center">Jam Masuk</th>
                    <th class="text-center">Jam Pulang</th>
                    <th class="text-center">Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($presensi as $i => $p)
                <tr>
                    <td>{{ $presensi->firstItem() + $i }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold"
                                 style="width:36px;height:36px;font-size:14px;flex-shrink:0;">
                                {{ strtoupper(substr($p->guru->nama ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $p->guru->nama ?? '—' }}</div>
                                <div class="text-muted small">NIP: {{ $p->guru->nip ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        @if($p->jam_masuk)
                            <span class="badge bg-light text-dark border">{{ substr($p->jam_masuk, 0, 5) }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($p->jam_pulang)
                            <span class="badge bg-light text-dark border">{{ substr($p->jam_pulang, 0, 5) }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @php
                            $color = match($p->status) {
                                'hadir'     => 'success',
                                'terlambat' => 'warning',
                                'izin'      => 'info',
                                'sakit'     => 'warning',
                                default     => 'secondary'
                            };
                        @endphp
                        <span class="badge bg-{{ $color }}-subtle text-{{ $color }} px-2 text-uppercase">{{ $p->status }}</span>
                    </td>
                    <td>{{ $p->keterangan ?: '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="fas fa-inbox fs-2 d-block mb-2 opacity-50"></i>
                        Tidak ada data presensi pada tanggal ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($presensi->hasPages())
        <div class="p-3">{{ $presensi->links() }}</div>
    @endif
</div>
@endsection
