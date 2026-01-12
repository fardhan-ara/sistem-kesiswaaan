<?php

namespace App\Http\Controllers;

use App\Models\Sanksi;
use App\Models\Pelanggaran;
use Illuminate\Http\Request;

class SanksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Sanksi::with(['pelanggaran.siswa', 'pelanggaran.jenisPelanggaran']);
        
        if ($request->filled('status')) {
            $query->where('status_sanksi', $request->status);
        }
        if ($request->filled('kategori')) {
            $query->where('kategori_poin', $request->kategori);
        }
        if ($request->filled('siswa')) {
            $query->whereHas('pelanggaran.siswa', function($q) use ($request) {
                $q->where('nama_siswa', 'like', '%' . $request->siswa . '%');
            });
        }
        
        $sanksis = $query->latest()->paginate(20)->withQueryString();
        return view('sanksi.index', compact('sanksis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
