@extends('layouts.app')

@section('title', 'Presensi Kehadiran Saya')
@section('page-title', 'Presensi Guru')
@section('page-subtitle', 'Absen masuk dan pulang setiap hari kerja')

@section('content')
<div class="row g-4 mb-4">
    {{-- Status Hari Ini --}}
    <div class="col-lg-5">
        <div class="content-card p-4">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="stat-icon rounded-3" style="background:#eef2ff;width:50px;height:50px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-calendar-day fa-lg" style="color:#6366f1;"></i>
                </div>
                <div>
                    <div class="fw-bold fs-6">{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</div>
                    <div class="text-muted small">Jam sekarang: <strong id="jamSekarang"></strong></div>
                </div>
            </div>

            @if($absensiHariIni)
                {{-- Sudah Absen --}}
                <div class="alert border-0 mb-3" style="background:{{ $absensiHariIni->status === 'terlambat' ? '#fffbeb' : '#f0fdf4' }};">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-check-circle {{ $absensiHariIni->status === 'terlambat' ? 'text-warning' : 'text-success' }} fs-5"></i>
                        <div>
                            <div class="fw-bold">
                                @if($absensiHariIni->status === 'terlambat')
                                    ⚠️ Absen Masuk Tercatat (Terlambat)
                                @else
                                    ✅ Absen Masuk Tepat Waktu
                                @endif
                            </div>
                            <div class="small text-muted">Jam masuk: <strong>{{ substr($absensiHariIni->jam_masuk, 0, 5) }}</strong></div>
                        </div>
                    </div>
                </div>

                @if($absensiHariIni->jam_pulang)
                    <div class="alert alert-info border-0 mb-3">
                        <i class="fas fa-sign-out-alt me-1"></i>
                        <strong>Sudah Absen Pulang</strong> — Jam: {{ substr($absensiHariIni->jam_pulang, 0, 5) }}
                    </div>
                @else
                    {{-- Tombol Absen Pulang --}}
                    <div class="border rounded-3 p-3 mb-3" style="background:#fafafa;">
                        <h6 class="fw-bold mb-3"><i class="fas fa-sign-out-alt me-2 text-danger"></i>Absen Pulang</h6>
                        <form action="{{ route('guru.presensi.pulang') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100 py-2">
                                <i class="fas fa-door-open me-1"></i> Klik Absen Pulang Sekarang
                            </button>
                        </form>
                    </div>
                @endif
            @else
                {{-- Belum Absen Masuk --}}
                <div class="border rounded-3 p-4" style="background:#fafafa;">
                    <h6 class="fw-bold mb-3 text-success"><i class="fas fa-sign-in-alt me-2"></i>Absen Masuk Hari Ini</h6>
                    <form action="{{ route('guru.presensi.masuk') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Keterangan (opsional)</label>
                            <input type="text" name="keterangan" class="form-control form-control-sm" placeholder="Contoh: WFH, Piket, Tugas Luar, dll">
                        </div>
                        <button type="submit" class="btn btn-success w-100 fw-bold py-2">
                            <i class="fas fa-fingerprint me-2"></i> Absen Masuk Sekarang
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    {{-- Riwayat Presensi --}}
    <div class="col-lg-7">
        <div class="content-card">
            <div class="content-card-header">
                <h6 class="content-card-title"><i class="fas fa-history me-2 text-primary"></i>Riwayat Kehadiran Saya</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th class="text-center">Jam Masuk</th>
                            <th class="text-center">Jam Pulang</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $r)
                            <tr>
                                <td>
                                    <strong>{{ \Carbon\Carbon::parse($r->tanggal)->locale('id')->isoFormat('ddd, D MMM Y') }}</strong>
                                </td>
                                <td class="text-center">
                                    {{ $r->jam_masuk ? substr($r->jam_masuk, 0, 5) : '-' }}
                                </td>
                                <td class="text-center">
                                    @if($r->jam_pulang)
                                        {{ substr($r->jam_pulang, 0, 5) }}
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @php
                                        $badgeColor = match($r->status) {
                                            'hadir'     => 'success',
                                            'terlambat' => 'warning',
                                            'izin'      => 'info',
                                            'sakit'     => 'warning',
                                            default     => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badgeColor }}-subtle text-{{ $badgeColor }} px-2 py-1 text-uppercase">
                                        {{ $r->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Belum ada riwayat presensi kehadiran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($riwayat->hasPages())
                <div class="p-3">{{ $riwayat->links() }}</div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateJam() {
        const now = new Date();
        const jam = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const el = document.getElementById('jamSekarang');
        if (el) el.textContent = jam;
    }
    updateJam();
    setInterval(updateJam, 1000);
</script>
@endpush
@endsection
