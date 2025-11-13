<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use App\Http\Resources\PrestasiResource;
use Illuminate\Http\Request;

class PrestasiApiController extends Controller
{
    public function index()
    {
        $prestasis = Prestasi::with(['siswa.kelas', 'jenisPrestasi'])->get();
        
        return PrestasiResource::collection($prestasis)->additional([
            'success' => true,
            'message' => 'Data prestasi berhasil diambil'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'jenis_prestasi_id' => 'required|exists:jenis_prestasis,id',
            'keterangan' => 'nullable|string|max:500'
        ]);

        try {
            $prestasi = Prestasi::create([
                'siswa_id' => $validated['siswa_id'],
                'jenis_prestasi_id' => $validated['jenis_prestasi_id'],
                'keterangan' => $validated['keterangan'] ?? null,
                'status_verifikasi' => 'pending'
            ]);

            $prestasi->load(['siswa.kelas', 'jenisPrestasi']);

            return (new PrestasiResource($prestasi))->additional([
                'success' => true,
                'message' => 'Prestasi berhasil dibuat'
            ])->response()->setStatusCode(201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat prestasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Prestasi $prestasi)
    {
        return (new PrestasiResource($prestasi->load(['siswa.kelas', 'jenisPrestasi'])))->additional([
            'success' => true,
            'message' => 'Data prestasi berhasil diambil'
        ]);
    }
}
