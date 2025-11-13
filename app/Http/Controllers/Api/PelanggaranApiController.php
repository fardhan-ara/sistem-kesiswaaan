<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pelanggaran;
use App\Models\JenisPelanggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelanggaranApiController extends Controller
{
    public function index()
    {
        $pelanggarans = Pelanggaran::with(['siswa.kelas', 'guru', 'jenisPelanggaran'])->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Data pelanggaran berhasil diambil',
            'data' => $pelanggarans
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'guru_id' => 'required|exists:gurus,id',
            'jenis_pelanggaran_id' => 'required|exists:jenis_pelanggarans,id',
            'keterangan' => 'nullable|string|max:1000'
        ]);

        try {
            $pelanggaran = DB::transaction(function () use ($validated) {
                $jenisPelanggaran = JenisPelanggaran::find($validated['jenis_pelanggaran_id']);
                
                $pelanggaran = Pelanggaran::create([
                    'siswa_id' => $validated['siswa_id'],
                    'guru_id' => $validated['guru_id'],
                    'jenis_pelanggaran_id' => $validated['jenis_pelanggaran_id'],
                    'poin' => $jenisPelanggaran->poin,
                    'keterangan' => $validated['keterangan'] ?? null,
                    'status_verifikasi' => 'pending'
                ]);

                return $pelanggaran->load(['siswa.kelas', 'guru', 'jenisPelanggaran']);
            });

            return response()->json([
                'success' => true,
                'message' => 'Pelanggaran berhasil dibuat',
                'data' => $pelanggaran
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pelanggaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Pelanggaran $pelanggaran)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data pelanggaran berhasil diambil',
            'data' => $pelanggaran->load(['siswa.kelas', 'guru', 'jenisPelanggaran'])
        ], 200);
    }
}
