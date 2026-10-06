@extends('layouts.app')

@section('title', 'Persetujuan Izin Siswa')
@section('page-title', 'Persetujuan Izin Siswa')
@section('page-subtitle', 'Verifikasi, setujui (ACC), atau tolak permohonan izin dan surat sakit siswa')

@section('content')
{{-- Stat Counter Banner --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fffbeb;">
                <i class="fas fa-clock" style="color:#d97706;"></i>
            </div>
            <div class="stat-value text-warning">{{ $pendingCount }}</div>
            <div class="stat-label">Menunggu Persetujuan Guru</div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;">
                <i class="fas fa-check-circle" style="color:#10b981;"></i>
            </div>
            <div class="stat-value text-success">
                {{ \App\Models\PengajuanIzin::where('status', 'disetujui')->count() }}
            </div>
            <div class="stat-label">Total Izin Disetujui</div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef2f2;">
                <i class="fas fa-times-circle" style="color:#ef4444;"></i>
            </div>
            <div class="stat-value text-danger">
                {{ \App\Models\PengajuanIzin::where('status', 'ditolak')->count() }}
            </div>
            <div class="stat-label">Total Izin Ditolak</div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="content-card-header d-flex justify-content-between align-items-center">
        <h6 class="content-card-title mb-0">
            <i class="fas fa-clipboard-check me-2 text-primary"></i>Daftar Pengajuan Izin / Sakit Siswa
        </h6>
        @if($pendingCount > 0)
            <span class="badge bg-warning text-dark px-3 py-2 fw-semibold">
                <i class="fas fa-bell me-1"></i> {{ $pendingCount }} Pengajuan Perlu Direspons
            </span>
        @endif
    </div>
    <div class="content-card-body p-3">
        {{-- Filter Form --}}
        <form method="GET" class="row g-2 mb-4">
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
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>⏳ Menunggu Konfirmasi</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>✅ Disetujui</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select name="jenis" class="form-select">
                    <option value="">Semua Jenis (Izin & Sakit)</option>
                    <option value="izin" {{ request('jenis') == 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ request('jenis') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-1"></i> Saring Data
                </button>
                @if(request()->anyFilled(['status', 'jenis', 'kelas_id']))
                    <a href="{{ route('guru.pengajuan-izin.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Tgl Pengajuan</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Jenis</th>
                        <th>Rentang Tanggal</th>
                        <th>Alasan Siswa</th>
                        <th>Status</th>
                        <th class="text-end" style="min-width:140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $p)
                        <tr class="{{ $p->status == 'menunggu' ? 'table-warning-subtle' : '' }}">
                            <td>
                                <span class="fw-medium text-dark">{{ $p->created_at->format('d/m/Y') }}</span>
                                <div class="text-muted" style="font-size:11px;">{{ $p->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                <strong class="text-dark">{{ $p->siswa?->nama ?? '-' }}</strong>
                                <div class="text-muted small">NIS: {{ $p->siswa?->nis ?? '-' }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $p->siswa?->kelas ? $p->siswa->kelas->tingkat . ' ' . $p->siswa->kelas->nama : '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $p->jenis == 'sakit' ? 'warning' : 'info' }}-subtle text-{{ $p->jenis == 'sakit' ? 'warning' : 'info' }} text-uppercase fw-bold">
                                    <i class="fas fa-{{ $p->jenis == 'sakit' ? 'head-side-cough' : 'calendar-day' }} me-1"></i>
                                    {{ $p->jenis }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">
                                    {{ $p->tanggal_mulai ? \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') : '-' }}
                                    @if($p->tanggal_mulai != $p->tanggal_selesai)
                                        <br><small class="text-muted">s/d {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}</small>
                                    @endif
                                </div>
                                <span class="badge bg-secondary-subtle text-secondary" style="font-size:10px;">{{ $p->jumlah_hari }} Hari</span>
                            </td>
                            <td>
                                <span class="text-muted small">{{ Str::limit($p->keterangan, 40) }}</span>
                            </td>
                            <td>
                                @if($p->status == 'menunggu')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Menunggu ACC</span>
                                @elseif($p->status == 'disetujui')
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Disetujui</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Ditolak</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('guru.pengajuan-izin.show', $p->id) }}" class="btn btn-sm {{ $p->status == 'menunggu' ? 'btn-primary' : 'btn-outline-secondary' }}">
                                    @if($p->status == 'menunggu')
                                        <i class="fas fa-check-double me-1"></i> Proses ACC
                                    @else
                                        <i class="fas fa-eye me-1"></i> Detail
                                    @endif
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="fas fa-check-circle fs-2 text-success mb-2 d-block"></i>
                                Tidak ada data pengajuan izin siswa yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pengajuans->hasPages())
            <div class="mt-4">
                {{ $pengajuans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
