<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $query = Kelas::with(['tahunAjaran', 'siswas']);
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        $kelas = $query->orderByRaw("FIELD(tingkat,'X','XI','XII')")->orderBy('nama')->paginate(15)->withQueryString();
        return view('admin.kelas.index', compact('kelas'));
    }

    public function create()
    {
        $tahunAjarans = TahunAjaran::orderBy('nama', 'desc')->get();
        $gurus        = Guru::where('status', 'aktif')->orderBy('nama')->get();
        return view('admin.kelas.create', compact('tahunAjarans', 'gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:100',
            'tingkat' => 'required|string|max:20',
        ]);
        Kelas::create($request->only(['nama', 'tingkat', 'jurusan', 'wali_kelas', 'kapasitas', 'tahun_ajaran_id']));
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kela)
    {
        $tahunAjarans = TahunAjaran::orderBy('nama', 'desc')->get();
        $gurus        = Guru::where('status', 'aktif')->orderBy('nama')->get();
        return view('admin.kelas.edit', ['kelas' => $kela, 'tahunAjarans' => $tahunAjarans, 'gurus' => $gurus]);
    }

    public function update(Request $request, Kelas $kela)
    {
        $request->validate([
            'nama'    => 'required|string|max:100',
            'tingkat' => 'required|string|max:20',
        ]);
        $kela->update($request->only(['nama', 'tingkat', 'jurusan', 'wali_kelas', 'kapasitas', 'tahun_ajaran_id']));
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela)
    {
        $kela->delete();
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
