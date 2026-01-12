<?php

namespace App\Http\Controllers;

use App\Models\JenisPrestasi;
use Illuminate\Http\Request;

class JenisPrestasiController extends Controller
{
    public function index(Request $request)
    {
        $query = JenisPrestasi::query();
        
        if ($request->nama) {
            $query->where('nama_prestasi', 'like', '%' . $request->nama . '%');
        }
        
        if ($request->tingkat) {
            $query->where('tingkat', $request->tingkat);
        }
        
        if ($request->kategori_penampilan) {
            $query->where('kategori_penampilan', $request->kategori_penampilan);
        }
        
        $jenisPrestasiS = $query->latest()->paginate(10);
        
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
            'tingkat' => 'required|in:sekolah,kecamatan,kota,provinsi,nasional,internasional',
            'kategori_penampilan' => 'required|in:solo,duo,trio,grup,tim,kolektif',
            'poin_reward' => 'required|integer|min:1|max:100'
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
            'tingkat' => 'required|in:sekolah,kecamatan,kota,provinsi,nasional,internasional',
            'kategori_penampilan' => 'required|in:solo,duo,trio,grup,tim,kolektif',
            'poin_reward' => 'required|integer|min:1|max:100'
        ]);

        $oldPoin = $jenisPrestasi->poin_reward;
        
        $jenisPrestasi->update($request->all());
        
        // Sinkronisasi poin di tabel prestasi
        if ($oldPoin != $request->poin_reward) {
            \App\Models\Prestasi::where('jenis_prestasi_id', $jenisPrestasi->id)
                ->update(['poin' => $request->poin_reward]);
        }
        
        return redirect()->route('jenis-prestasi.index')->with('success', 'Jenis prestasi berhasil diupdate dan data prestasi tersinkronisasi!');
    }

    public function destroy(JenisPrestasi $jenisPrestasi)
    {
        // Cek apakah ada prestasi yang menggunakan jenis ini
        $jumlahPrestasi = \App\Models\Prestasi::where('jenis_prestasi_id', $jenisPrestasi->id)->count();
        
        if ($jumlahPrestasi > 0) {
            return redirect()->route('jenis-prestasi.index')
                ->with('error', "Tidak dapat menghapus! Jenis prestasi ini digunakan oleh {$jumlahPrestasi} data prestasi.");
        }
        
        $jenisPrestasi->delete();
        return redirect()->route('jenis-prestasi.index')->with('success', 'Jenis prestasi berhasil dihapus!');
    }
}