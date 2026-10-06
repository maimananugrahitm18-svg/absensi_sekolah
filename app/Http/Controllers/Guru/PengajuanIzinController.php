<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PengajuanIzin;
use App\Models\Kelas;
use Illuminate\Http\Request;

class PengajuanIzinController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::orderByRaw("FIELD(tingkat,'X','XI','XII')")->orderBy('nama')->get();
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

        $pendingCount = PengajuanIzin::where('status', 'menunggu')->count();
        $pengajuans   = $query->latest()->paginate(15)->withQueryString();

        return view('guru.pengajuan-izin.index', compact('pengajuans', 'kelas', 'pendingCount'));
    }

    public function show(PengajuanIzin $pengajuanIzin)
    {
        $pengajuanIzin->load(['siswa.kelas', 'diprosesOleh']);
        return view('guru.pengajuan-izin.show', compact('pengajuanIzin'));
    }

    public function approve(PengajuanIzin $pengajuanIzin)
    {
        $namaSiswa = $pengajuanIzin->siswa?->nama ?? 'Siswa';
        $pengajuanIzin->update([
            'status'        => 'disetujui',
            'diproses_oleh' => auth()->id(),
            'diproses_pada' => now(),
        ]);

        return redirect()->route('guru.pengajuan-izin.index')
            ->with('success', "Pengajuan izin atas nama {$namaSiswa} berhasil DISETUJUI.");
    }

    public function reject(Request $request, PengajuanIzin $pengajuanIzin)
    {
        $request->validate([
            'catatan' => 'required|string|max:500'
        ], [
            'catatan.required' => 'Alasan / catatan penolakan wajib diisi.'
        ]);

        $namaSiswa = $pengajuanIzin->siswa?->nama ?? 'Siswa';
        $pengajuanIzin->update([
            'status'        => 'ditolak',
            'catatan_admin' => $request->catatan,
            'diproses_oleh' => auth()->id(),
            'diproses_pada' => now(),
        ]);

        return redirect()->route('guru.pengajuan-izin.index')
            ->with('success', "Pengajuan izin atas nama {$namaSiswa} telah DITOLAK.");
    }
}
