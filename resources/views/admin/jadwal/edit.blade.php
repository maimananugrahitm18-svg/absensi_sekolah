@extends('layouts.app')

@section('title', 'Edit Jadwal')
@section('page-title', 'Edit Jadwal')
@section('page-subtitle', 'Perbarui jadwal pelajaran')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="content-card">
            <div class="content-card-header">
                <h6 class="content-card-title"><i class="fas fa-edit me-2 text-primary"></i>Edit Jadwal Pelajaran</h6>
                <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="p-4">
                <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                            <select id="tahun_ajaran_id" name="tahun_ajaran_id" class="form-select @error('tahun_ajaran_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                @foreach($tahunAjarans as $ta)
                                    <option value="{{ $ta->id }}" {{ old('tahun_ajaran_id', $jadwal->tahun_ajaran_id) == $ta->id ? 'selected' : '' }}>
                                        {{ $ta->nama }} ({{ ucfirst($ta->semester) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('tahun_ajaran_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kelas <span class="text-danger">*</span></label>
                            <select id="kelas_id" name="kelas_id" class="form-select @error('kelas_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}" {{ old('kelas_id', $jadwal->kelas_id) == $k->id ? 'selected' : '' }}>
                                        {{ $k->tingkat }} {{ $k->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                            <select id="mata_pelajaran_id" name="mata_pelajaran_id" class="form-select @error('mata_pelajaran_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($mapels as $m)
                                    <option value="{{ $m->id }}" {{ old('mata_pelajaran_id', $jadwal->mata_pelajaran_id) == $m->id ? 'selected' : '' }}>
                                        {{ $m->nama }} ({{ $m->kode }})
                                    </option>
                                @endforeach
                            </select>
                            @error('mata_pelajaran_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Guru Pengampu <span class="text-danger">*</span></label>
                            <select id="guru_id" name="guru_id" class="form-select @error('guru_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Guru --</option>
                                @foreach($gurus as $g)
                                    <option value="{{ $g->id }}" {{ old('guru_id', $jadwal->guru_id) == $g->id ? 'selected' : '' }}>
                                        {{ $g->nama }} (NIP: {{ $g->nip ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('guru_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Hari <span class="text-danger">*</span></label>
                            <select id="hari" name="hari" class="form-select @error('hari') is-invalid @enderror" required>
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                                    <option value="{{ $hari }}" {{ old('hari', $jadwal->hari) == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                                @endforeach
                            </select>
                            @error('hari') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" id="jam_mulai" name="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror" value="{{ old('jam_mulai', substr($jadwal->jam_mulai, 0, 5)) }}" required>
                            @error('jam_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" id="jam_selesai" name="jam_selesai" class="form-control @error('jam_selesai') is-invalid @enderror" value="{{ old('jam_selesai', substr($jadwal->jam_selesai, 0, 5)) }}" required>
                            @error('jam_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ruangan</label>
                            <input type="text" id="ruangan" name="ruangan" class="form-control @error('ruangan') is-invalid @enderror" value="{{ old('ruangan', $jadwal->ruangan) }}" placeholder="Contoh: R.101">
                            @error('ruangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="aktif" {{ old('status', $jadwal->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $jadwal->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top mt-4 pt-3">
                        <a href="{{ route('admin.jadwal.index') }}" class="btn btn-light px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> Update Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ── Notif Bentrok Real-Time ────────────────────────────────────────── --}}
<div id="alert-bentrok" class="alert d-none mx-auto mt-3" role="alert" style="max-width:700px; border-radius:12px;"></div>

@push('scripts')
<script>
(function () {
    const jadwalData = @json($jadwalData ?? []);

    const fields = ['#guru_id', '#hari', '#jam_mulai', '#jam_selesai', '#ruangan', '#tahun_ajaran_id'];
    fields.forEach(f => {
        const el = document.querySelector(f);
        if (el) el.addEventListener('change', cekBentrok);
        if (el) el.addEventListener('input', cekBentrok);
    });

    function val(id) {
        const el = document.querySelector(id);
        return el ? el.value.trim() : '';
    }

    function cekBentrok() {
        const guruId     = val('#guru_id');
        const hari       = val('#hari');
        const jamMulai   = val('#jam_mulai');
        const jamSelesai = val('#jam_selesai');
        const ruangan    = val('#ruangan');
        const taId       = val('#tahun_ajaran_id');
        const alertBox   = document.getElementById('alert-bentrok');

        alertBox.className = 'alert d-none mx-auto mt-3';
        alertBox.innerHTML = '';

        if (!guruId || !hari || !jamMulai || !jamSelesai || !taId) return;
        if (jamMulai >= jamSelesai) return;

        const msgs = [];

        // Cek bentrok guru
        const bentrokGuru = jadwalData.filter(j =>
            String(j.guru_id) === guruId &&
            j.hari === hari &&
            String(j.tahun_ajaran_id) === taId &&
            j.status === 'aktif' &&
            timeOverlap(jamMulai, jamSelesai, j.jam_mulai.substring(0,5), j.jam_selesai.substring(0,5))
        );
        if (bentrokGuru.length > 0) {
            const b = bentrokGuru[0];
            msgs.push(`<i class="fas fa-user-times me-2"></i><strong>Guru bentrok!</strong> Sudah dijadwalkan hari <strong>${b.hari}</strong> jam <strong>${b.jam_mulai.substring(0,5)}–${b.jam_selesai.substring(0,5)}</strong> di Kelas <strong>${b.kelas_nama}</strong>.`);
        }

        // Cek bentrok ruangan
        if (ruangan) {
            const bentrokRuangan = jadwalData.filter(j =>
                j.ruangan && j.ruangan.toLowerCase() === ruangan.toLowerCase() &&
                j.hari === hari &&
                String(j.tahun_ajaran_id) === taId &&
                j.status === 'aktif' &&
                timeOverlap(jamMulai, jamSelesai, j.jam_mulai.substring(0,5), j.jam_selesai.substring(0,5))
            );
            if (bentrokRuangan.length > 0) {
                const b = bentrokRuangan[0];
                msgs.push(`<i class="fas fa-door-closed me-2"></i><strong>Ruangan bentrok!</strong> <strong>${ruangan}</strong> sudah dipakai hari <strong>${b.hari}</strong> jam <strong>${b.jam_mulai.substring(0,5)}–${b.jam_selesai.substring(0,5)}</strong>.`);
            }
        }

        if (msgs.length > 0) {
            alertBox.className = 'alert alert-warning mx-auto mt-3';
            alertBox.style.maxWidth = '700px';
            alertBox.style.borderRadius = '12px';
            alertBox.innerHTML = '<ul class="mb-0">' + msgs.map(m => `<li>${m}</li>`).join('') + '</ul>';
        }
    }

    function timeOverlap(s1, e1, s2, e2) {
        return s1 < e2 && e1 > s2;
    }
})();
</script>
@endpush
@endsection
