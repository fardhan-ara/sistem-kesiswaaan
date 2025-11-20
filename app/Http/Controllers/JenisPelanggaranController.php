<?php

namespace App\Http\Controllers;

use App\Models\JenisPelanggaran;
use Illuminate\Http\Request;

class JenisPelanggaranController extends Controller
{
    public function index()
    {
        $jenisPelanggarans = JenisPelanggaran::latest()->paginate(10);
        return view('jenis-pelanggaran.index', compact('jenisPelanggarans'));
    }

    public function create()
    {
        return view('jenis-pelanggaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelompok' => 'required|string|max:255',
            'nama_pelanggaran' => 'required|string|max:255',
            'kategori' => 'required|in:ringan,sedang,berat,sangat_berat',
            'poin' => 'required|integer|min:1|max:100',
            'sanksi_rekomendasi' => 'nullable|string'
        ]);

        JenisPelanggaran::create($request->all());
        return redirect()->route('jenis-pelanggaran.index')->with('success', 'Jenis pelanggaran berhasil ditambahkan!');
    }

    public function edit(JenisPelanggaran $jenisPelanggaran)
    {
        return view('jenis-pelanggaran.edit', compact('jenisPelanggaran'));
    }

    public function update(Request $request, JenisPelanggaran $jenisPelanggaran)
    {
        $request->validate([
            'kelompok' => 'required|string|max:255',
            'nama_pelanggaran' => 'required|string|max:255',
            'kategori' => 'required|in:ringan,sedang,berat,sangat_berat',
            'poin' => 'required|integer|min:1|max:100',
            'sanksi_rekomendasi' => 'nullable|string'
        ]);

        $jenisPelanggaran->update($request->all());
        return redirect()->route('jenis-pelanggaran.index')->with('success', 'Jenis pelanggaran berhasil diupdate!');
    }

    public function destroy(JenisPelanggaran $jenisPelanggaran)
    {
        $jenisPelanggaran->delete();
        return redirect()->route('jenis-pelanggaran.index')->with('success', 'Jenis pelanggaran berhasil dihapus!');
    }
}