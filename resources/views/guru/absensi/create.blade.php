@extends('layouts.app')

@section('title', 'Isi Absensi Kelas')
@section('page-title', 'Pengisian Absensi Siswa')
@section('page-subtitle', $jadwal->mataPelajaran?->nama . ' - Kelas ' . $jadwal->kelas?->tingkat . ' ' . $jadwal->kelas?->nama)

@section('content')
<div class="content-card mb-4">
    <div class="p-4 bg-light border-bottom">
        <div class="row align-items-center g-3">
            <div class="col-md-7">
                <h5 class="fw-bold mb-1">{{ $jadwal->mataPelajaran?->nama }}</h5>
                <div class="text-muted small">
                    <span class="me-3"><i class="fas fa-door-open me-1"></i> {{ $jadwal->kelas?->tingkat }} {{ $jadwal->kelas?->nama }}</span>
                    <span class="me-3"><i class="fas fa-clock me-1"></i> {{ $jadwal->hari }}, {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</span>
                    <span><i class="fas fa-map-marker-alt me-1"></i> {{ $jadwal->ruangan ?? 'Ruang Kelas' }}</span>
                </div>
            </div>
            <div class="col-md-5">
                <form method="GET" action="{{ route('guru.absensi.create', $jadwal->id) }}" class="d-flex gap-2 justify-content-md-end">
                    <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control form-control-sm" style="max-width: 170px;" onchange="this.form.submit()">
                    <noscript><button type="submit" class="btn btn-sm btn-primary">Ganti</button></noscript>
                </form>
            </div>
        </div>
    </div>

    {{-- Quick Action Bar --}}
    <div class="p-3 bg-white border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="text-muted small">
            Total Siswa Terdaftar: <strong>{{ $siswas->count() }} orang</strong>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-success btn-sm" onclick="setAllStatus('hadir')">
                <i class="fas fa-check-double me-1"></i> Set Semua Hadir
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setAllStatus('clear')">
                <i class="fas fa-undo me-1"></i> Reset Pilihan
            </button>
        </div>
    </div>

    <form action="{{ route('guru.absensi.store', $jadwal->id) }}" method="POST">
        @csrf
        <input type="hidden" name="tanggal" value="{{ $tanggal }}">

        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th class="text-center" style="width: 320px;">Status Kehadiran</th>
                        <th>Catatan / Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $index => $siswa)
                        @php
                            $izin = isset($izinDisetujui) ? ($izinDisetujui[$siswa->id] ?? null) : null;
                            $defaultStatus = $izin ? $izin->jenis : 'hadir';
                            $currentStatus = old("absensi.{$siswa->id}.status", $existing[$siswa->id] ?? $defaultStatus);
                            $defaultKet = $izin ? ($izin->keterangan ? Str::limit($izin->keterangan, 30) : ucfirst($izin->jenis) . ' telah di-ACC') : '';
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><span class="text-muted small">{{ $siswa->nis ?? '-' }}</span></td>
                            <td>
                                <strong>{{ $siswa->nama }}</strong>
                                <div class="text-muted small">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                @if($izin)
                                    <span class="badge bg-{{ $izin->jenis == 'sakit' ? 'warning' : 'info' }}-subtle text-{{ $izin->jenis == 'sakit' ? 'warning' : 'info' }} border border-{{ $izin->jenis == 'sakit' ? 'warning' : 'info' }}-subtle mt-1" style="font-size:10.5px;">
                                        <i class="fas fa-check-circle me-1"></i> {{ ucfirst($izin->jenis) }} telah di-ACC
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check status-radio status-hadir" name="absensi[{{ $siswa->id }}][status]" id="hadir_{{ $siswa->id }}" value="hadir" {{ $currentStatus == 'hadir' ? 'checked' : '' }} required>
                                    <label class="btn btn-outline-success btn-sm py-2" for="hadir_{{ $siswa->id }}">Hadir</label>

                                    <input type="radio" class="btn-check status-radio status-izin" name="absensi[{{ $siswa->id }}][status]" id="izin_{{ $siswa->id }}" value="izin" {{ $currentStatus == 'izin' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-info btn-sm py-2" for="izin_{{ $siswa->id }}">Izin</label>

                                    <input type="radio" class="btn-check status-radio status-sakit" name="absensi[{{ $siswa->id }}][status]" id="sakit_{{ $siswa->id }}" value="sakit" {{ $currentStatus == 'sakit' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-warning btn-sm py-2" for="sakit_{{ $siswa->id }}">Sakit</label>

                                    <input type="radio" class="btn-check status-radio status-alpha" name="absensi[{{ $siswa->id }}][status]" id="alpha_{{ $siswa->id }}" value="alpha" {{ $currentStatus == 'alpha' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-danger btn-sm py-2" for="alpha_{{ $siswa->id }}">Alpa</label>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="absensi[{{ $siswa->id }}][keterangan]" class="form-control form-control-sm" placeholder="Opsional (cth: demam, dispen)" value="{{ old("absensi.{$siswa->id}.keterangan", $defaultKet) }}">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                Belum ada data siswa aktif di kelas ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($siswas->count() > 0)
            <div class="p-4 bg-light border-top d-flex justify-content-between align-items-center">
                <a href="{{ route('guru.absensi.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Batal / Kembali
                </a>
                <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">
                    <i class="fas fa-save me-1"></i> Simpan Data Absensi
                </button>
            </div>
        @endif
    </form>
</div>

@push('scripts')
<script>
    function setAllStatus(type) {
        if (type === 'hadir') {
            document.querySelectorAll('.status-hadir').forEach(el => el.checked = true);
        } else if (type === 'clear') {
            document.querySelectorAll('.status-radio').forEach(el => el.checked = false);
        }
    }
</script>
@endpush
@endsection
