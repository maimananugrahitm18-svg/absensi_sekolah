<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $guru = auth()->user()->guru;
        $jadwals = Jadwal::with(['kelas', 'mataPelajaran', 'tahunAjaran'])
            ->where('guru_id', $guru->id)
            ->where('status', 'aktif')
            ->orderByRaw("FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('jam_mulai')
            ->get();

        return view('guru.absensi.index', compact('jadwals'));
    }

    public function create(Request $request, Jadwal $jadwal)
    {
        $guru = auth()->user()->guru;

        // Pastikan jadwal milik guru ini
        if ($jadwal->guru_id !== $guru->id) {
            abort(403);
        }

        $tanggal  = $request->tanggal ?? today()->toDateString();
        $siswas   = Siswa::where('kelas_id', $jadwal->kelas_id)
                         ->where('status', 'aktif')
                         ->orderBy('nama')
                         ->get();

        // Ambil absensi yang sudah ada untuk jadwal & tanggal ini
        $existing = Absensi::where('jadwal_id', $jadwal->id)
                           ->where('tanggal', $tanggal)
                           ->pluck('status', 'siswa_id');

        // Ambil izin siswa yang telah disetujui untuk kelas ini pada tanggal ini
        $izinDisetujui = \App\Models\PengajuanIzin::whereHas('siswa', fn($q) => $q->where('kelas_id', $jadwal->kelas_id))
            ->where('status', 'disetujui')
            ->whereDate('tanggal_mulai', '<=', $tanggal)
            ->whereDate('tanggal_selesai', '>=', $tanggal)
            ->get()
            ->keyBy('siswa_id');

        return view('guru.absensi.create', compact('jadwal', 'tanggal', 'siswas', 'existing', 'izinDisetujui'));
    }

    public function store(Request $request, Jadwal $jadwal)
    {
        $guru = auth()->user()->guru;
        if ($jadwal->guru_id !== $guru->id) {
            abort(403);
        }

        $request->validate([
            'tanggal'         => 'required|date',
            'absensi'         => 'required|array',
            'absensi.*.status' => 'required|in:hadir,izin,sakit,alpha',
        ]);

        $tahunAktif = TahunAjaran::aktif();

        foreach ($request->absensi as $siswaId => $data) {
            Absensi::updateOrCreate(
                [
                    'jadwal_id' => $jadwal->id,
                    'siswa_id'  => $siswaId,
                    'tanggal'   => $request->tanggal,
                ],
                [
                    'guru_id'         => $guru->id,
                    'tahun_ajaran_id' => $tahunAktif?->id,
                    'status'          => $data['status'],
                    'keterangan'      => $data['keterangan'] ?? null,
                ]
            );
        }

        return redirect()->route('guru.absensi.index')
            ->with('success', 'Absensi berhasil disimpan untuk tanggal ' . $request->tanggal . '.');
    }

    public function rekap(Request $request)
    {
        $guru    = auth()->user()->guru;
        $jadwals = Jadwal::with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guru->id)
            ->where('status', 'aktif')
            ->get();

        $selectedJadwal = null;
        $rekapData      = [];

        if ($request->filled('jadwal_id')) {
            $selectedJadwal = Jadwal::with(['kelas', 'mataPelajaran'])->findOrFail($request->jadwal_id);
            $siswas         = Siswa::where('kelas_id', $selectedJadwal->kelas_id)
                                   ->where('status', 'aktif')
                                   ->orderBy('nama')
                                   ->get();

            foreach ($siswas as $siswa) {
                $abs = Absensi::where('jadwal_id', $selectedJadwal->id)
                              ->where('siswa_id', $siswa->id)
                              ->when($request->filled('bulan'), fn($q) => $q->whereMonth('tanggal', $request->bulan))
                              ->selectRaw('status, COUNT(*) as total')
                              ->groupBy('status')
                              ->pluck('total', 'status')
                              ->toArray();

                $rekapData[] = [
                    'siswa'  => $siswa,
                    'hadir'  => $abs['hadir']  ?? 0,
                    'izin'   => $abs['izin']   ?? 0,
                    'sakit'  => $abs['sakit']  ?? 0,
                    'alpha'  => $abs['alpha']  ?? 0,
                    'total'  => array_sum($abs),
                ];
            }
        }

        return view('guru.absensi.rekap', compact('jadwals', 'selectedJadwal', 'rekapData'));
    }
}
