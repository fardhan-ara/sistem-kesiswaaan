<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Guru;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $query = Kelas::with(['waliKelas', 'tahunAjaran']);
        
        if ($request->nama_kelas) {
            $query->where('nama_kelas', 'like', '%' . $request->nama_kelas . '%');
        }
        
        if ($request->jurusan) {
            $query->where('jurusan', 'like', '%' . $request->jurusan . '%');
        }
        
        $kelas = $query->latest()->paginate(10);
        return view('kelas.index', compact('kelas'));
    }

    public function create()
    {
        $gurus = Guru::with('user')
            ->whereHas('user', function($query) {
                $query->where(function($q) {
                    $q->where('role', 'guru')
                      ->orWhereJsonContains('additional_roles', 'guru');
                });
            })
            ->get();
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

        // Validasi kombinasi nama_kelas dan wali_kelas_id harus unique
        if ($request->wali_kelas_id) {
            $exists = Kelas::where('nama_kelas', $request->nama_kelas)
                ->where('wali_kelas_id', $request->wali_kelas_id)
                ->exists();
            
            if ($exists) {
                return back()->withErrors([
                    'nama_kelas' => 'Kombinasi nama kelas dan wali kelas ini sudah ada.'
                ])->withInput();
            }
            
            // Cek apakah wali kelas sudah assigned ke kelas lain
            $waliKelasExists = Kelas::where('wali_kelas_id', $request->wali_kelas_id)->exists();
            if ($waliKelasExists) {
                return back()->withErrors([
                    'wali_kelas_id' => 'Guru ini sudah menjadi wali kelas di kelas lain.'
                ])->withInput();
            }
        }

        Kelas::create($request->all());
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function show(Kelas $kelas)
    {
        $kelas->load(['waliKelas', 'tahunAjaran']);
        return response()->json($kelas);
    }

    public function edit(Kelas $kelas)
    {
        $gurus = Guru::with('user')
            ->whereHas('user', function($query) {
                $query->where(function($q) {
                    $q->where('role', 'guru')
                      ->orWhereJsonContains('additional_roles', 'guru');
                });
            })
            ->where(function($query) use ($kelas) {
                $query->where('id', $kelas->wali_kelas_id)
                      ->orWhereDoesntHave('kelas');
            })
            ->get();
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

        // Validasi kombinasi nama_kelas dan wali_kelas_id harus unique (exclude current kelas)
        if ($request->wali_kelas_id) {
            $exists = Kelas::where('nama_kelas', $request->nama_kelas)
                ->where('wali_kelas_id', $request->wali_kelas_id)
                ->where('id', '!=', $kelas->id)
                ->exists();
            
            if ($exists) {
                return back()->withErrors([
                    'nama_kelas' => 'Kombinasi nama kelas dan wali kelas ini sudah ada.'
                ])->withInput();
            }
            
            // Cek apakah wali kelas sudah assigned ke kelas lain (exclude current kelas)
            $waliKelasExists = Kelas::where('wali_kelas_id', $request->wali_kelas_id)
                ->where('id', '!=', $kelas->id)
                ->exists();
            if ($waliKelasExists) {
                return back()->withErrors([
                    'wali_kelas_id' => 'Guru ini sudah menjadi wali kelas di kelas lain.'
                ])->withInput();
            }
        }

        $kelas->update($request->all());
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diupdate!');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus!');
    }
}