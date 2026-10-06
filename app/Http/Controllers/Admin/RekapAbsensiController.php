<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class RekapAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $tahunAjarans  = TahunAjaran::orderBy('nama', 'desc')->get();
        $kelas         = Kelas::orderBy('tingkat')->orderBy('nama')->get();
        $tahunAktif    = TahunAjaran::aktif();

        $tahunAjaranId = $request->tahun_ajaran_id ?? $tahunAktif?->id;
        $bulan         = $request->bulan;
        $kelasId       = $request->kelas_id;

        $query = Siswa::with(['kelas', 'absensis' => function ($q) use ($tahunAjaranId, $bulan) {
            if ($tahunAjaranId) {
                $q->where('tahun_ajaran_id', $tahunAjaranId);
            }
            if ($bulan) {
                $q->whereMonth('tanggal', $bulan);
            }
        }])->where('status', 'aktif');

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        $siswas = $query->orderBy('nama')->get();
        $rekapPerKelas = $this->buildRekapPerKelas($tahunAjaranId, $bulan, $kelasId);
        $totalSiswaSemua = $siswas->count();

        return view('admin.rekap.index', compact(
            'siswas',
            'rekapPerKelas',
            'tahunAjarans',
            'kelas',
            'tahunAktif',
            'totalSiswaSemua'
        ));
    }

    public function exportCsv(Request $request)
    {
        $tahunAjaranId = $request->tahun_ajaran_id ?? TahunAjaran::aktif()?->id;
        $kelasId       = $request->kelas_id;
        $bulan         = $request->bulan;

        $tahunAjaran   = TahunAjaran::find($tahunAjaranId);
        $kelasFilter   = $kelasId ? Kelas::find($kelasId) : null;

        $rekapPerKelas = $this->buildRekapPerKelas($tahunAjaranId, $bulan, $kelasId);

        $filename = 'Rekap_Absensi_' . ($kelasFilter ? str_replace(' ', '_', $kelasFilter->nama_lengkap) . '_' : 'Per_Kelas_') . date('Ymd_His') . '.xls';

        $content = view('admin.rekap.excel', compact('rekapPerKelas', 'tahunAjaran', 'kelasFilter', 'bulan'))->render();

        return response($content, 200, [
            'Content-Type'        => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    /**
     * Build attendance recap grouped strictly per class.
     */
    private function buildRekapPerKelas(?int $tahunAjaranId, ?string $bulan, ?string $kelasId): array
    {
        $allKelas = Kelas::orderBy('tingkat')->orderBy('nama')->get();

        $query = Siswa::with(['kelas', 'absensis' => function ($q) use ($tahunAjaranId, $bulan) {
            if ($tahunAjaranId) {
                $q->where('tahun_ajaran_id', $tahunAjaranId);
            }
            if ($bulan) {
                $q->whereMonth('tanggal', $bulan);
            }
        }])->where('status', 'aktif');

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        $allSiswas = $query->orderBy('nama')->get();

        $rekapPerKelas = [];
        $targetKelas   = $kelasId ? $allKelas->where('id', $kelasId) : $allKelas;

        foreach ($targetKelas as $k) {
            $siswasInKelas = $allSiswas->where('kelas_id', $k->id);
            if ($siswasInKelas->isEmpty() && $kelasId != $k->id) {
                continue;
            }

            $sumHadir = 0;
            $sumIzin  = 0;
            $sumSakit = 0;
            $sumAlpha = 0;

            $items = [];
            foreach ($siswasInKelas as $siswa) {
                $hadir = $siswa->absensis->where('status', 'hadir')->count();
                $izin  = $siswa->absensis->where('status', 'izin')->count();
                $sakit = $siswa->absensis->where('status', 'sakit')->count();
                $alpha = $siswa->absensis->where('status', 'alpha')->count();
                $total = $hadir + $izin + $sakit + $alpha;
                $pct   = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

                $sumHadir += $hadir;
                $sumIzin  += $izin;
                $sumSakit += $sakit;
                $sumAlpha += $alpha;

                $items[] = [
                    'siswa' => $siswa,
                    'hadir' => $hadir,
                    'izin'  => $izin,
                    'sakit' => $sakit,
                    'alpha' => $alpha,
                    'total' => $total,
                    'pct'   => $pct,
                ];
            }

            $totalSemua = $sumHadir + $sumIzin + $sumSakit + $sumAlpha;
            $avgPct = $totalSemua > 0 ? round(($sumHadir / $totalSemua) * 100, 1) : 0;

            $rekapPerKelas[] = [
                'kelas'       => $k,
                'items'       => $items,
                'total_siswa' => count($items),
                'sum_hadir'   => $sumHadir,
                'sum_izin'    => $sumIzin,
                'sum_sakit'   => $sumSakit,
                'sum_alpha'   => $sumAlpha,
                'total_semua' => $totalSemua,
                'avg_pct'     => $avgPct,
            ];
        }

        // Siswa tanpa kelas (jika ada dan tidak difilter kelas tertentu)
        if (!$kelasId) {
            $siswasTanpaKelas = $allSiswas->whereNull('kelas_id');
            if ($siswasTanpaKelas->isNotEmpty()) {
                $sumHadir = 0; $sumIzin = 0; $sumSakit = 0; $sumAlpha = 0;
                $items = [];
                foreach ($siswasTanpaKelas as $siswa) {
                    $hadir = $siswa->absensis->where('status', 'hadir')->count();
                    $izin  = $siswa->absensis->where('status', 'izin')->count();
                    $sakit = $siswa->absensis->where('status', 'sakit')->count();
                    $alpha = $siswa->absensis->where('status', 'alpha')->count();
                    $total = $hadir + $izin + $sakit + $alpha;
                    $pct   = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

                    $sumHadir += $hadir;
                    $sumIzin  += $izin;
                    $sumSakit += $sakit;
                    $sumAlpha += $alpha;

                    $items[] = [
                        'siswa' => $siswa,
                        'hadir' => $hadir,
                        'izin'  => $izin,
                        'sakit' => $sakit,
                        'alpha' => $alpha,
                        'total' => $total,
                        'pct'   => $pct,
                    ];
                }

                $totalSemua = $sumHadir + $sumIzin + $sumSakit + $sumAlpha;
                $avgPct = $totalSemua > 0 ? round(($sumHadir / $totalSemua) * 100, 1) : 0;

                $rekapPerKelas[] = [
                    'kelas'       => null,
                    'items'       => $items,
                    'total_siswa' => count($items),
                    'sum_hadir'   => $sumHadir,
                    'sum_izin'    => $sumIzin,
                    'sum_sakit'   => $sumSakit,
                    'sum_alpha'   => $sumAlpha,
                    'total_semua' => $totalSemua,
                    'avg_pct'     => $avgPct,
                ];
            }
        }

        return $rekapPerKelas;
    }
}
