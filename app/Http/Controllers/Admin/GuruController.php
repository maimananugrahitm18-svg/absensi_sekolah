<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $query = Guru::with('user');
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%$s%")->orWhere('nip', 'like', "%$s%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $gurus = $query->orderBy('nama')->paginate(15)->withQueryString();
        return view('admin.guru.index', compact('gurus'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'nip'      => 'nullable|unique:gurus,nip',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->nama,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'guru',
            ]);

            Guru::create([
                'user_id'              => $user->id,
                'nip'                  => $request->nip,
                'nama'                 => $request->nama,
                'jenis_kelamin'        => $request->jenis_kelamin,
                'no_hp'                => $request->no_hp,
                'alamat'               => $request->alamat,
                'pendidikan_terakhir'  => $request->pendidikan_terakhir,
                'status'               => $request->status ?? 'aktif',
            ]);
        });

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function show(Guru $guru)
    {
        $guru->load(['user', 'jadwals.mataPelajaran', 'jadwals.kelas']);
        return view('admin.guru.show', compact('guru'));
    }

    public function edit(Guru $guru)
    {
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nama'  => 'required|string|max:255',
            'nip'   => 'nullable|unique:gurus,nip,' . $guru->id,
            'email' => 'required|email|unique:users,email,' . $guru->user_id,
        ]);

        DB::transaction(function () use ($request, $guru) {
            $isDemo = $guru->user && $guru->user->email === 'guru@gmail.com';
            $email = $isDemo ? 'guru@gmail.com' : $request->email;

            $guru->user->update(['name' => $request->nama, 'email' => $email]);
            if ($request->filled('password') && !$isDemo) {
                $guru->user->update(['password' => Hash::make($request->password)]);
            }
            $guru->update([
                'nip'                 => $request->nip,
                'nama'                => $request->nama,
                'jenis_kelamin'       => $request->jenis_kelamin,
                'no_hp'               => $request->no_hp,
                'alamat'              => $request->alamat,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'status'              => $request->status,
            ]);
        });

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru)
    {
        if ($guru->user && $guru->user->email === 'guru@gmail.com') {
            return back()->with('error', 'Akun Guru Demo (guru@gmail.com) dilindungi dan tidak dapat dihapus.');
        }

        DB::transaction(fn() => $guru->user->delete());
        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil dihapus.');
    }
}
