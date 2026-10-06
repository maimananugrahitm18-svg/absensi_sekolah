<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanIzin;
use App\Models\Kelas;
use Illuminate\Http\Request;

class PengajuanIzinController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::orderBy('tingkat')->orderBy('nama')->get();
        $query = PengajuanIzin::with(['siswa.kelas', 'diprosesOleh']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }
        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        $pengajuans = $query->latest()->paginate(15)->withQueryString();
        return view('admin.pengajuan-izin.index', compact('pengajuans', 'kelas'));
    }

    public function show(PengajuanIzin $pengajuanIzin)
    {
        $pengajuanIzin->load(['siswa.kelas', 'diprosesOleh']);
        return view('admin.pengajuan-izin.show', compact('pengajuanIzin'));
    }

    public function approve(PengajuanIzin $pengajuanIzin)
    {
        $pengajuanIzin->update([
            'status'       => 'disetujui',
            'diproses_oleh' => auth()->id(),
            'diproses_pada' => now(),
        ]);
        return redirect()->back()->with('success', 'Pengajuan izin disetujui.');
    }

    public function reject(Request $request, PengajuanIzin $pengajuanIzin)
    {
        $request->validate(['catatan_admin' => 'required|string|max:500']);
        $pengajuanIzin->update([
            'status'         => 'ditolak',
            'catatan_admin'  => $request->catatan_admin,
            'diproses_oleh'  => auth()->id(),
            'diproses_pada'  => now(),
        ]);
        return redirect()->back()->with('success', 'Pengajuan izin ditolak.');
    }
}
