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
        try {
            $user = Auth::user();
            
            switch ($user->role) {
                case 'admin':
                case 'kesiswaan':
                    return $this->adminDashboard();
                case 'guru':
                    return $this->guruDashboard();
                case 'bk':
                    return $this->bkDashboard();
                case 'kepala_sekolah':
                    return redirect()->route('kepala-sekolah.dashboard');
                case 'siswa':
                    return $this->siswaDashboard();
                case 'ortu':
                    return $this->ortuDashboard();
                default:
                    return view('dashboard.index', ['message' => 'Dashboard ' . $user->role]);
            }
        } catch (\Exception $e) {
            return view('dashboard.index', ['error' => 'Error loading dashboard']);
        }
    }

    private function adminDashboard()
    {
        try {
            $totalSiswa = Siswa::count();
            $totalPelanggaran = Pelanggaran::count();
            $totalPrestasi = Prestasi::count();
            $sanksiAktif = Sanksi::where('status_sanksi', 'aktif')->count();
            $totalUsers = User::count();
            $totalBK = BimbinganKonseling::count();
            
            $pelanggaranPerBulan = array_fill(1, 12, 0);
            $prestasiPerBulan = array_fill(1, 12, 0);
            $pelanggaranPerKategori = collect();
            $topSiswa = collect();

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
        } catch (\Exception $e) {
            return view('dashboard.admin', [
                'totalSiswa' => 0,
                'totalPelanggaran' => 0,
                'totalPrestasi' => 0,
                'sanksiAktif' => 0,
                'totalUsers' => 0,
                'totalBK' => 0,
                'pelanggaranPerBulan' => array_fill(1, 12, 0),
                'prestasiPerBulan' => array_fill(1, 12, 0),
                'pelanggaranPerKategori' => collect(),
                'topSiswa' => collect()
            ]);
        }
    }

    private function guruDashboard()
    {
        return view('dashboard.guru', [
            'message' => 'Dashboard guru',
            'totalPelanggaran' => 0,
            'totalPrestasi' => 0,
            'pelanggaranBulanIni' => 0,
            'prestasiBulanIni' => 0,
            'pelanggaranTerbaru' => collect(),
            'statistikBulanan' => ['labels' => [], 'pelanggaran' => [], 'prestasi' => []]
        ]);
    }

    private function siswaDashboard()
    {
        try {
            $user = Auth::user();
            $siswa = Siswa::where('users_id', $user->id)->first();
            
            return view('dashboard.siswa', [
                'siswa' => $siswa,
                'totalPelanggaran' => 0,
                'totalPrestasi' => 0,
                'totalPoin' => 0,
                'sanksiAktif' => 0,
                'pelanggaranTerbaru' => collect(),
                'prestasiTerbaru' => collect(),
                'sanksiAktifList' => collect()
            ]);
        } catch (\Exception $e) {
            return view('dashboard.siswa', [
                'siswa' => null,
                'totalPelanggaran' => 0,
                'totalPrestasi' => 0,
                'totalPoin' => 0,
                'sanksiAktif' => 0,
                'pelanggaranTerbaru' => collect(),
                'prestasiTerbaru' => collect(),
                'sanksiAktifList' => collect()
            ]);
        }
    }

    private function bkDashboard()
    {
        try {
            $user = Auth::user();
            $guru = \App\Models\Guru::where('users_id', $user->id)->first();
            
            $totalBK = BimbinganKonseling::count();
            $bkBulanIni = BimbinganKonseling::whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->count();
            
            $siswaBermasalah = Siswa::whereHas('pelanggarans', function($q) {
                $q->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi'])
                  ->havingRaw('SUM(poin) >= 50');
            })->count();
            
            $totalSiswa = Siswa::count();
            
            $bkTerbaru = BimbinganKonseling::with(['siswa.kelas', 'guru'])
                ->latest('tanggal')
                ->take(5)
                ->get();
            
            $labels = [];
            $bkData = [];
            for ($i = 2; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $labels[] = $date->format('M Y');
                $bkData[] = BimbinganKonseling::whereMonth('tanggal', $date->month)
                    ->whereYear('tanggal', $date->year)
                    ->count();
            }
            
            $statistikBulanan = [
                'labels' => $labels,
                'bk' => $bkData
            ];
            
            return view('dashboard.bk', compact(
                'totalBK',
                'bkBulanIni',
                'siswaBermasalah',
                'totalSiswa',
                'bkTerbaru',
                'statistikBulanan'
            ));
        } catch (\Exception $e) {
            return view('dashboard.bk', [
                'totalBK' => 0,
                'bkBulanIni' => 0,
                'siswaBermasalah' => 0,
                'totalSiswa' => 0,
                'bkTerbaru' => collect(),
                'statistikBulanan' => ['labels' => [], 'bk' => []]
            ]);
        }
    }

    private function ortuDashboard()
    {
        try {
            $user = Auth::user();
            $biodata = \App\Models\BiodataOrtu::where('user_id', $user->id)->first();
            
            if (!$biodata) {
                return view('dashboard.ortu', [
                    'siswa' => null,
                    'message' => 'Silakan lengkapi biodata orang tua terlebih dahulu.',
                    'status' => null
                ]);
            }
            
            if ($biodata->status_approval === 'pending') {
                return view('dashboard.ortu', [
                    'siswa' => null,
                    'message' => 'Biodata Anda sedang dalam proses verifikasi oleh admin.',
                    'status' => 'pending'
                ]);
            }
            
            if ($biodata->status_approval === 'rejected') {
                return view('dashboard.ortu', [
                    'siswa' => null,
                    'message' => 'Biodata Anda ditolak. Alasan: ' . ($biodata->rejection_reason ?? 'Tidak ada alasan'),
                    'status' => 'rejected'
                ]);
            }
            
            $siswa = Siswa::with(['kelas', 'tahunAjaran'])->find($biodata->siswa_id);
            
            if (!$siswa) {
                return view('dashboard.ortu', [
                    'siswa' => null,
                    'message' => 'Data siswa tidak ditemukan.',
                    'status' => null
                ]);
            }
            
            $totalPelanggaran = Pelanggaran::where('siswa_id', $siswa->id)
                ->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi'])
                ->count();
            
            $totalPrestasi = Prestasi::where('siswa_id', $siswa->id)
                ->where('status_verifikasi', 'verified')
                ->count();
            
            $totalPoin = Pelanggaran::where('siswa_id', $siswa->id)
                ->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi'])
                ->sum('poin');
            
            $sanksiAktif = Sanksi::where('siswa_id', $siswa->id)
                ->where('status_sanksi', 'aktif')
                ->count();
            
            $pelanggaranTerbaru = Pelanggaran::where('siswa_id', $siswa->id)
                ->with(['jenisPelanggaran', 'guru'])
                ->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi'])
                ->latest()
                ->take(5)
                ->get();
            
            $prestasiTerbaru = Prestasi::where('siswa_id', $siswa->id)
                ->with(['jenisPrestasi', 'guru'])
                ->where('status_verifikasi', 'verified')
                ->latest()
                ->take(5)
                ->get();
            
            $sanksiAktifList = Sanksi::where('siswa_id', $siswa->id)
                ->with(['pelanggaran.jenisPelanggaran'])
                ->where('status_sanksi', 'aktif')
                ->get();
            
            return view('dashboard.ortu', compact(
                'siswa',
                'biodata',
                'totalPelanggaran',
                'totalPrestasi',
                'totalPoin',
                'sanksiAktif',
                'pelanggaranTerbaru',
                'prestasiTerbaru',
                'sanksiAktifList'
            ));
        } catch (\Exception $e) {
            return view('dashboard.ortu', [
                'siswa' => null,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'status' => null
            ]);
        }
    }
}