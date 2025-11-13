<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Http\Requests\StoreSiswaRequest;
use App\Http\Requests\UpdateSiswaRequest;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::with(['kelas', 'tahunAjaran'])->latest()->get();
        return view('siswa.index', compact('siswas'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        $tahunAjarans = TahunAjaran::all();
        return view('siswa.create', compact('kelas', 'tahunAjarans'));
    }

    public function store(StoreSiswaRequest $request)
    {
        Siswa::create($request->validated());
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan');
    }

    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::all();
        $tahunAjarans = TahunAjaran::all();
        return view('siswa.edit', compact('siswa', 'kelas', 'tahunAjarans'));
    }

    public function update(UpdateSiswaRequest $request, Siswa $siswa)
    {
        $siswa->update($request->validated());
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diupdate');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus');
    }
}
