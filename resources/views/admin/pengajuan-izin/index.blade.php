@extends('layouts.app')

@section('title', 'Pemantauan Izin Siswa')
@section('page-title', 'Pemantauan Izin Siswa')
@section('page-subtitle', 'Monitoring status izin dan sakit siswa (Persetujuan & ACC diproses oleh Guru)')

@section('content')
<div class="content-card">
    <div class="content-card-header d-flex justify-content-between align-items-center">
        <h6 class="content-card-title mb-0"><i class="fas fa-eye me-2 text-primary"></i>Monitoring Pengajuan Izin Siswa</h6>
        <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-1">
            <i class="fas fa-info-circle me-1"></i> Admin Hanya Memantau &bull; ACC oleh Guru
        </span>
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
                <button type="submit" class="btn btn-secondary w-100"><i class="fas fa-filter me-1"></i> Filter</button>
                @if(request()->anyFilled(['status', 'jenis', 'kelas_id']))
                    <a href="{{ route('admin.pengajuan-izin.index') }}" class="btn btn-outline-secondary" title="Reset Filter"><i class="fas fa-times"></i></a>
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
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th class="text-end" style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $p)
                        <tr>
                            <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <strong>{{ $p->siswa?->nama }}</strong>
                                <div class="text-muted small">NIS: {{ $p->siswa?->nis ?? '-' }}</div>
                            </td>
                            <td>{{ $p->siswa?->kelas ? $p->siswa->kelas->tingkat . ' ' . $p->siswa->kelas->nama : '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $p->jenis == 'sakit' ? 'warning' : 'info' }}-subtle text-{{ $p->jenis == 'sakit' ? 'warning' : 'info' }} text-uppercase">
                                    {{ $p->jenis }}
                                </span>
                            </td>
                            <td>
                                {{ $p->tanggal_mulai ? \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') : '-' }}
                                @if($p->tanggal_mulai != $p->tanggal_selesai)
                                    s/d {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}
                                @endif
                            </td>
                            <td><span class="text-muted small">{{ Str::limit($p->keterangan, 35) }}</span></td>
                            <td>
                                @if($p->status == 'menunggu')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Menunggu</span>
                                @elseif($p->status == 'disetujui')
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Disetujui</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Ditolak</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.pengajuan-izin.show', $p->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye me-1"></i> Periksa
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Tidak ada data pengajuan izin.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pengajuans->hasPages())
            <div class="mt-3">
                {{ $pengajuans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
