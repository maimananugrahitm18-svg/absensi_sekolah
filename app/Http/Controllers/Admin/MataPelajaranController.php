<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index(Request $request)
    {
        $query = MataPelajaran::query();
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%'.$request->search.'%')
                  ->orWhere('kode', 'like', '%'.$request->search.'%');
        }
        $mapels = $query->orderBy('nama')->paginate(15)->withQueryString();
        return view('admin.mapel.index', compact('mapels'));
    }

    public function create()
    {
        return view('admin.mapel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:mata_pelajarans,kode',
            'nama' => 'required|string|max:255',
            'sks'  => 'nullable|integer|min:1|max:10',
        ]);
        MataPelajaran::create($request->only(['kode', 'nama', 'deskripsi', 'sks', 'status']));
        return redirect()->route('admin.mapel.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(MataPelajaran $mapel)
    {
        return view('admin.mapel.edit', compact('mapel'));
    }

    public function update(Request $request, MataPelajaran $mapel)
    {
        $request->validate([
            'kode' => 'required|unique:mata_pelajarans,kode,' . $mapel->id,
            'nama' => 'required|string|max:255',
        ]);
        $mapel->update($request->only(['kode', 'nama', 'deskripsi', 'sks', 'status']));
        return redirect()->route('admin.mapel.index')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(MataPelajaran $mapel)
    {
        $mapel->delete();
        return redirect()->route('admin.mapel.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
