<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\TahunAjaran;

class DashboardController extends Controller
{
    public function index()
    {
        $siswa      = auth()->user()->siswa;
        $tahunAktif = TahunAjaran::aktif();

        $rekap = $siswa ? $siswa->getRekap() : [];
        $persentase = ($rekap['total'] ?? 0) > 0
            ? round(($rekap['hadir'] / $rekap['total']) * 100, 1)
            : 0;

        $riwayatTerbaru = Absensi::with(['jadwal.mataPelajaran'])
            ->where('siswa_id', $siswa?->id)
            ->latest('tanggal')
            ->take(5)
            ->get();

        return view('siswa.dashboard', compact('siswa', 'rekap', 'persentase', 'riwayatTerbaru', 'tahunAktif'));
    }
}
