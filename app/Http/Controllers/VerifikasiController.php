<?php

namespace App\Http\Controllers;

use App\Models\Pelanggaran;
use App\Models\Prestasi;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VerifikasiController extends Controller
{
    /**
     * Dashboard Verifikator
     * Menampilkan statistik dan data yang perlu diverifikasi
     */
    public function index()
    {
        $user = Auth::user();
        $guru = Guru::where('users_id', $user->id)->first();
        
        // Statistik verifikasi
        $pelanggaranMenunggu = Pelanggaran::where('status_verifikasi', 'menunggu')->count();
        $prestasiMenunggu = Prestasi::where('status_verifikasi', 'menunggu')->count();
        $pelanggaranRevisi = Pelanggaran::where('status_verifikasi', 'revisi')->count();
        $prestasiRevisi = Prestasi::where('status_verifikasi', 'revisi')->count();
        
        // Total terverifikasi hari ini
        $pelanggaranHariIni = Pelanggaran::where('status_verifikasi', 'terverifikasi')
            ->whereDate('updated_at', today())
            ->count();
        $prestasiHariIni = Prestasi::where('status_verifikasi', 'terverifikasi')
            ->whereDate('updated_at', today())
            ->count();
            
        // Total ditolak hari ini
        $pelanggaranDitolakHariIni = Pelanggaran::where('status_verifikasi', 'ditolak')
            ->whereDate('updated_at', today())
            ->count();
        $prestasiDitolakHariIni = Prestasi::where('status_verifikasi', 'ditolak')
            ->whereDate('updated_at', today())
            ->count();
        
        // Data yang menunggu verifikasi (5 terbaru)
        $pelanggaranBaru = Pelanggaran::with(['siswa.kelas', 'jenisPelanggaran', 'guru'])
            ->whereIn('status_verifikasi', ['menunggu', 'revisi'])
            ->latest()
            ->take(5)
            ->get();
            
        $prestasiBaru = Prestasi::with(['siswa.kelas', 'jenisPrestasi', 'guru'])
            ->whereIn('status_verifikasi', ['menunggu', 'revisi'])
            ->latest()
            ->take(5)
            ->get();
        
        // Statistik verifikasi 7 hari terakhir
        $statistikHarian = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = now()->subDays($i);
            $statistikHarian['tanggal'][] = $tanggal->format('d M');
            $statistikHarian['pelanggaran'][] = Pelanggaran::where('status_verifikasi', 'terverifikasi')
                ->whereDate('updated_at', $tanggal->toDateString())
                ->count();
            $statistikHarian['prestasi'][] = Prestasi::where('status_verifikasi', 'terverifikasi')
                ->whereDate('updated_at', $tanggal->toDateString())
                ->count();
        }
        
        return view('verifikasi.dashboard', compact(
            'pelanggaranMenunggu',
            'prestasiMenunggu',
            'pelanggaranRevisi',
            'prestasiRevisi',
            'pelanggaranHariIni',
            'prestasiHariIni',
            'pelanggaranDitolakHariIni',
            'prestasiDitolakHariIni',
            'pelanggaranBaru',
            'prestasiBaru',
            'statistikHarian'
        ));
    }
    
    /**
     * Daftar Pelanggaran yang Menunggu Verifikasi
     */
    public function pelanggaranMenunggu(Request $request)
    {
        $query = Pelanggaran::with(['siswa.kelas', 'jenisPelanggaran', 'guru'])
            ->whereIn('status_verifikasi', ['menunggu', 'revisi']);
            
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }
        
        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }
        
        // Filter berdasarkan kelas
        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }
        
        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->whereHas('jenisPelanggaran', function($q) use ($request) {
                $q->where('kategori', $request->kategori);
            });
        }
        
        $pelanggarans = $query->latest()->paginate(20);
        $kelas = \App\Models\Kelas::all();
        
        return view('verifikasi.pelanggaran-menunggu', compact('pelanggarans', 'kelas'));
    }
    
    /**
     * Daftar Prestasi yang Menunggu Verifikasi
     */
    public function prestasiMenunggu(Request $request)
    {
        $query = Prestasi::with(['siswa.kelas', 'jenisPrestasi', 'guru'])
            ->whereIn('status_verifikasi', ['menunggu', 'revisi']);
            
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }
        
        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }
        
        // Filter berdasarkan kelas
        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }
        
        $prestasis = $query->latest()->paginate(20);
        $kelas = \App\Models\Kelas::all();
        
        return view('verifikasi.prestasi-menunggu', compact('prestasis', 'kelas'));
    }
    
    /**
     * Detail Pelanggaran untuk Verifikasi
     */
    public function pelanggaranDetail($id)
    {
        $pelanggaran = Pelanggaran::with([
            'siswa.kelas', 
            'jenisPelanggaran', 
            'guru',
            'siswa.pelanggarans' => function($q) {
                $q->where('status_verifikasi', 'terverifikasi')
                  ->latest()
                  ->take(5);
            }
        ])->findOrFail($id);
        
        // Hitung total poin siswa
        $totalPoin = Pelanggaran::where('siswa_id', $pelanggaran->siswa_id)
            ->where('status_verifikasi', 'terverifikasi')
            ->sum('poin');
            
        return view('verifikasi.pelanggaran-detail', compact('pelanggaran', 'totalPoin'));
    }
    
    /**
     * Detail Prestasi untuk Verifikasi
     */
    public function prestasiDetail($id)
    {
        $prestasi = Prestasi::with([
            'siswa.kelas', 
            'jenisPrestasi', 
            'guru',
            'siswa.prestasis' => function($q) {
                $q->where('status_verifikasi', 'terverifikasi')
                  ->latest()
                  ->take(5);
            }
        ])->findOrFail($id);
        
        // Hitung total poin prestasi siswa
        $totalPoin = Prestasi::where('siswa_id', $prestasi->siswa_id)
            ->where('status_verifikasi', 'terverifikasi')
            ->sum('poin');
            
        return view('verifikasi.prestasi-detail', compact('prestasi', 'totalPoin'));
    }
    
    /**
     * Verifikasi Pelanggaran (Approve)
     */
    public function verifikasiPelanggaran(Request $request, $id)
    {
        $request->validate([
            'catatan_verifikasi' => 'nullable|string|max:500'
        ]);
        
        $pelanggaran = Pelanggaran::findOrFail($id);
        $user = Auth::user();
        $guru = Guru::where('users_id', $user->id)->first();
        
        DB::beginTransaction();
        try {
            $pelanggaran->update([
                'status_verifikasi' => 'terverifikasi',
                'guru_verifikator' => $guru ? $guru->id : null,
                'tanggal_verifikasi' => now(),
                'catatan_verifikasi' => $request->catatan_verifikasi
            ]);
            
            // Log verifikasi
            DB::table('verifikasi_datas')->insert([
                'tabel_terkait' => 'pelanggarans',
                'id_terkait' => $pelanggaran->id,
                'guru_verifikator' => $guru ? $guru->id : null,
                'status' => 'diverifikasi',
                'catatan' => $request->catatan_verifikasi,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Pelanggaran berhasil diverifikasi');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal memverifikasi pelanggaran: ' . $e->getMessage());
        }
    }
    
    /**
     * Tolak Pelanggaran (Reject)
     */
    public function tolakPelanggaran(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500'
        ]);
        
        $pelanggaran = Pelanggaran::findOrFail($id);
        $user = Auth::user();
        $guru = Guru::where('users_id', $user->id)->first();
        
        DB::beginTransaction();
        try {
            $pelanggaran->update([
                'status_verifikasi' => 'ditolak',
                'guru_verifikator' => $guru ? $guru->id : null,
                'tanggal_verifikasi' => now(),
                'catatan_verifikasi' => $request->alasan_penolakan
            ]);
            
            // Log verifikasi
            DB::table('verifikasi_datas')->insert([
                'tabel_terkait' => 'pelanggarans',
                'id_terkait' => $pelanggaran->id,
                'guru_verifikator' => $guru ? $guru->id : null,
                'status' => 'ditolak',
                'catatan' => $request->alasan_penolakan,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Pelanggaran berhasil ditolak');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menolak pelanggaran: ' . $e->getMessage());
        }
    }
    
    /**
     * Minta Revisi Pelanggaran
     */
    public function revisiPelanggaran(Request $request, $id)
    {
        $request->validate([
            'catatan_revisi' => 'required|string|max:500'
        ]);
        
        $pelanggaran = Pelanggaran::findOrFail($id);
        $user = Auth::user();
        $guru = Guru::where('users_id', $user->id)->first();
        
        DB::beginTransaction();
        try {
            $pelanggaran->update([
                'status_verifikasi' => 'revisi',
                'guru_verifikator' => $guru ? $guru->id : null,
                'tanggal_verifikasi' => now(),
                'catatan_verifikasi' => $request->catatan_revisi
            ]);
            
            // Log verifikasi
            DB::table('verifikasi_datas')->insert([
                'tabel_terkait' => 'pelanggarans',
                'id_terkait' => $pelanggaran->id,
                'guru_verifikator' => $guru ? $guru->id : null,
                'status' => 'revisi',
                'catatan' => $request->catatan_revisi,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Pelanggaran diminta untuk direvisi');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal meminta revisi: ' . $e->getMessage());
        }
    }
    
    /**
     * Verifikasi Prestasi (Approve)
     */
    public function verifikasiPrestasi(Request $request, $id)
    {
        $request->validate([
            'catatan_verifikasi' => 'nullable|string|max:500'
        ]);
        
        $prestasi = Prestasi::findOrFail($id);
        $user = Auth::user();
        $guru = Guru::where('users_id', $user->id)->first();
        
        DB::beginTransaction();
        try {
            $prestasi->update([
                'status_verifikasi' => 'terverifikasi',
                'guru_verifikator' => $guru ? $guru->id : null,
                'tanggal_verifikasi' => now(),
                'catatan_verifikasi' => $request->catatan_verifikasi
            ]);
            
            // Log verifikasi
            DB::table('verifikasi_datas')->insert([
                'tabel_terkait' => 'prestasis',
                'id_terkait' => $prestasi->id,
                'guru_verifikator' => $guru ? $guru->id : null,
                'status' => 'diverifikasi',
                'catatan' => $request->catatan_verifikasi,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Prestasi berhasil diverifikasi');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal memverifikasi prestasi: ' . $e->getMessage());
        }
    }
    
    /**
     * Tolak Prestasi (Reject)
     */
    public function tolakPrestasi(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500'
        ]);
        
        $prestasi = Prestasi::findOrFail($id);
        $user = Auth::user();
        $guru = Guru::where('users_id', $user->id)->first();
        
        DB::beginTransaction();
        try {
            $prestasi->update([
                'status_verifikasi' => 'ditolak',
                'guru_verifikator' => $guru ? $guru->id : null,
                'tanggal_verifikasi' => now(),
                'catatan_verifikasi' => $request->alasan_penolakan
            ]);
            
            // Log verifikasi
            DB::table('verifikasi_datas')->insert([
                'tabel_terkait' => 'prestasis',
                'id_terkait' => $prestasi->id,
                'guru_verifikator' => $guru ? $guru->id : null,
                'status' => 'ditolak',
                'catatan' => $request->alasan_penolakan,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Prestasi berhasil ditolak');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menolak prestasi: ' . $e->getMessage());
        }
    }
    
    /**
     * Minta Revisi Prestasi
     */
    public function revisiPrestasi(Request $request, $id)
    {
        $request->validate([
            'catatan_revisi' => 'required|string|max:500'
        ]);
        
        $prestasi = Prestasi::findOrFail($id);
        $user = Auth::user();
        $guru = Guru::where('users_id', $user->id)->first();
        
        DB::beginTransaction();
        try {
            $prestasi->update([
                'status_verifikasi' => 'revisi',
                'guru_verifikator' => $guru ? $guru->id : null,
                'tanggal_verifikasi' => now(),
                'catatan_verifikasi' => $request->catatan_revisi
            ]);
            
            // Log verifikasi
            DB::table('verifikasi_datas')->insert([
                'tabel_terkait' => 'prestasis',
                'id_terkait' => $prestasi->id,
                'guru_verifikator' => $guru ? $guru->id : null,
                'status' => 'revisi',
                'catatan' => $request->catatan_revisi,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Prestasi diminta untuk direvisi');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal meminta revisi: ' . $e->getMessage());
        }
    }
    
    /**
     * Riwayat Verifikasi
     */
    public function riwayat(Request $request)
    {
        $user = Auth::user();
        $guru = Guru::where('users_id', $user->id)->first();
        
        $query = DB::table('verifikasi_datas')
            ->where('guru_verifikator', $guru ? $guru->id : null);
            
        // Filter berdasarkan tabel
        if ($request->filled('tabel')) {
            $query->where('tabel_terkait', $request->tabel);
        }
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }
        
        $riwayat = $query->latest()->paginate(20);
        
        return view('verifikasi.riwayat', compact('riwayat'));
    }
    
    /**
     * Bulk Verifikasi Pelanggaran
     */
    public function bulkVerifikasiPelanggaran(Request $request)
    {
        $request->validate([
            'pelanggaran_ids' => 'required|array',
            'pelanggaran_ids.*' => 'exists:pelanggarans,id'
        ]);
        
        $user = Auth::user();
        $guru = Guru::where('users_id', $user->id)->first();
        
        DB::beginTransaction();
        try {
            foreach ($request->pelanggaran_ids as $id) {
                $pelanggaran = Pelanggaran::find($id);
                if ($pelanggaran && $pelanggaran->status_verifikasi == 'menunggu') {
                    $pelanggaran->update([
                        'status_verifikasi' => 'terverifikasi',
                        'guru_verifikator' => $guru ? $guru->id : null,
                        'tanggal_verifikasi' => now()
                    ]);
                    
                    // Log verifikasi
                    DB::table('verifikasi_datas')->insert([
                        'tabel_terkait' => 'pelanggarans',
                        'id_terkait' => $pelanggaran->id,
                        'guru_verifikator' => $guru ? $guru->id : null,
                        'status' => 'diverifikasi',
                        'catatan' => 'Bulk verification',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->back()->with('success', count($request->pelanggaran_ids) . ' pelanggaran berhasil diverifikasi');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal verifikasi bulk: ' . $e->getMessage());
        }
    }
    
    /**
     * Bulk Verifikasi Prestasi
     */
    public function bulkVerifikasiPrestasi(Request $request)
    {
        $request->validate([
            'prestasi_ids' => 'required|array',
            'prestasi_ids.*' => 'exists:prestasis,id'
        ]);
        
        $user = Auth::user();
        $guru = Guru::where('users_id', $user->id)->first();
        
        DB::beginTransaction();
        try {
            foreach ($request->prestasi_ids as $id) {
                $prestasi = Prestasi::find($id);
                if ($prestasi && $prestasi->status_verifikasi == 'menunggu') {
                    $prestasi->update([
                        'status_verifikasi' => 'terverifikasi',
                        'guru_verifikator' => $guru ? $guru->id : null,
                        'tanggal_verifikasi' => now()
                    ]);
                    
                    // Log verifikasi
                    DB::table('verifikasi_datas')->insert([
                        'tabel_terkait' => 'prestasis',
                        'id_terkait' => $prestasi->id,
                        'guru_verifikator' => $guru ? $guru->id : null,
                        'status' => 'diverifikasi',
                        'catatan' => 'Bulk verification',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->back()->with('success', count($request->prestasi_ids) . ' prestasi berhasil diverifikasi');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal verifikasi bulk: ' . $e->getMessage());
        }
    }
}
