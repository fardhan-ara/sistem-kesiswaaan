<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaApiController extends Controller
{
    public function index()
    {
        $siswas = Siswa::with(['kelas', 'tahunAjaran'])->get();
        return response()->json($siswas);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|unique:siswas',
            'nama_siswa' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required|in:L,P',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id'
        ]);

        $siswa = Siswa::create($validated);
        return response()->json($siswa, 201);
    }

    public function show(Siswa $siswa)
    {
        return response()->json($siswa->load(['kelas', 'tahunAjaran']));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nis' => 'required|unique:siswas,nis,' . $siswa->id,
            'nama_siswa' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required|in:L,P',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id'
        ]);

        $siswa->update($validated);
        return response()->json($siswa);
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return response()->json(['message' => 'Siswa deleted successfully']);
    }
}
