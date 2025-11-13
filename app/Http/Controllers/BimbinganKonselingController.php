<?php

namespace App\Http\Controllers;

use App\Models\BimbinganKonseling;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Http\Request;

class BimbinganKonselingController extends Controller
{
    public function index()
    {
        $bimbingans = BimbinganKonseling::with(['siswa.kelas', 'guru'])->latest()->get();
        return view('bk.index', compact('bimbingans'));
    }

    public function create()
    {
        $siswas = Siswa::with('kelas')->get();
        $gurus = Guru::all();
        return view('bk.create', compact('siswas', 'gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'guru_id' => 'required|exists:gurus,id',
            'catatan' => 'required',
            'tanggal' => 'required|date',
            'status' => 'required'
        ]);

        BimbinganKonseling::create($request->all());
        return redirect()->route('bk.index')->with('success', 'Data bimbingan konseling berhasil ditambahkan');
    }
}
