@extends('layouts.app')

@section('title', 'Riwayat Absensi')
@section('page-title', 'Riwayat Kehadiran')
@section('page-subtitle', 'Daftar lengkap kehadiran dan ketidakhadiran kamu di sekolah')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-3">
        <div class="stat-card p-3 text-center">
            <div class="stat-value text-success fs-3">{{ $rekap['hadir'] ?? 0 }}</div>
            <div class="stat-label">Hadir</div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="stat-card p-3 text-center">
            <div class="stat-value text-info fs-3">{{ $rekap['izin'] ?? 0 }}</div>
            <div class="stat-label">Izin</div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="stat-card p-3 text-center">
            <div class="stat-value text-warning fs-3">{{ $rekap['sakit'] ?? 0 }}</div>
            <div class="stat-label">Sakit</div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="stat-card p-3 text-center">
            <div class="stat-value text-danger fs-3">{{ $rekap['alpha'] ?? 0 }}</div>
            <div class="stat-label">Alpa</div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="content-card-header">
        <h6 class="content-card-title"><i class="fas fa-filter me-2 text-primary"></i>Filter Riwayat Kehadiran</h6>
    </div>
    <div class="p-3 border-bottom">
        <form method="GET" class="row g-2">
            <div class="col-12 col-md-4">
                <select name="tahun_ajaran_id" class="form-select">
                    <option value="">Semua Tahun Ajaran</option>
                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->id }}" {{ request('tahun_ajaran_id') == $ta->id ? 'selected' : '' }}>
                            {{ $ta->nama }} ({{ ucfirst($ta->semester) }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select name="bulan" class="form-select">
                    <option value="">Semua Bulan</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ sprintf('%02d', $m) }}" {{ request('bulan') == sprintf('%02d', $m) ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m)->locale('id')->isoFormat('MMMM') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="alpha" {{ request('status') == 'alpha' ? 'selected' : '' }}>Alpa</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="fas fa-search me-1"></i> Filter</button>
                @if(request()->anyFilled(['tahun_ajaran_id', 'bulan', 'status']))
                    <a href="{{ route('siswa.riwayat') }}" class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>
                @endif
            </div>
        </form>
    </div>

    <div class="content-card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Hari & Tanggal</th>
                        <th>Mata Pelajaran</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $index => $abs)
                        @php
                            $badgeColor = match($abs->status) {
                                'hadir' => 'success',
                                'izin'  => 'info',
                                'sakit' => 'warning',
                                default => 'danger'
                            };
                        @endphp
                        <tr>
                            <td>{{ $riwayat->firstItem() + $index }}</td>
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($abs->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}</strong>
                            </td>
                            <td>
                                <div>{{ $abs->jadwal?->mataPelajaran?->nama ?? '-' }}</div>
                                <div class="text-muted small">Guru: {{ $abs->jadwal?->guru?->nama }}</div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $badgeColor }}-subtle text-{{ $badgeColor }} px-3 py-1 text-uppercase">
                                    {{ $abs->status }}
                                </span>
                            </td>
                            <td>{{ $abs->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                Belum ada riwayat absensi yang cocok dengan filter yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($riwayat->hasPages())
            <div class="p-3">
                {{ $riwayat->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
