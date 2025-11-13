<?php

namespace App\Http\Controllers;

use App\Models\Prestasi;
use App\Models\Siswa;
use App\Models\JenisPrestasi;
use Illuminate\Http\Request;
use App\Http\Requests\StorePrestasiRequest;

class PrestasiController extends Controller
{
    public function index()
    {
        $prestasis = Prestasi::with(['siswa.kelas', 'jenisPrestasi'])->latest()->get();
        return view('prestasi.index', compact('prestasis'));
    }

    public function create()
    {
        $siswas = Siswa::with('kelas')->get();
        $jenisPrestasis = JenisPrestasi::all();
        return view('prestasi.create', compact('siswas', 'jenisPrestasis'));
    }

    public function store(StorePrestasiRequest $request)
    {
        $validated = $request->validated();

        Prestasi::create([
            'siswa_id' => $validated['siswa_id'],
            'jenis_prestasi_id' => $validated['jenis_prestasi_id'],
            'keterangan' => $validated['keterangan'] ?? null,
            'status_verifikasi' => 'pending'
        ]);

        return redirect()->route('prestasi.index')->with('success', 'Data prestasi berhasil ditambahkan');
    }

    public function edit(Prestasi $prestasi)
    {
        $siswas = Siswa::with('kelas')->get();
        $jenisPrestasis = JenisPrestasi::all();
        return view('prestasi.edit', compact('prestasi', 'siswas', 'jenisPrestasis'));
    }

    public function update(StorePrestasiRequest $request, Prestasi $prestasi)
    {
        $prestasi->update($request->validated());
        return redirect()->route('prestasi.index')->with('success', 'Data prestasi berhasil diupdate');
    }

    public function destroy(Prestasi $prestasi)
    {
        $prestasi->delete();
        return redirect()->route('prestasi.index')->with('success', 'Data prestasi berhasil dihapus');
    }

    public function verify(Prestasi $prestasi)
    {
        $prestasi->update(['status_verifikasi' => 'verified']);
        return redirect()->route('prestasi.index')->with('success', 'Prestasi berhasil diverifikasi');
    }

    public function reject(Prestasi $prestasi)
    {
        $prestasi->update(['status_verifikasi' => 'rejected']);
        return redirect()->route('prestasi.index')->with('success', 'Prestasi ditolak');
    }
}
