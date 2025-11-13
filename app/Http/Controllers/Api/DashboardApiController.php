<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Pelanggaran;
use App\Models\Prestasi;
use Illuminate\Http\Request;

class DashboardApiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        if ($user->role === 'siswa') {
            $siswa = Siswa::where('users_id', $user->id)->first();
            
            if (!$siswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data siswa tidak ditemukan'
                ], 404);
            }
            
            $totalPelanggaran = $siswa->pelanggarans()->count();
            $totalPrestasi = $siswa->prestasis()->count();
            $totalPoin = $siswa->pelanggarans()
                ->where('status_verifikasi', 'verified')
                ->sum('poin');
            
            $pelanggaranTerbaru = $siswa->pelanggarans()
                ->with('jenisPelanggaran')
                ->latest()
                ->take(5)
                ->get();
            
            $prestasiTerbaru = $siswa->prestasis()
                ->with('jenisPrestasi')
                ->latest()
                ->take(5)
                ->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Dashboard siswa berhasil diambil',
                'data' => [
                    'siswa' => [
                        'id' => $siswa->id,
                        'nis' => $siswa->nis,
                        'nama' => $siswa->nama_siswa,
                        'kelas' => $siswa->kelas->nama_kelas
                    ],
                    'statistik' => [
                        'total_pelanggaran' => $totalPelanggaran,
                        'total_prestasi' => $totalPrestasi,
                        'total_poin' => $totalPoin
                    ],
                    'pelanggaran_terbaru' => $pelanggaranTerbaru,
                    'prestasi_terbaru' => $prestasiTerbaru
                ]
            ], 200);
        }
        
        $totalSiswa = Siswa::count();
        $totalPelanggaran = Pelanggaran::count();
        $totalPrestasi = Prestasi::count();
        $totalPoinSemua = Pelanggaran::where('status_verifikasi', 'verified')->sum('poin');
        
        return response()->json([
            'success' => true,
            'message' => 'Dashboard berhasil diambil',
            'data' => [
                'statistik' => [
                    'total_siswa' => $totalSiswa,
                    'total_pelanggaran' => $totalPelanggaran,
                    'total_prestasi' => $totalPrestasi,
                    'total_poin' => $totalPoinSemua
                ]
            ]
        ], 200);
    }
}
