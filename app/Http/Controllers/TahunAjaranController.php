<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjarans = TahunAjaran::latest()->paginate(10);
        return view('tahun-ajaran.index', compact('tahunAjarans'));
    }

    public function create()
    {
        return view('tahun-ajaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|max:255|unique:tahun_ajarans,tahun_ajaran',
            'tahun_mulai' => 'required|integer|min:2020|max:2050',
            'tahun_selesai' => 'required|integer|min:2020|max:2050|gt:tahun_mulai',
            'semester' => 'required|in:ganjil,genap',
            'status_aktif' => 'required|in:aktif,nonaktif'
        ]);

        if ($request->status_aktif === 'aktif') {
            TahunAjaran::where('status_aktif', 'aktif')->update(['status_aktif' => 'nonaktif']);
        }

        TahunAjaran::create($request->all());
        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil ditambahkan!');
    }

    public function edit(TahunAjaran $tahunAjaran)
    {
        return view('tahun-ajaran.edit', compact('tahunAjaran'));
    }

    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|max:255|unique:tahun_ajarans,tahun_ajaran,' . $tahunAjaran->id,
            'tahun_mulai' => 'required|integer|min:2020|max:2050',
            'tahun_selesai' => 'required|integer|min:2020|max:2050|gt:tahun_mulai',
            'semester' => 'required|in:ganjil,genap',
            'status_aktif' => 'required|in:aktif,nonaktif'
        ]);

        if ($request->status_aktif === 'aktif' && $tahunAjaran->status_aktif !== 'aktif') {
            TahunAjaran::where('status_aktif', 'aktif')->update(['status_aktif' => 'nonaktif']);
        }

        $tahunAjaran->update($request->all());
        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil diupdate!');
    }

    public function destroy(TahunAjaran $tahunAjaran)
    {
        $tahunAjaran->delete();
        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil dihapus!');
    }
}