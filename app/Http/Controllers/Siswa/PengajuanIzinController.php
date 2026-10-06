<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\PengajuanIzin;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class PengajuanIzinController extends Controller
{
    public function index()
    {
        $siswa      = auth()->user()->siswa;
        $pengajuans = PengajuanIzin::with('diprosesOleh')
                                   ->where('siswa_id', $siswa->id)
                                   ->latest()
                                   ->paginate(10);
        return view('siswa.pengajuan-izin.index', compact('pengajuans'));
    }

    public function create()
    {
        return view('siswa.pengajuan-izin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis'           => 'required|in:izin,sakit',
            'tanggal_mulai'   => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan'      => 'required|string|min:10|max:1000',
        ]);

        $siswa      = auth()->user()->siswa;
        $tahunAktif = TahunAjaran::aktif();

        PengajuanIzin::create([
            'siswa_id'        => $siswa->id,
            'tahun_ajaran_id' => $tahunAktif?->id,
            'jenis'           => $request->jenis,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'keterangan'      => $request->keterangan,
            'lampiran'        => null,
            'status'          => 'menunggu',
        ]);

        return redirect()->route('siswa.pengajuan-izin.index')
            ->with('success', 'Pengajuan izin berhasil dikirim. Menunggu persetujuan admin.');
    }
}
