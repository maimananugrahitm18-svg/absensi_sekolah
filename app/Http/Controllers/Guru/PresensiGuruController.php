<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiGuru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PresensiGuruController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;
        $today = today()->toDateString();

        $absensiHariIni = AbsensiGuru::where('guru_id', $guru->id)
            ->where('tanggal', $today)
            ->first();

        $riwayat = AbsensiGuru::where('guru_id', $guru->id)
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        return view('guru.presensi.index', compact('guru', 'absensiHariIni', 'riwayat'));
    }

    public function absenMasuk(Request $request)
    {
        $guru = auth()->user()->guru;
        $today = today()->toDateString();
        $nowTime = now()->format('H:i:s');

        $request->validate([
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Tentukan status (misal lewat jam 07:30 dianggap terlambat)
        $jamBatas = '07:30:00';
        $status = ($nowTime > $jamBatas) ? 'terlambat' : 'hadir';

        AbsensiGuru::updateOrCreate(
            ['guru_id' => $guru->id, 'tanggal' => $today],
            [
                'jam_masuk'  => $nowTime,
                'status'     => $status,
                'keterangan' => $request->keterangan,
            ]
        );

        $pesan = $status === 'terlambat' 
            ? 'Absen masuk berhasil dicatat pada ' . substr($nowTime, 0, 5) . ' (Terlambat).' 
            : 'Absen masuk tepat waktu berhasil dicatat pada ' . substr($nowTime, 0, 5) . '.';

        return redirect()->back()->with('success', $pesan);
    }

    public function absenPulang(Request $request)
    {
        $guru = auth()->user()->guru;
        $today = today()->toDateString();
        $nowTime = now()->format('H:i:s');

        $absensi = AbsensiGuru::where('guru_id', $guru->id)->where('tanggal', $today)->first();

        if (!$absensi || !$absensi->jam_masuk) {
            return redirect()->back()->with('error', 'Anda harus melakukan absen masuk terlebih dahulu sebelum absen pulang.');
        }

        $absensi->update([
            'jam_pulang'  => $nowTime,
        ]);

        return redirect()->back()->with('success', 'Absen pulang berhasil dicatat pada jam ' . substr($nowTime, 0, 5) . '. Selamat beristirahat!');
    }
}
