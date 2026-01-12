<?php

namespace App\Http\Controllers;

use App\Models\JenisPelanggaran;
use Illuminate\Http\Request;

class JenisPelanggaranController extends Controller
{
    public function index(Request $request)
    {
        $query = JenisPelanggaran::query();
        
        if ($request->nama) {
            $query->where('nama_pelanggaran', 'like', '%' . $request->nama . '%');
        }
        
        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }
        
        $jenisPelanggarans = $query->latest()->paginate(10);
        
        return view('jenis-pelanggaran.index', compact('jenisPelanggarans'));
    }

    public function create()
    {
        return view('jenis-pelanggaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|in:ketertiban,kehadiran,pakaian,sikap,akademik,fasilitas,kriminal',
            'nama_pelanggaran' => 'required|string',
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
            'kategori' => 'required|in:ketertiban,kehadiran,pakaian,sikap,akademik,fasilitas,kriminal',
            'nama_pelanggaran' => 'required|string',
            'poin' => 'required|integer|min:1|max:100',
            'sanksi_rekomendasi' => 'nullable|string'
        ]);

        $oldPoin = $jenisPelanggaran->poin;
        
        $jenisPelanggaran->update($request->all());
        
        // Sinkronisasi poin di tabel pelanggaran
        if ($oldPoin != $request->poin) {
            \App\Models\Pelanggaran::where('jenis_pelanggaran_id', $jenisPelanggaran->id)
                ->update(['poin' => $request->poin]);
        }
        
        return redirect()->route('jenis-pelanggaran.index')->with('success', 'Jenis pelanggaran berhasil diupdate dan data pelanggaran tersinkronisasi!');
    }

    public function destroy(JenisPelanggaran $jenisPelanggaran)
    {
        // Cek apakah ada pelanggaran yang menggunakan jenis ini
        $jumlahPelanggaran = \App\Models\Pelanggaran::where('jenis_pelanggaran_id', $jenisPelanggaran->id)->count();
        
        if ($jumlahPelanggaran > 0) {
            return redirect()->route('jenis-pelanggaran.index')
                ->with('error', "Tidak dapat menghapus! Jenis pelanggaran ini digunakan oleh {$jumlahPelanggaran} data pelanggaran.");
        }
        
        $jenisPelanggaran->delete();
        return redirect()->route('jenis-pelanggaran.index')->with('success', 'Jenis pelanggaran berhasil dihapus!');
    }
    
    public function usage($id)
    {
        $pelanggarans = \App\Models\Pelanggaran::where('jenis_pelanggaran_id', $id)
            ->with('siswa.kelas')
            ->get();
        
        $siswas = $pelanggarans->map(function($p) {
            return [
                'nama_siswa' => $p->siswa->nama_siswa ?? '-',
                'kelas' => $p->siswa->kelas->nama_kelas ?? '-'
            ];
        })->unique('nama_siswa')->values();
        
        return response()->json([
            'count' => $pelanggarans->count(),
            'siswas' => $siswas
        ]);
    }
}