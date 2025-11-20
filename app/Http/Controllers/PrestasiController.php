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
        $gurus = Guru::all();
        $jenisPrestasis = JenisPrestasi::orderBy('kategori')->orderBy('poin', 'desc')->get();
        return view('prestasi.create', compact('siswas', 'gurus', 'jenisPrestasis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'guru_pencatat' => 'required|exists:gurus,id',
            'jenis_prestasi_id' => 'required|exists:jenis_prestasis,id',
            'keterangan' => 'nullable|string'
        ]);

        $jenisPrestasi = JenisPrestasi::find($request->jenis_prestasi_id);

        Prestasi::create([
            'siswa_id' => $request->siswa_id,
            'guru_pencatat' => $request->guru_pencatat,
            'jenis_prestasi_id' => $request->jenis_prestasi_id,
            'poin' => $jenisPrestasi->poin,
            'keterangan' => $request->keterangan,
            'status_verifikasi' => 'menunggu'
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
        if (!in_array(auth()->user()->role, ['admin', 'kesiswaan'])) {
            abort(403, 'Unauthorized action.');
        }

        $prestasi->update([
            'status_verifikasi' => 'diverifikasi',
            'guru_verifikator' => auth()->user()->guru->id ?? null,
            'tanggal_verifikasi' => now()
        ]);
        
        return redirect()->back()->with('success', 'Prestasi berhasil diverifikasi');
    }

    public function reject(Prestasi $prestasi)
    {
        if (!in_array(auth()->user()->role, ['admin', 'kesiswaan'])) {
            abort(403, 'Unauthorized action.');
        }

        $prestasi->update([
            'status_verifikasi' => 'ditolak',
            'guru_verifikator' => auth()->user()->guru->id ?? null,
            'tanggal_verifikasi' => now()
        ]);
        
        return redirect()->back()->with('success', 'Prestasi ditolak');
    }
}
