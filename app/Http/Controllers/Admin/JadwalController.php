<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $query = Jadwal::with(['kelas', 'guru', 'mataPelajaran', 'tahunAjaran']);
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }
        if ($request->filled('guru_id')) {
            $query->where('guru_id', $request->guru_id);
        }
        if ($request->filled('tahun_ajaran_id')) {
            $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
        }

        $jadwals      = $query->orderByRaw("FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
                              ->orderBy('jam_mulai')->paginate(20)->withQueryString();
        $kelas        = Kelas::orderByRaw("FIELD(tingkat,'X','XI','XII')")->orderBy('nama')->get();
        $gurus        = Guru::orderBy('nama')->get();
        $tahunAjarans = TahunAjaran::orderBy('nama', 'desc')->get();

        return view('admin.jadwal.index', compact('jadwals', 'kelas', 'gurus', 'tahunAjarans'));
    }

    public function create()
    {
        $kelas        = Kelas::orderByRaw("FIELD(tingkat,'X','XI','XII')")->orderBy('nama')->get();
        $gurus        = Guru::where('status', 'aktif')->orderBy('nama')->get();
        $mapels       = MataPelajaran::where('status', 'aktif')->orderBy('nama')->get();
        $tahunAjarans = TahunAjaran::orderBy('nama', 'desc')->get();
        $jadwalData   = Jadwal::with('kelas')->where('status', 'aktif')->get()->map(fn($j) => [
            'id'             => $j->id,
            'guru_id'        => $j->guru_id,
            'hari'           => $j->hari,
            'jam_mulai'      => $j->jam_mulai,
            'jam_selesai'    => $j->jam_selesai,
            'ruangan'        => $j->ruangan,
            'tahun_ajaran_id'=> $j->tahun_ajaran_id,
            'status'         => $j->status,
            'kelas_nama'     => $j->kelas ? ($j->kelas->tingkat . ' ' . $j->kelas->nama) : '-',
        ]);
        return view('admin.jadwal.create', compact('kelas', 'gurus', 'mapels', 'tahunAjarans', 'jadwalData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id'          => 'required|exists:kelas,id',
            'guru_id'           => 'required|exists:gurus,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'tahun_ajaran_id'   => 'required|exists:tahun_ajarans,id',
            'hari'              => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai'         => 'required|date_format:H:i',
            'jam_selesai'       => 'required|date_format:H:i|after:jam_mulai',
        ]);

        // ── Cek bentrok guru ─────────────────────────────────────────────────
        $bentrokGuru = Jadwal::where('guru_id', $request->guru_id)
            ->where('hari', $request->hari)
            ->where('tahun_ajaran_id', $request->tahun_ajaran_id)
            ->where('status', 'aktif')
            ->where(function ($q) use ($request) {
                $q->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                  ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                  ->orWhere(function ($q2) use ($request) {
                      $q2->where('jam_mulai', '<=', $request->jam_mulai)
                         ->where('jam_selesai', '>=', $request->jam_selesai);
                  });
            })->first();

        if ($bentrokGuru) {
            return back()->withInput()->withErrors([
                'guru_id' => 'Guru ini sudah memiliki jadwal di hari ' . $request->hari
                    . ' jam ' . $bentrokGuru->jam_mulai . '–' . $bentrokGuru->jam_selesai
                    . ' (Kelas ' . $bentrokGuru->kelas->tingkat . ' ' . $bentrokGuru->kelas->nama . '). Pilih guru atau jam lain.',
            ]);
        }

        // ── Cek bentrok ruangan ───────────────────────────────────────────────
        if ($request->filled('ruangan')) {
            $bentrokRuangan = Jadwal::where('ruangan', $request->ruangan)
                ->where('hari', $request->hari)
                ->where('tahun_ajaran_id', $request->tahun_ajaran_id)
                ->where('status', 'aktif')
                ->where(function ($q) use ($request) {
                    $q->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhere(function ($q2) use ($request) {
                          $q2->where('jam_mulai', '<=', $request->jam_mulai)
                             ->where('jam_selesai', '>=', $request->jam_selesai);
                      });
                })->first();

            if ($bentrokRuangan) {
                return back()->withInput()->withErrors([
                    'ruangan' => 'Ruangan ' . $request->ruangan . ' sudah dipakai di hari ' . $request->hari
                        . ' jam ' . $bentrokRuangan->jam_mulai . '–' . $bentrokRuangan->jam_selesai
                        . '. Pilih ruangan atau jam lain.',
                ]);
            }
        }

        Jadwal::create($request->only([
            'kelas_id', 'guru_id', 'mata_pelajaran_id', 'tahun_ajaran_id',
            'hari', 'jam_mulai', 'jam_selesai', 'ruangan', 'status',
        ]));

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Jadwal $jadwal)
    {
        $kelas        = Kelas::orderByRaw("FIELD(tingkat,'X','XI','XII')")->orderBy('nama')->get();
        $gurus        = Guru::where('status', 'aktif')->orderBy('nama')->get();
        $mapels       = MataPelajaran::where('status', 'aktif')->orderBy('nama')->get();
        $tahunAjarans = TahunAjaran::orderBy('nama', 'desc')->get();
        $jadwalData   = Jadwal::with('kelas')->where('status', 'aktif')->where('id', '!=', $jadwal->id)->get()->map(fn($j) => [
            'id'             => $j->id,
            'guru_id'        => $j->guru_id,
            'hari'           => $j->hari,
            'jam_mulai'      => $j->jam_mulai,
            'jam_selesai'    => $j->jam_selesai,
            'ruangan'        => $j->ruangan,
            'tahun_ajaran_id'=> $j->tahun_ajaran_id,
            'status'         => $j->status,
            'kelas_nama'     => $j->kelas ? ($j->kelas->tingkat . ' ' . $j->kelas->nama) : '-',
        ]);
        return view('admin.jadwal.edit', compact('jadwal', 'kelas', 'gurus', 'mapels', 'tahunAjarans', 'jadwalData'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'kelas_id'          => 'required|exists:kelas,id',
            'guru_id'           => 'required|exists:gurus,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'tahun_ajaran_id'   => 'required|exists:tahun_ajarans,id',
            'hari'              => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai'         => 'required|date_format:H:i',
            'jam_selesai'       => 'required|date_format:H:i|after:jam_mulai',
        ]);

        // ── Cek bentrok guru (kecuali jadwal dirinya sendiri) ─────────────────
        $bentrokGuru = Jadwal::where('guru_id', $request->guru_id)
            ->where('hari', $request->hari)
            ->where('tahun_ajaran_id', $request->tahun_ajaran_id)
            ->where('status', 'aktif')
            ->where('id', '!=', $jadwal->id)
            ->where(function ($q) use ($request) {
                $q->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                  ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                  ->orWhere(function ($q2) use ($request) {
                      $q2->where('jam_mulai', '<=', $request->jam_mulai)
                         ->where('jam_selesai', '>=', $request->jam_selesai);
                  });
            })->first();

        if ($bentrokGuru) {
            return back()->withInput()->withErrors([
                'guru_id' => 'Guru ini sudah memiliki jadwal di hari ' . $request->hari
                    . ' jam ' . $bentrokGuru->jam_mulai . '–' . $bentrokGuru->jam_selesai
                    . ' (Kelas ' . $bentrokGuru->kelas->tingkat . ' ' . $bentrokGuru->kelas->nama . '). Pilih guru atau jam lain.',
            ]);
        }

        // ── Cek bentrok ruangan (kecuali jadwal dirinya sendiri) ─────────────
        if ($request->filled('ruangan')) {
            $bentrokRuangan = Jadwal::where('ruangan', $request->ruangan)
                ->where('hari', $request->hari)
                ->where('tahun_ajaran_id', $request->tahun_ajaran_id)
                ->where('status', 'aktif')
                ->where('id', '!=', $jadwal->id)
                ->where(function ($q) use ($request) {
                    $q->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhere(function ($q2) use ($request) {
                          $q2->where('jam_mulai', '<=', $request->jam_mulai)
                             ->where('jam_selesai', '>=', $request->jam_selesai);
                      });
                })->first();

            if ($bentrokRuangan) {
                return back()->withInput()->withErrors([
                    'ruangan' => 'Ruangan ' . $request->ruangan . ' sudah dipakai di hari ' . $request->hari
                        . ' jam ' . $bentrokRuangan->jam_mulai . '–' . $bentrokRuangan->jam_selesai
                        . '. Pilih ruangan atau jam lain.',
                ]);
            }
        }

        $jadwal->update($request->only([
            'kelas_id', 'guru_id', 'mata_pelajaran_id', 'tahun_ajaran_id',
            'hari', 'jam_mulai', 'jam_selesai', 'ruangan', 'status',
        ]));

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
