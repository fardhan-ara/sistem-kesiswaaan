<?php

namespace App\Http\Controllers;

use App\Models\JenisPrestasi;
use Illuminate\Http\Request;

class JenisPrestasiController extends Controller
{
    public function index()
    {
        $jenisPrestasiS = JenisPrestasi::latest()->paginate(10);
        return view('jenis-prestasi.index', compact('jenisPrestasiS'));
    }

    public function create()
    {
        return view('jenis-prestasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_prestasi' => 'required|string|max:255',
            'kategori' => 'required|in:akademik,non_akademik',
            'poin' => 'required|integer|min:1|max:100',
            'penghargaan' => 'nullable|string'
        ]);

        JenisPrestasi::create($request->all());
        return redirect()->route('jenis-prestasi.index')->with('success', 'Jenis prestasi berhasil ditambahkan!');
    }

    public function edit(JenisPrestasi $jenisPrestasi)
    {
        return view('jenis-prestasi.edit', compact('jenisPrestasi'));
    }

    public function update(Request $request, JenisPrestasi $jenisPrestasi)
    {
        $request->validate([
            'nama_prestasi' => 'required|string|max:255',
            'kategori' => 'required|in:akademik,non_akademik',
            'poin' => 'required|integer|min:1|max:100',
            'penghargaan' => 'nullable|string'
        ]);

        $jenisPrestasi->update($request->all());
        return redirect()->route('jenis-prestasi.index')->with('success', 'Jenis prestasi berhasil diupdate!');
    }

    public function destroy(JenisPrestasi $jenisPrestasi)
    {
        $jenisPrestasi->delete();
        return redirect()->route('jenis-prestasi.index')->with('success', 'Jenis prestasi berhasil dihapus!');
    }
}