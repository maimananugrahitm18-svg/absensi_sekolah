<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class RiwayatAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $siswa        = auth()->user()->siswa;
        $tahunAjarans = TahunAjaran::orderBy('nama', 'desc')->get();

        $query = Absensi::with(['jadwal.mataPelajaran', 'jadwal.kelas'])
            ->where('siswa_id', $siswa->id);

        if ($request->filled('tahun_ajaran_id')) {
            $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
        }
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $riwayat = $query->latest('tanggal')->paginate(20)->withQueryString();
        $rekap   = $siswa->getRekap();

        return view('siswa.riwayat', compact('riwayat', 'rekap', 'tahunAjarans'));
    }
}
