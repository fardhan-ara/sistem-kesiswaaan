<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Guru;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with(['waliKelas.user', 'tahunAjaran'])->latest()->paginate(10);
        return view('kelas.index', compact('kelas'));
    }

    public function create()
    {
        $gurus = Guru::all();
        $tahunAjarans = TahunAjaran::all();
        return view('kelas.create', compact('gurus', 'tahunAjarans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'wali_kelas_id' => 'nullable|exists:gurus,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id'
        ]);

        Kelas::create($request->all());
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function edit(Kelas $kelas)
    {
        $gurus = Guru::all();
        $tahunAjarans = TahunAjaran::all();
        return view('kelas.edit', compact('kelas', 'gurus', 'tahunAjarans'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'wali_kelas_id' => 'nullable|exists:gurus,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id'
        ]);

        $kelas->update($request->all());
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diupdate!');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus!');
    }
}