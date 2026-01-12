<?php

namespace App\Http\Controllers;

use App\Models\BimbinganKonseling;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Http\Request;

class BimbinganKonselingController extends Controller
{
    public function index(Request $request)
    {
        $query = BimbinganKonseling::with(['siswa.kelas', 'guru']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        if ($request->filled('siswa')) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('nama_siswa', 'like', '%' . $request->siswa . '%');
            });
        }
        if ($request->filled('guru')) {
            $query->whereHas('guru', function($q) use ($request) {
                $q->where('nama_guru', 'like', '%' . $request->guru . '%');
            });
        }
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }
        
        $bimbingans = $query->latest()->paginate(10)->withQueryString();
        return view('bk.index', compact('bimbingans'));
    }

    public function create()
    {
        $siswas = Siswa::with('kelas')->orderBy('nama_siswa')->get();
        $gurus = Guru::whereHas('user', function($q) {
            $q->where('role', 'bk');
        })->orWhere('bidang_studi', 'LIKE', '%BK%')
        ->orWhere('bidang_studi', 'LIKE', '%Bimbingan Konseling%')
        ->orderBy('nama_guru')->get();
        
        return view('bk.create', compact('siswas', 'gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'guru_id' => 'required|exists:gurus,id',
            'kategori' => 'required|in:pribadi,sosial,belajar,karir',
            'tanggal' => 'required|date',
            'catatan' => 'required|string',
            'status' => 'required|in:selesai,proses,terjadwal'
        ]);

        BimbinganKonseling::create($request->all());
        return redirect()->route('bk.index')->with('success', 'Data bimbingan konseling berhasil ditambahkan');
    }

    public function show(BimbinganKonseling $bk)
    {
        $bk->load(['siswa.kelas', 'guru']);
        return response()->json($bk);
    }

    public function edit(BimbinganKonseling $bk)
    {
        $siswas = Siswa::with('kelas')->orderBy('nama_siswa')->get();
        $gurus = Guru::whereHas('user', function($q) {
            $q->where('role', 'bk');
        })->orWhere('bidang_studi', 'LIKE', '%BK%')
        ->orWhere('bidang_studi', 'LIKE', '%Bimbingan Konseling%')
        ->orderBy('nama_guru')->get();
        
        return view('bk.edit', compact('bk', 'siswas', 'gurus'));
    }

    public function update(Request $request, BimbinganKonseling $bk)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'guru_id' => 'required|exists:gurus,id',
            'kategori' => 'required|in:pribadi,sosial,belajar,karir',
            'tanggal' => 'required|date',
            'catatan' => 'required|string',
            'status' => 'required|in:selesai,proses,terjadwal'
        ]);

        $bk->update($request->all());
        return redirect()->route('bk.index')->with('success', 'Data bimbingan konseling berhasil diupdate');
    }

    public function destroy(BimbinganKonseling $bk)
    {
        $bk->delete();
        return redirect()->route('bk.index')->with('success', 'Data bimbingan konseling berhasil dihapus');
    }
}
