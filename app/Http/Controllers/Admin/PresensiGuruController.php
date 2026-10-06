<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbsensiGuru;
use App\Models\Guru;
use Illuminate\Http\Request;

class PresensiGuruController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->tanggal ?? today()->toDateString();

        $query = AbsensiGuru::with('guru')->where('tanggal', $tanggal);

        if ($request->filled('guru_id')) {
            $query->where('guru_id', $request->guru_id);
        }
        if ($request->filled('nama')) {
            $query->whereHas('guru', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->nama . '%');
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $presensi = $query->orderBy('jam_masuk', 'asc')->paginate(20)->withQueryString();
        $gurus = Guru::where('status', 'aktif')->orderBy('nama')->get();

        $totalGuru = Guru::where('status', 'aktif')->count();
        $hadirHariIni = AbsensiGuru::where('tanggal', $tanggal)->where('status', 'hadir')->count();
        $terlambatHariIni = AbsensiGuru::where('tanggal', $tanggal)->where('status', 'terlambat')->count();
        $izinSakitHariIni = AbsensiGuru::where('tanggal', $tanggal)->whereIn('status', ['izin', 'sakit'])->count();
        $sudahAbsen = AbsensiGuru::where('tanggal', $tanggal)->count();
        $belumAbsen = max(0, $totalGuru - $sudahAbsen);

        return view('admin.presensi-guru.index', compact(
            'presensi', 'gurus', 'tanggal', 'totalGuru',
            'hadirHariIni', 'terlambatHariIni', 'izinSakitHariIni', 'belumAbsen'
        ));
    }

    public function export(Request $request)
    {
        $tanggal = $request->tanggal ?? today()->toDateString();

        $query = AbsensiGuru::with('guru')->where('tanggal', $tanggal);

        if ($request->filled('guru_id')) {
            $query->where('guru_id', $request->guru_id);
        }
        if ($request->filled('nama')) {
            $query->whereHas('guru', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->nama . '%');
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $presensi = $query->orderBy('jam_masuk', 'asc')->get();

        $filename = 'rekap_presensi_guru_' . $tanggal . '.xls';

        return response()->view('admin.presensi-guru.excel', [
            'presensi' => $presensi,
            'tanggal'  => $tanggal,
        ])
        ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
        ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
        ->header('Cache-Control', 'max-age=0');
    }
}
