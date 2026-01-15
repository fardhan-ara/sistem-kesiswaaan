<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = TahunAjaran::query();
            
            if ($request->status_aktif) {
                $query->where('status_aktif', $request->status_aktif);
            }
            
            if ($request->semester) {
                $query->where('semester', $request->semester);
            }
            
            $tahunAjarans = $query->latest()->paginate(10);
            return view('tahun-ajaran.index', compact('tahunAjarans'));
        } catch (\Exception $e) {
            \Log::error('Error in TahunAjaranController@index: ' . $e->getMessage());
            return view('tahun-ajaran.index', ['tahunAjarans' => collect()->paginate(10)])
                ->with('error', 'Terjadi kesalahan saat memuat data tahun ajaran.');
        }
    }

    public function create()
    {
        return view('tahun-ajaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_mulai' => 'required|integer|min:2020|max:2050',
            'tahun_selesai' => 'required|integer|min:2020|max:2050|gt:tahun_mulai',
            'semester' => 'required|in:ganjil,genap',
            'status_aktif' => 'required|in:aktif,nonaktif'
        ]);

        if ($request->status_aktif === 'aktif') {
            TahunAjaran::where('status_aktif', 'aktif')->update(['status_aktif' => 'nonaktif']);
        }

        $data = $request->all();
        $data['tahun_ajaran'] = $request->tahun_mulai . '/' . $request->tahun_selesai;
        
        TahunAjaran::create($data);
        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil ditambahkan!');
    }

    public function show(TahunAjaran $tahunAjaran)
    {
        return response()->json($tahunAjaran);
    }

    public function edit(TahunAjaran $tahunAjaran)
    {
        try {
            return view('tahun-ajaran.edit', compact('tahunAjaran'));
        } catch (\Exception $e) {
            \Log::error('Error in TahunAjaranController@edit: ' . $e->getMessage());
            return redirect()->route('tahun-ajaran.index')
                ->with('error', 'Terjadi kesalahan saat membuka halaman edit tahun ajaran.');
        }
    }

    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        $request->validate([
            'tahun_mulai' => 'required|integer|min:2020|max:2050',
            'tahun_selesai' => 'required|integer|min:2020|max:2050|gt:tahun_mulai',
            'semester' => 'required|in:ganjil,genap',
            'status_aktif' => 'required|in:aktif,nonaktif'
        ]);

        if ($request->status_aktif === 'aktif' && $tahunAjaran->status_aktif !== 'aktif') {
            TahunAjaran::where('status_aktif', 'aktif')->update(['status_aktif' => 'nonaktif']);
        }

        $data = $request->only(['tahun_mulai', 'tahun_selesai', 'semester', 'status_aktif']);
        $data['tahun_ajaran'] = $request->tahun_mulai . '/' . $request->tahun_selesai;
        
        $tahunAjaran->update($data);
        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil diupdate!');
    }

    public function destroy(TahunAjaran $tahunAjaran)
    {
        $tahunAjaran->delete();
        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil dihapus!');
    }
}