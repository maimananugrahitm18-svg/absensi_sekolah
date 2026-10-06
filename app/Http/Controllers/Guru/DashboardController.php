<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\TahunAjaran;

class DashboardController extends Controller
{
    public function index()
    {
        $guru       = auth()->user()->guru;
        $tahunAktif = TahunAjaran::aktif();

        $jadwalsHariIni = Jadwal::with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guru?->id)
            ->where('hari', now()->locale('id')->isoFormat('dddd'))
            ->where('status', 'aktif')
            ->orderBy('jam_mulai')
            ->get();

        $totalAbsensiDiisi = Absensi::where('guru_id', $guru?->id)
            ->whereDate('tanggal', today())
            ->count();

        $totalJadwal = Jadwal::where('guru_id', $guru?->id)
            ->where('tahun_ajaran_id', $tahunAktif?->id)
            ->where('status', 'aktif')
            ->count();

        $izinMenunggu = \App\Models\PengajuanIzin::where('status', 'menunggu')->count();

        return view('guru.dashboard', compact('guru', 'jadwalsHariIni', 'totalAbsensiDiisi', 'totalJadwal', 'tahunAktif', 'izinMenunggu'));
    }
}
