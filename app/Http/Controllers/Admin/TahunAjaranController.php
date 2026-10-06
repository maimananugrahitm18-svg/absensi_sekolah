<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjarans = TahunAjaran::latest()->paginate(15);
        return view('admin.tahun-ajaran.index', compact('tahunAjarans'));
    }

    public function create()
    {
        return view('admin.tahun-ajaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'            => 'required|string|max:20',
            'semester'        => 'required|in:ganjil,genap',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        if ($request->boolean('is_aktif')) {
            TahunAjaran::where('is_aktif', true)->update(['is_aktif' => false]);
        }

        TahunAjaran::create($request->only(['nama', 'semester', 'tanggal_mulai', 'tanggal_selesai', 'is_aktif']));
        return redirect()->route('admin.tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function edit(TahunAjaran $tahunAjaran)
    {
        return view('admin.tahun-ajaran.edit', compact('tahunAjaran'));
    }

    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        $request->validate([
            'nama'            => 'required|string|max:20',
            'semester'        => 'required|in:ganjil,genap',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        DB::transaction(function () use ($request, $tahunAjaran) {
            if ($request->boolean('is_aktif')) {
                TahunAjaran::where('is_aktif', true)->where('id', '!=', $tahunAjaran->id)->update(['is_aktif' => false]);
            }
            $tahunAjaran->update($request->only(['nama', 'semester', 'tanggal_mulai', 'tanggal_selesai', 'is_aktif']));
        });

        return redirect()->route('admin.tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroy(TahunAjaran $tahunAjaran)
    {
        $tahunAjaran->delete();
        return redirect()->route('admin.tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil dihapus.');
    }
}
