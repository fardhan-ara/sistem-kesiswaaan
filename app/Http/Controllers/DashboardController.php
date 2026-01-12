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
        
        // Redirect berdasarkan role - TANPA LOOP
        switch ($user->role) {
            case 'admin':
            case 'kesiswaan':
                return $this->adminDashboard();
            
            case 'guru':
                return $this->guruDashboard();
            
            case 'bk':
                return $this->bkDashboard();
            
            case 'kepala_sekolah':
                return $this->kepalaSekolahDashboard();
            
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
        // Statistik utama - query sederhana
        $totalSiswa = Siswa::count();
        $totalPelanggaran = Pelanggaran::where('status_verifikasi', 'diverifikasi')->count();
        $totalPrestasi = Prestasi::where('status_verifikasi', 'verified')->count();
        $sanksiAktif = Sanksi::where('status_sanksi', 'aktif')->count();
        $totalUsers = User::count();
        $totalBK = BimbinganKonseling::count();
        
        // Data chart - optimized dengan raw query
        $pelanggaranPerBulan = Pelanggaran::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->whereYear('created_at', now()->year)
            ->where('status_verifikasi', 'diverifikasi')
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();
            
        $prestasiPerBulan = Prestasi::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->whereYear('created_at', now()->year)
            ->where('status_verifikasi', 'verified')
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        // Fill missing months with 0
        for ($i = 1; $i <= 12; $i++) {
            if (!isset($pelanggaranPerBulan[$i])) $pelanggaranPerBulan[$i] = 0;
            if (!isset($prestasiPerBulan[$i])) $prestasiPerBulan[$i] = 0;
        }
        ksort($pelanggaranPerBulan);
        ksort($prestasiPerBulan);

        // Kategori pelanggaran - group by nama pelanggaran (top 10)
        $pelanggaranPerKategori = DB::table('pelanggarans')
            ->join('jenis_pelanggarans', 'pelanggarans.jenis_pelanggaran_id', '=', 'jenis_pelanggarans.id')
            ->select('jenis_pelanggarans.nama_pelanggaran', DB::raw('count(*) as total'))
            ->where('pelanggarans.status_verifikasi', 'diverifikasi')
            ->groupBy('jenis_pelanggarans.nama_pelanggaran')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // Top siswa - simplified, hanya ambil 5
        $topSiswa = Siswa::select('siswas.*')
            ->withCount(['pelanggarans' => function($q) {
                $q->where('status_verifikasi', 'diverifikasi');
            }])
            ->withCount(['prestasis' => function($q) {
                $q->where('status_verifikasi', 'verified');
            }])
            ->having(DB::raw('pelanggarans_count + prestasis_count'), '>', 0)
            ->orderByRaw('pelanggarans_count + prestasis_count DESC')
            ->limit(5)
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
            'pelanggaranPerKategori',
            'topSiswa'
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
                'statistikBulanan' => ['labels' => [], 'pelanggaran' => [], 'prestasi' => []]
            ]);
        }
        
        // Statistik sederhana
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
            
        // Pelanggaran terbaru - limit 5
        $pelanggaranTerbaru = Pelanggaran::with(['siswa:id,nama_siswa,kelas_id', 'siswa.kelas:id,nama_kelas', 'jenisPelanggaran:id,nama_pelanggaran'])
            ->where('guru_pencatat', $guru->id)
            ->latest()
            ->limit(5)
            ->get();
            
        // Chart 3 bulan terakhir saja
        $statistikBulanan = ['labels' => [], 'pelanggaran' => [], 'prestasi' => []];
        for ($i = 2; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $statistikBulanan['labels'][] = $bulan->format('M Y');
            $statistikBulanan['pelanggaran'][] = Pelanggaran::where('guru_pencatat', $guru->id)
                ->whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->count();
            $statistikBulanan['prestasi'][] = Prestasi::where('guru_pencatat', $guru->id)
                ->whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->count();
        }

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
        
        if ($user->status !== 'approved') {
            $statusMessage = match($user->status) {
                'pending' => 'Akun Anda sedang menunggu persetujuan dari admin.',
                'rejected' => 'Akun Anda ditolak. Alasan: ' . ($user->rejection_reason ?? 'Tidak ada alasan'),
                default => 'Status akun tidak valid. Hubungi admin.'
            };
            
            return view('dashboard.siswa', [
                'siswa' => null,
                'status' => $user->status,
                'message' => $statusMessage,
                'totalPelanggaran' => 0,
                'totalPrestasi' => 0,
                'totalPoin' => 0,
                'sanksiAktif' => 0,
                'pelanggaranTerbaru' => collect(),
                'prestasiTerbaru' => collect(),
                'sanksiAktifList' => collect()
            ]);
        }
        
        $siswa = Siswa::select('id', 'nama_siswa', 'kelas_id', 'tahun_ajaran_id', 'nis', 'jenis_kelamin')
            ->with(['kelas:id,nama_kelas', 'tahunAjaran:id,tahun_ajaran,tahun_mulai,tahun_selesai,semester'])
            ->where('users_id', $user->id)
            ->first();
        
        if (!$siswa) {
            return view('dashboard.siswa', [
                'siswa' => null,
                'status' => 'approved',
                'message' => 'Data siswa belum terdaftar. Hubungi admin.',
                'totalPelanggaran' => 0,
                'totalPrestasi' => 0,
                'totalPoin' => 0,
                'sanksiAktif' => 0,
                'pelanggaranTerbaru' => collect(),
                'prestasiTerbaru' => collect(),
                'sanksiAktifList' => collect()
            ]);
        }

        // Statistik simple
        $totalPelanggaran = Pelanggaran::where('siswa_id', $siswa->id)
            ->where('status_verifikasi', 'terverifikasi')->count();
        $totalPrestasi = Prestasi::where('siswa_id', $siswa->id)
            ->where('status_verifikasi', 'terverifikasi')->count();
        $totalPoin = Pelanggaran::where('siswa_id', $siswa->id)
            ->where('status_verifikasi', 'terverifikasi')->sum('poin');
        $sanksiAktif = Sanksi::whereHas('pelanggaran', function($q) use ($siswa) {
            $q->where('siswa_id', $siswa->id);
        })->whereIn('status_sanksi', ['direncanakan', 'sedang_dilaksanakan'])->count();
        
        // Data terbaru - limit 3
        $pelanggaranTerbaru = Pelanggaran::select('id', 'jenis_pelanggaran_id', 'poin', 'guru_pencatat', 'status_verifikasi', 'created_at')
            ->with(['jenisPelanggaran:id,nama_pelanggaran', 'guru:id,nama_guru'])
            ->where('siswa_id', $siswa->id)
            ->where('status_verifikasi', 'terverifikasi')
            ->latest()->limit(3)->get();
            
        $prestasiTerbaru = Prestasi::select('id', 'jenis_prestasi_id', 'poin', 'guru_pencatat', 'status_verifikasi', 'created_at')
            ->with(['jenisPrestasi:id,nama_prestasi', 'guru:id,nama_guru'])
            ->where('siswa_id', $siswa->id)
            ->where('status_verifikasi', 'terverifikasi')
            ->latest()->limit(3)->get();
            
        $sanksiAktifList = Sanksi::select('id', 'pelanggaran_id', 'nama_sanksi', 'tanggal_mulai', 'tanggal_selesai')
            ->whereHas('pelanggaran', function($q) use ($siswa) {
                $q->where('siswa_id', $siswa->id);
            })
            ->whereIn('status_sanksi', ['direncanakan', 'sedang_dilaksanakan'])
            ->limit(3)->get();

        return view('dashboard.siswa', compact(
            'siswa', 'totalPelanggaran', 'totalPrestasi', 'totalPoin',
            'sanksiAktif', 'pelanggaranTerbaru', 'prestasiTerbaru', 'sanksiAktifList'
        ));
    }

    private function ortuDashboard()
    {
        $user = Auth::user();
        
        // Cek status user dulu
        if ($user->status === 'pending') {
            return view('dashboard.ortu', [
                'siswa' => null,
                'status' => 'pending',
                'message' => 'Akun Anda menunggu persetujuan admin. Silakan cek email untuk notifikasi.',
                'totalPelanggaran' => 0,
                'totalPrestasi' => 0,
                'totalPoin' => 0,
                'sanksiAktif' => 0
            ]);
        }
        
        if ($user->status === 'rejected') {
            return view('dashboard.ortu', [
                'siswa' => null,
                'status' => 'rejected',
                'message' => 'Akun ditolak oleh admin. Alasan: ' . ($user->rejection_reason ?? 'Tidak ada keterangan'),
                'totalPelanggaran' => 0,
                'totalPrestasi' => 0,
                'totalPoin' => 0,
                'sanksiAktif' => 0
            ]);
        }
        
        $biodata = \App\Models\BiodataOrtu::select('id', 'siswa_id', 'status_approval', 'rejection_reason')
            ->where('user_id', $user->id)->first();
        
        if (!$biodata) {
            return view('dashboard.ortu', [
                'siswa' => null,
                'status' => 'no_biodata',
                'message' => 'Silakan lengkapi biodata untuk verifikasi.',
                'totalPelanggaran' => 0,
                'totalPrestasi' => 0,
                'totalPoin' => 0,
                'sanksiAktif' => 0
            ]);
        }
        
        if ($biodata->status_approval === 'pending') {
            return view('dashboard.ortu', [
                'siswa' => null,
                'status' => 'pending',
                'message' => 'Biodata sedang ditinjau admin.',
                'totalPelanggaran' => 0,
                'totalPrestasi' => 0,
                'totalPoin' => 0,
                'sanksiAktif' => 0
            ]);
        }
        
        if ($biodata->status_approval === 'rejected') {
            return view('dashboard.ortu', [
                'siswa' => null,
                'status' => 'rejected',
                'message' => 'Biodata ditolak: ' . $biodata->rejection_reason,
                'totalPelanggaran' => 0,
                'totalPrestasi' => 0,
                'totalPoin' => 0,
                'sanksiAktif' => 0
            ]);
        }
        
        $siswa = Siswa::select('id', 'nama_siswa', 'nis', 'jenis_kelamin', 'kelas_id', 'tahun_ajaran_id')
            ->with(['kelas:id,nama_kelas', 'tahunAjaran:id,tahun_ajaran,tahun_mulai,tahun_selesai,semester'])
            ->find($biodata->siswa_id);
        
        if (!$siswa) {
            return view('dashboard.ortu', [
                'siswa' => null,
                'status' => 'approved',
                'message' => 'Data anak tidak ditemukan.',
                'totalPelanggaran' => 0,
                'totalPrestasi' => 0,
                'totalPoin' => 0,
                'sanksiAktif' => 0
            ]);
        }
        
        // Statistik simple
        $totalPelanggaran = Pelanggaran::where('siswa_id', $siswa->id)
            ->where('status_verifikasi', 'diverifikasi')->count();
        $totalPrestasi = Prestasi::where('siswa_id', $siswa->id)
            ->where('status_verifikasi', 'verified')->count();
        $totalPoin = Pelanggaran::where('siswa_id', $siswa->id)
            ->where('status_verifikasi', 'diverifikasi')->sum('poin');
        $sanksiAktif = Sanksi::whereHas('pelanggaran', function($q) use ($siswa) {
            $q->where('siswa_id', $siswa->id);
        })->where('status_sanksi', 'aktif')->count();
        
        $pelanggaranTerbaru = Pelanggaran::select('id', 'jenis_pelanggaran_id', 'poin', 'created_at')
            ->with(['jenisPelanggaran:id,nama_pelanggaran'])
            ->where('siswa_id', $siswa->id)
            ->where('status_verifikasi', 'diverifikasi')
            ->latest()->limit(3)->get();
            
        $prestasiTerbaru = Prestasi::select('id', 'jenis_prestasi_id', 'poin', 'created_at')
            ->with(['jenisPrestasi:id,nama_prestasi'])
            ->where('siswa_id', $siswa->id)
            ->where('status_verifikasi', 'verified')
            ->latest()->limit(3)->get();
            
        $sanksiAktifList = Sanksi::select('id', 'nama_sanksi', 'tanggal_mulai', 'tanggal_selesai')
            ->whereHas('pelanggaran', function($q) use ($siswa) {
                $q->where('siswa_id', $siswa->id);
            })->where('status_sanksi', 'aktif')->limit(3)->get();
        
        return view('dashboard.ortu', compact(
            'siswa', 'biodata', 'totalPelanggaran', 'totalPrestasi', 'totalPoin',
            'sanksiAktif', 'pelanggaranTerbaru', 'prestasiTerbaru', 'sanksiAktifList'
        ));
    }

    private function defaultDashboard()
    {
        return view('dashboard.index');
    }

    private function bkDashboard()
    {
        $user = Auth::user();
        
        // Statistik BK
        $totalBK = BimbinganKonseling::count();
        $bkBulanIni = BimbinganKonseling::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $totalSiswa = Siswa::count();
        $siswaBermasalah = Siswa::whereHas('pelanggarans', function($q) {
            $q->where('status_verifikasi', 'diverifikasi');
        })->count();
        
        // BK terbaru
        $bkTerbaru = BimbinganKonseling::with(['siswa:id,nama_siswa,kelas_id', 'siswa.kelas:id,nama_kelas'])
            ->latest()
            ->limit(10)
            ->get();
        
        // Chart 3 bulan terakhir
        $statistikBulanan = ['labels' => [], 'bk' => []];
        for ($i = 2; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $statistikBulanan['labels'][] = $bulan->format('M Y');
            $statistikBulanan['bk'][] = BimbinganKonseling::whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->count();
        }
        
        return view('dashboard.bk', compact(
            'totalBK',
            'bkBulanIni',
            'totalSiswa',
            'siswaBermasalah',
            'bkTerbaru',
            'statistikBulanan'
        ));
    }

    private function kepalaSekolahDashboard()
    {
        // Statistik Lengkap untuk Kepala Sekolah
        $totalSiswa = Siswa::count();
        $totalGuru = \App\Models\Guru::count();
        $totalKelas = \App\Models\Kelas::count();
        $totalPelanggaran = Pelanggaran::where('status_verifikasi', 'diverifikasi')->count();
        $totalPrestasi = Prestasi::where('status_verifikasi', 'verified')->count();
        $totalBK = BimbinganKonseling::count();
        $sanksiAktif = Sanksi::where('status_sanksi', 'aktif')->count();
        
        // Pelanggaran per bulan (6 bulan terakhir)
        $pelanggaranPerBulan = [];
        $prestasiPerBulan = [];
        $labels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $labels[] = $bulan->format('M Y');
            $pelanggaranPerBulan[] = Pelanggaran::whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->where('status_verifikasi', 'diverifikasi')
                ->count();
            $prestasiPerBulan[] = Prestasi::whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->where('status_verifikasi', 'verified')
                ->count();
        }
        
        // Top 5 siswa bermasalah
        $siswaBermasalah = Siswa::select('siswas.*')
            ->withCount(['pelanggarans' => function($q) {
                $q->where('status_verifikasi', 'diverifikasi');
            }])
            ->having('pelanggarans_count', '>', 0)
            ->orderBy('pelanggarans_count', 'DESC')
            ->limit(5)
            ->get();
        
        // Top 5 siswa berprestasi
        $siswaBerprestasi = Siswa::select('siswas.*')
            ->withCount(['prestasis' => function($q) {
                $q->where('status_verifikasi', 'verified');
            }])
            ->having('prestasis_count', '>', 0)
            ->orderBy('prestasis_count', 'DESC')
            ->limit(5)
            ->get();
        
        // Pelanggaran per kategori (top 5 jenis pelanggaran)
        $pelanggaranPerKategori = DB::table('pelanggarans')
            ->join('jenis_pelanggarans', 'pelanggarans.jenis_pelanggaran_id', '=', 'jenis_pelanggarans.id')
            ->select('jenis_pelanggarans.nama_pelanggaran', DB::raw('count(*) as total'))
            ->where('pelanggarans.status_verifikasi', 'diverifikasi')
            ->groupBy('jenis_pelanggarans.nama_pelanggaran')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
        
        return view('dashboard.kepala-sekolah', compact(
            'totalSiswa',
            'totalGuru',
            'totalKelas',
            'totalPelanggaran',
            'totalPrestasi',
            'totalBK',
            'sanksiAktif',
            'pelanggaranPerBulan',
            'prestasiPerBulan',
            'labels',
            'siswaBermasalah',
            'siswaBerprestasi',
            'pelanggaranPerKategori'
        ));
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