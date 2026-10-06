<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\Absensi;
use App\Models\TahunAjaran;
use App\Models\PengajuanIzin;

class DashboardController extends Controller
{
    public function index()
    {
        $tahunAktif = TahunAjaran::aktif();

        $stats = [
            'total_siswa'   => Siswa::where('status', 'aktif')->count(),
            'total_guru'    => Guru::where('status', 'aktif')->count(),
            'total_kelas'   => Kelas::count(),
            'total_mapel'   => \App\Models\MataPelajaran::where('status', 'aktif')->count(),
        ];

        $absensiHariIni = Absensi::whereDate('tanggal', today())
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $pengajuanMenunggu = PengajuanIzin::where('status', 'menunggu')->count();

        $recentAbsensi = Absensi::with(['siswa', 'jadwal.mataPelajaran', 'jadwal.kelas'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'absensiHariIni', 'pengajuanMenunggu', 'recentAbsensi', 'tahunAktif'
        ));
    }

    public function resetDemoData()
    {
        \Illuminate\Support\Facades\Artisan::call('demo:reset', ['--force' => true]);
        return redirect()->route('admin.dashboard')->with('success', 'Database demo berhasil di-reset ke kondisi awal yang bersih!');
    }
}
