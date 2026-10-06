<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with(['user', 'kelas']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%$s%")
                  ->orWhere('nis', 'like', "%$s%")
                  ->orWhere('nisn', 'like', "%$s%");
            });
        }
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $siswas = $query->orderBy('nama')->paginate(15)->withQueryString();
        $kelas  = Kelas::orderBy('nama')->get();

        return view('admin.siswa.index', compact('siswas', 'kelas'));
    }

    public function create()
    {
        $kelas = Kelas::orderBy('nama')->get();
        return view('admin.siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'           => 'required|string|max:255',
            'nis'            => 'nullable|unique:siswas,nis',
            'nisn'           => 'nullable|unique:siswas,nisn',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|min:6',
            'kelas_id'       => 'nullable|exists:kelas,id',
            'jenis_kelamin'  => 'nullable|in:L,P',
            'tanggal_lahir'  => 'nullable|date',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->nama,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'siswa',
            ]);

            Siswa::create([
                'user_id'        => $user->id,
                'nis'            => $request->nis,
                'nisn'           => $request->nisn,
                'nama'           => $request->nama,
                'kelas_id'       => $request->kelas_id,
                'jenis_kelamin'  => $request->jenis_kelamin,
                'tanggal_lahir'  => $request->tanggal_lahir,
                'tempat_lahir'   => $request->tempat_lahir,
                'no_hp'          => $request->no_hp,
                'no_hp_ortu'     => $request->no_hp_ortu,
                'alamat'         => $request->alamat,
                'status'         => $request->status ?? 'aktif',
            ]);
        });

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(Siswa $siswa)
    {
        $siswa->load(['user', 'kelas', 'absensis.jadwal.mataPelajaran']);
        $rekap = $siswa->getRekap();
        return view('admin.siswa.show', compact('siswa', 'rekap'));
    }

    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::orderBy('nama')->get();
        return view('admin.siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nama'           => 'required|string|max:255',
            'nis'            => 'nullable|unique:siswas,nis,' . $siswa->id,
            'nisn'           => 'nullable|unique:siswas,nisn,' . $siswa->id,
            'email'          => 'required|email|unique:users,email,' . $siswa->user_id,
            'kelas_id'       => 'nullable|exists:kelas,id',
            'jenis_kelamin'  => 'nullable|in:L,P',
            'tanggal_lahir'  => 'nullable|date',
        ]);

        DB::transaction(function () use ($request, $siswa) {
            $isDemo = $siswa->user && $siswa->user->email === 'siswa@gmail.com';
            $email = $isDemo ? 'siswa@gmail.com' : $request->email;

            $siswa->user->update([
                'name'  => $request->nama,
                'email' => $email,
            ]);

            if ($request->filled('password') && !$isDemo) {
                $siswa->user->update(['password' => Hash::make($request->password)]);
            }

            $siswa->update([
                'nis'           => $request->nis,
                'nisn'          => $request->nisn,
                'nama'          => $request->nama,
                'kelas_id'      => $request->kelas_id,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'tempat_lahir'  => $request->tempat_lahir,
                'no_hp'         => $request->no_hp,
                'no_hp_ortu'    => $request->no_hp_ortu,
                'alamat'        => $request->alamat,
                'status'        => $request->status,
            ]);
        });

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        if ($siswa->user && $siswa->user->email === 'siswa@gmail.com') {
            return back()->with('error', 'Akun Siswa Demo (siswa@gmail.com) dilindungi dan tidak dapat dihapus.');
        }

        DB::transaction(function () use ($siswa) {
            $siswa->user->delete();
        });
        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
