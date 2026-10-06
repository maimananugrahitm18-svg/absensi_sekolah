@extends('layouts.app')

@section('title', 'Rekap Absensi Siswa')
@section('page-title', 'Rekap Absensi Siswa')
@section('page-subtitle', 'Laporan dan statistik kehadiran siswa per kelas terstruktur')

@section('content')
@php
    $siswas = $siswas ?? collect();
@endphp
{{-- Filter Section --}}
<div class="content-card mb-4 d-print-none">
    <div class="content-card-header d-flex justify-content-between align-items-center">
        <h6 class="content-card-title mb-0"><i class="fas fa-filter me-2 text-primary"></i>Filter Rekapitulasi</h6>
        @if(request()->anyFilled(['kelas_id', 'tahun_ajaran_id', 'bulan']))
            <a href="{{ route('admin.rekap.index') }}" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-undo me-1"></i> Reset Filter
            </a>
        @endif
    </div>
    <div class="p-3">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Pilih Kelas</label>
                <select name="kelas_id" class="form-select">
                    <option value="">Semua Kelas (Kelompokkan Per Kelas)</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->tingkat }} {{ $k->nama }} {{ $k->jurusan ? '(' . $k->jurusan . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Tahun Ajaran</label>
                <select name="tahun_ajaran_id" class="form-select">
                    <option value="">Semua Periode</option>
                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->id }}" {{ request('tahun_ajaran_id', $tahunAktif?->id) == $ta->id ? 'selected' : '' }}>
                            {{ $ta->nama }} ({{ ucfirst($ta->semester) }}) {{ $ta->is_aktif ? '★' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Bulan</label>
                <select name="bulan" class="form-select">
                    <option value="">Semua Bulan</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ sprintf('%02d', $m) }}" {{ request('bulan') == sprintf('%02d', $m) ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m)->locale('id')->isoFormat('MMMM') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Overview Stats & Export Buttons --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary fs-6 px-3 py-2 rounded-pill">
                <i class="fas fa-users me-1"></i> Total {{ $totalSiswaSemua }} Siswa
            </span>
            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 fs-6 px-3 py-2 rounded-pill">
                <i class="fas fa-chalkboard me-1"></i> {{ count($rekapPerKelas) }} Kelompok Kelas
            </span>
        </div>
    </div>
    <div class="d-flex gap-2 d-print-none">
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm px-3">
            <i class="fas fa-print me-1"></i> Cetak Laporan
        </button>
        <a href="{{ route('admin.rekap.export', request()->all()) }}" class="btn btn-success btn-sm px-3">
            <i class="fas fa-file-excel me-1"></i> Export Excel (.xls)
        </a>
    </div>
</div>

{{-- Quick Navigation Pills if Multiple Classes --}}
@if(count($rekapPerKelas) > 1)
    <div class="card border-0 shadow-sm p-3 mb-4 bg-light d-print-none">
        <div class="small fw-semibold text-muted mb-2"><i class="fas fa-compass me-1"></i> Lompat Cepat ke Kelas:</div>
        <div class="d-flex flex-wrap gap-2">
            @foreach($rekapPerKelas as $group)
                @php
                    $namaKelas = $group['kelas'] ? $group['kelas']->nama_lengkap : 'Tanpa Kelas';
                    $anchorId = 'kelas-' . ($group['kelas'] ? $group['kelas']->id : 'none');
                @endphp
                <a href="#{{ $anchorId }}" class="btn btn-sm btn-white border shadow-xs text-dark d-flex align-items-center gap-1">
                    <span class="fw-bold">{{ $namaKelas }}</span>
                    <span class="badge bg-secondary-subtle text-secondary rounded-pill">{{ $group['total_siswa'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
@endif

{{-- Rekap Cards Grouped Per Class --}}
@forelse($rekapPerKelas as $group)
    @php
        $k = $group['kelas'];
        $anchorId = 'kelas-' . ($k ? $k->id : 'none');
    @endphp
    <div class="content-card mb-4" id="{{ $anchorId }}">
        {{-- Header Kelas --}}
        <div class="content-card-header bg-white border-bottom py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 20px;">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0 text-dark">
                        {{ $k ? $k->nama_lengkap : 'Siswa Tanpa Kelas' }}
                    </h5>
                    <div class="text-muted small">
                        @if($k && $k->wali_kelas)
                            <i class="fas fa-chalkboard-teacher me-1"></i> Wali Kelas: <strong>{{ $k->wali_kelas }}</strong>
                        @endif
                        @if($k && $k->jurusan)
                            <span class="mx-2">•</span> Jurusan: <strong>{{ $k->jurusan }}</strong>
                        @endif
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-dark border px-3 py-2">
                    <i class="fas fa-user-friends me-1 text-primary"></i> <strong>{{ $group['total_siswa'] }}</strong> Siswa
                </span>
                <span class="badge bg-{{ $group['avg_pct'] >= 80 ? 'success' : ($group['avg_pct'] >= 60 ? 'warning' : 'danger') }}-subtle text-{{ $group['avg_pct'] >= 80 ? 'success' : ($group['avg_pct'] >= 60 ? 'warning' : 'danger') }} border px-3 py-2 fw-bold">
                    Rata-rata Kehadiran: {{ $group['avg_pct'] }}%
                </span>
            </div>
        </div>

        {{-- Tabel Siswa Kelas Ini --}}
        <div class="content-card-body p-0">
            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 45px;" class="text-center">No</th>
                            <th style="width: 110px;">NIS</th>
                            <th>Nama Siswa</th>
                            <th class="text-center text-success" style="width: 75px;">Hadir</th>
                            <th class="text-center text-info" style="width: 75px;">Izin</th>
                            <th class="text-center text-warning" style="width: 75px;">Sakit</th>
                            <th class="text-center text-danger" style="width: 75px;">Alpa</th>
                            <th class="text-center fw-bold" style="width: 85px;">Total</th>
                            <th class="text-center" style="width: 140px;">% Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($group['items'] as $idx => $row)
                            @php
                                $s = $row['siswa'];
                                $pct = $row['pct'];
                            @endphp
                            <tr>
                                <td class="text-center text-muted small fw-semibold">{{ $idx + 1 }}</td>
                                <td><span class="text-muted small">{{ $s->nis ?? '-' }}</span></td>
                                <td>
                                    <a href="{{ route('admin.siswa.show', $s->id) }}" class="fw-semibold text-dark text-decoration-none hover-primary">
                                        {{ $s->nama }}
                                    </a>
                                </td>
                                <td class="text-center fw-bold text-success">{{ $row['hadir'] }}</td>
                                <td class="text-center fw-bold text-info">{{ $row['izin'] }}</td>
                                <td class="text-center fw-bold text-warning">{{ $row['sakit'] }}</td>
                                <td class="text-center fw-bold text-danger">{{ $row['alpha'] }}</td>
                                <td class="text-center fw-bold text-dark">{{ $row['total'] }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 6px;">
                                            <div class="progress-bar {{ $pct >= 80 ? 'bg-success' : ($pct >= 60 ? 'bg-warning' : 'bg-danger') }}" style="width: {{ $pct }}%;"></div>
                                        </div>
                                        <span class="small fw-bold {{ $pct >= 80 ? 'text-success' : ($pct >= 60 ? 'text-warning' : 'text-danger') }}">{{ $pct }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Belum ada data siswa di kelas ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    {{-- Subtotal Kelas --}}
                    @if(count($group['items']) > 0)
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="3" class="text-end text-uppercase pe-3">
                                    <i class="fas fa-calculator me-1 text-primary"></i> Subtotal Kelas {{ $k ? $k->nama_lengkap : '' }}:
                                </td>
                                <td class="text-center text-success">{{ $group['sum_hadir'] }}</td>
                                <td class="text-center text-info">{{ $group['sum_izin'] }}</td>
                                <td class="text-center text-warning">{{ $group['sum_sakit'] }}</td>
                                <td class="text-center text-danger">{{ $group['sum_alpha'] }}</td>
                                <td class="text-center text-dark">{{ $group['total_semua'] }}</td>
                                <td class="text-center text-primary">{{ $group['avg_pct'] }}% (Rata-rata)</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
@empty
    <div class="content-card p-5 text-center text-muted">
        <i class="fas fa-folder-open fa-3x mb-3 text-secondary opacity-50"></i>
        <h5>Tidak ada data kehadiran yang sesuai</h5>
        <p class="small mb-0">Coba ubah filter kelas, tahun ajaran, atau bulan di atas.</p>
    </div>
@endforelse

<style>
@media print {
    .sidebar, .topbar, .d-print-none { display: none !important; }
    .main-content { margin-left: 0 !important; width: 100% !important; padding: 0 !important; }
    .content-card { border: 1px solid #ddd !important; box-shadow: none !important; page-break-after: always; margin-bottom: 20px !important; }
}
</style>
@endsection
