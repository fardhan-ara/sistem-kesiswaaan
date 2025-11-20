<?php

namespace App\Http\Controllers;

use App\Models\Pelanggaran;
use App\Models\Prestasi;
use App\Models\Sanksi;
use App\Models\Siswa;
use App\Models\User;
use App\Models\BimbinganKonseling;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{


    public function index()
    {
        $user = Auth::user();
        
        // Data berdasarkan role
        switch ($user->role) {
            case 'admin':
            case 'kesiswaan':
                return $this->adminDashboard();
            case 'guru':
            case 'wali_kelas':
                return $this->guruDashboard();
            case 'siswa':
                return $this->siswaDashboard();
            case 'ortu':
                return $this->ortuDashboard();
            default:
                return $this->defaultDashboard();
        }
    }

    private function adminDashboard()
    {
        // Statistik utama
        $totalSiswa = Siswa::count();
        $totalPelanggaran = Pelanggaran::where('status_verifikasi', 'verified')->count();
        $totalPrestasi = Prestasi::where('status_verifikasi', 'verified')->count();
        $sanksiAktif = Sanksi::whereIn('status_sanksi', ['direncanakan', 'berjalan'])->count();
        $totalUsers = User::count();
        $totalBK = BimbinganKonseling::count();
        
        // Data untuk chart pelanggaran per bulan (12 bulan terakhir)
        $pelanggaranPerBulan = [];
        $prestasiPerBulan = [];
        $bulanLabels = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $bulanLabels[] = $bulan->format('M Y');
            
            $pelanggaranPerBulan[] = Pelanggaran::whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->where('status_verifikasi', 'verified')
                ->count();
                
            $prestasiPerBulan[] = Prestasi::whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->where('status_verifikasi', 'verified')
                ->count();
        }

        // Statistik per kategori pelanggaran
        $pelanggaranPerKategori = DB::table('pelanggarans')
            ->join('jenis_pelanggarans', 'pelanggarans.jenis_pelanggaran_id', '=', 'jenis_pelanggarans.id')
            ->select('jenis_pelanggarans.kategori', DB::raw('count(*) as total'))
            ->where('pelanggarans.status_verifikasi', 'verified')
            ->groupBy('jenis_pelanggarans.kategori')
            ->get();

        // Top 5 pelanggar
        $topPelanggar = Siswa::with('kelas')
            ->withCount(['pelanggarans' => function($q) {
                $q->where('status_verifikasi', 'verified');
            }])
            ->withSum(['pelanggarans' => function($q) {
                $q->where('status_verifikasi', 'verified');
            }], 'poin')
            ->having('pelanggarans_count', '>', 0)
            ->orderBy('pelanggarans_count', 'desc')
            ->take(5)
            ->get();

        // Top 5 prestasi
        $topPrestasi = Siswa::with('kelas')
            ->withCount(['prestasis' => function($q) {
                $q->where('status_verifikasi', 'verified');
            }])
            ->withSum(['prestasis' => function($q) {
                $q->where('status_verifikasi', 'verified');
            }], 'poin')
            ->having('prestasis_count', '>', 0)
            ->orderBy('prestasis_count', 'desc')
            ->take(5)
            ->get();

        // Pelanggaran terbaru (belum diverifikasi)
        $pelanggaranBaru = Pelanggaran::with(['siswa.kelas', 'jenisPelanggaran', 'guru'])
            ->where('status_verifikasi', 'menunggu')
            ->latest()
            ->take(5)
            ->get();

        // Prestasi terbaru (belum diverifikasi)
        $prestasiBaru = Prestasi::with(['siswa.kelas', 'jenisPrestasi', 'guru'])
            ->where('status_verifikasi', 'menunggu')
            ->latest()
            ->take(5)
            ->get();
            
        // Sanksi yang akan berakhir minggu ini
        $sanksiMendatang = Sanksi::with(['pelanggaran.siswa'])
            ->where('status_sanksi', 'berjalan')
            ->whereBetween('tanggal_selesai', [now(), now()->addWeek()])
            ->orderBy('tanggal_selesai')
            ->get();

        return view('dashboard.admin', compact(
            'totalSiswa',
            'totalPelanggaran',
            'totalPrestasi',
            'sanksiAktif',
            'totalUsers',
            'totalBK',
            'pelanggaranPerBulan',
            'prestasiPerBulan',
            'bulanLabels',
            'pelanggaranPerKategori',
            'topPelanggar',
            'topPrestasi',
            'pelanggaranBaru',
            'prestasiBaru',
            'sanksiMendatang'
        ));
    }

    private function guruDashboard()
    {
        $user = Auth::user();
        $guru = \App\Models\Guru::where('users_id', $user->id)->first();
        
        if (!$guru) {
            return view('dashboard.guru', [
                'message' => 'Data guru Anda belum terdaftar. Hubungi admin.',
                'totalPelanggaran' => 0,
                'totalPrestasi' => 0,
                'pelanggaranBulanIni' => 0,
                'prestasiBulanIni' => 0,
                'pelanggaranTerbaru' => collect(),
                'statistikBulanan' => []
            ]);
        }
        
        // Statistik dasar
        $totalPelanggaran = Pelanggaran::where('guru_pencatat', $guru->id)->count();
        $totalPrestasi = Prestasi::where('guru_pencatat', $guru->id)->count();
        
        $pelanggaranBulanIni = Pelanggaran::where('guru_pencatat', $guru->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        $prestasiBulanIni = Prestasi::where('guru_pencatat', $guru->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        // Pelanggaran terbaru yang dicatat
        $pelanggaranTerbaru = Pelanggaran::with(['siswa.kelas', 'jenisPelanggaran'])
            ->where('guru_pencatat', $guru->id)
            ->latest()
            ->take(10)
            ->get();
            
        // Data untuk chart 6 bulan terakhir
        $statistikBulanan = [];
        $bulanLabels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $bulanLabels[] = $bulan->format('M Y');
            
            $statistikBulanan['pelanggaran'][] = Pelanggaran::where('guru_pencatat', $guru->id)
                ->whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->count();
                
            $statistikBulanan['prestasi'][] = Prestasi::where('guru_pencatat', $guru->id)
                ->whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->count();
        }
        
        $statistikBulanan['labels'] = $bulanLabels;

        return view('dashboard.guru', compact(
            'totalPelanggaran',
            'totalPrestasi',
            'pelanggaranBulanIni',
            'prestasiBulanIni',
            'pelanggaranTerbaru',
            'statistikBulanan',
            'guru'
        ));
    }

    private function siswaDashboard()
    {
        $user = Auth::user();
        
        // Cari siswa berdasarkan users_id atau username yang sama dengan NIS
        $siswa = Siswa::where('users_id', $user->id)
            ->orWhere('nis', $user->username)
            ->first();
        
        if (!$siswa) {
            return view('dashboard.siswa', [
                'siswa' => null,
                'message' => 'Data siswa Anda belum terdaftar. Silakan hubungi admin untuk melengkapi data Anda.',
                'totalPelanggaran' => 0,
                'totalPrestasi' => 0,
                'totalPoin' => 0,
                'sanksiAktif' => 0
            ]);
        }

        // Hitung statistik siswa
        $totalPelanggaran = $siswa->pelanggarans()
            ->where('status_verifikasi', 'verified')
            ->count();
            
        $totalPrestasi = $siswa->prestasis()
            ->where('status_verifikasi', 'verified')
            ->count();
            
        $totalPoin = $siswa->pelanggarans()
            ->where('status_verifikasi', 'verified')
            ->sum('poin');
            
        // Hitung sanksi aktif dari pelanggaran
        $sanksiAktif = Sanksi::whereHas('pelanggaran', function($q) use ($siswa) {
            $q->where('siswa_id', $siswa->id);
        })
        ->whereIn('status_sanksi', ['direncanakan', 'berjalan'])
        ->count();
        
        // Ambil data pelanggaran terbaru (5 terakhir)
        $pelanggaranTerbaru = $siswa->pelanggarans()
            ->with('jenisPelanggaran', 'guru')
            ->latest()
            ->take(5)
            ->get();
            
        // Ambil data prestasi terbaru (5 terakhir)
        $prestasiTerbaru = $siswa->prestasis()
            ->with('jenisPrestasi', 'guru')
            ->latest()
            ->take(5)
            ->get();
            
        // Ambil sanksi yang sedang berjalan
        $sanksiAktifList = Sanksi::whereHas('pelanggaran', function($q) use ($siswa) {
            $q->where('siswa_id', $siswa->id);
        })
        ->whereIn('status_sanksi', ['direncanakan', 'berjalan'])
        ->with('pelanggaran.jenisPelanggaran')
        ->get();

        return view('dashboard.siswa', compact(
            'siswa',
            'totalPelanggaran',
            'totalPrestasi',
            'totalPoin',
            'sanksiAktif',
            'pelanggaranTerbaru',
            'prestasiTerbaru',
            'sanksiAktifList'
        ));
    }

    private function ortuDashboard()
    {
        return view('dashboard.ortu');
    }

    private function defaultDashboard()
    {
        return view('dashboard.index');
    }

    private function getMonthlyData($model, $conditions = [])
    {
        $query = $model::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('COUNT(*) as total')
        )->whereYear('created_at', date('Y'));
        
        foreach ($conditions as $field => $value) {
            $query->where($field, $value);
        }
        
        return $query->groupBy('bulan')->pluck('total', 'bulan');
    }
}