<?php

namespace App\Http\Controllers;

use App\Models\Sanksi;
use App\Models\Siswa;
use App\Models\Pelanggaran;
use App\Models\Prestasi;
use App\Models\PelaksanaanSanksi;
use App\Models\Kelas;
use App\Models\RekomendasiExecutive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class KepalaSekolahController extends Controller
{
    // 1. DASHBOARD EXECUTIVE
    public function dashboard()
    {
        $overview = [
            'total_siswa' => Siswa::count(),
            'total_pelanggaran' => Pelanggaran::whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi', 'verified'])->count(),
            'total_prestasi' => Prestasi::whereIn('status_verifikasi', ['verified', 'diverifikasi', 'terverifikasi'])->count(),
            'sanksi_aktif' => Sanksi::where('status_sanksi', 'aktif')->count(),
        ];
        
        // KPI (Key Performance Indicator)
        $kpi = [
            'efektivitas_sanksi' => $this->hitungEfektivitasSanksi(),
            'tingkat_disiplin' => $this->hitungTingkatDisiplin(),
            'rasio_prestasi' => $this->hitungRasioPrestasi(),
            'trend_pelanggaran' => $this->getTrendStatus()
        ];
        
        // Grafik Trend (12 bulan)
        $trendBulanan = $this->getTrendBulanan();
        
        // Alert & Notification
        $alerts = $this->getAlerts();
        
        return view('kepala-sekolah.dashboard', compact('overview', 'kpi', 'trendBulanan', 'alerts'));
    }
    
    // 2. MONITORING PELANGGARAN
    public function monitoringPelanggaran(Request $request)
    {
        $query = Pelanggaran::with(['siswa.kelas', 'jenisPelanggaran'])
            ->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi', 'verified']);
        
        if ($request->kelas_id) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }
        
        if ($request->periode) {
            $dates = $this->getPeriodeDates($request->periode);
            $query->whereBetween('created_at', $dates);
        }
        
        $pelanggarans = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Siswa dengan Pelanggaran Berat (poin >= 100)
        $siswaBerat = Siswa::withSum(['pelanggarans' => function($q) {
            $q->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi', 'verified']);
        }], 'poin')
        ->having('pelanggarans_sum_poin', '>=', 100)
        ->orderBy('pelanggarans_sum_poin', 'desc')
        ->get();
        
        // Trend Bulanan
        $trendBulanan = $this->getTrendPelanggaranBulanan();
        
        $kelas = Kelas::all();
        
        return view('kepala-sekolah.monitoring-pelanggaran', compact('pelanggarans', 'siswaBerat', 'trendBulanan', 'kelas'));
    }
    
    // 3. MONITORING SANKSI
    public function monitoringSanksi(Request $request)
    {
        $query = Sanksi::with(['siswa.kelas', 'pelaksanaanSanksis']);
        
        if ($request->status) {
            $query->where('status_sanksi', $request->status);
        }
        
        if ($request->kelas_id) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }
        
        $sanksis = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Efektivitas Sanksi dengan proteksi
        $totalSanksi = Sanksi::count();
        $sanksiSelesai = Sanksi::where('status_sanksi', 'selesai')->count();
        $sanksiAktif = Sanksi::where('status_sanksi', 'aktif')->count();
        
        $efektivitas = [
            'total_sanksi' => $totalSanksi,
            'sanksi_selesai' => $sanksiSelesai,
            'sanksi_aktif' => $sanksiAktif,
            'tingkat_kepatuhan' => $this->hitungTingkatKepatuhan(),
            'efektivitas_per_kategori' => $this->getEfektivitasPerKategori()
        ];
        
        // Kasus Eskalasi dengan proteksi
        $kasusEskalasi = Siswa::with('kelas')
            ->withCount('sanksis')
            ->has('sanksis', '>=', 2)
            ->orderBy('sanksis_count', 'desc')
            ->limit(10)
            ->get();
        
        $kelas = Kelas::all();
        
        return view('kepala-sekolah.monitoring-sanksi', compact('sanksis', 'efektivitas', 'kasusEskalasi', 'kelas'));
    }
    
    // 4. MONITORING PRESTASI
    public function monitoringPrestasi(Request $request)
    {
        $query = Prestasi::with(['siswa.kelas', 'jenisPrestasi'])
            ->whereIn('status_verifikasi', ['verified', 'diverifikasi', 'terverifikasi']);
        
        if ($request->kelas_id) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }
        
        $prestasis = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Trend Prestasi
        $trendPrestasi = $this->getTrendPrestasiBulanan();
        
        // Top Siswa
        $topSiswa = Siswa::withCount(['prestasis' => function($q) {
            $q->whereIn('status_verifikasi', ['verified', 'diverifikasi', 'terverifikasi']);
        }])
        ->having('prestasis_count', '>', 0)
        ->orderBy('prestasis_count', 'desc')
        ->limit(10)
        ->get();
        
        $kelas = Kelas::all();
        
        return view('kepala-sekolah.monitoring-prestasi', compact('prestasis', 'trendPrestasi', 'topSiswa', 'kelas'));
    }
    
    // 5. LAPORAN EXECUTIVE
    public function laporanExecutive(Request $request)
    {
        try {
            $periode = $request->periode ?? 'bulan_ini';
            $dates = $this->getPeriodeDates($periode);
            
            $report = [
                'periode' => $periode,
                'ringkasan' => $this->getRingkasanExecutive($dates),
                'analytics' => $this->getAnalytics($dates),
                'insights' => $this->getInsights($dates),
                'rekomendasi' => \DB::table('rekomendasi_executives')->where('is_active', true)->latest()->get()
            ];
            
            return view('kepala-sekolah.laporan-executive', compact('report'));
        } catch (\Exception $e) {
            $periode = $request->periode ?? 'bulan_ini';
            $dates = $this->getPeriodeDates($periode);
            
            $report = [
                'periode' => $periode,
                'ringkasan' => $this->getRingkasanExecutive($dates),
                'analytics' => $this->getAnalytics($dates),
                'insights' => $this->getInsights($dates),
                'rekomendasi' => []
            ];
            
            return view('kepala-sekolah.laporan-executive', compact('report'));
        }
    }
    
    public function storeRekomendasi(Request $request)
    {
        try {
            $request->validate([
                'rekomendasi' => 'required|string',
                'periode' => 'nullable|string'
            ]);
            
            \DB::table('rekomendasi_executives')->insert([
                'rekomendasi' => $request->rekomendasi,
                'periode' => $request->periode,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            return redirect()->back()->with('success', 'Rekomendasi berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Tabel rekomendasi belum tersedia. Silakan hubungi administrator.');
        }
    }
    
    public function deleteRekomendasi($id)
    {
        try {
            \DB::table('rekomendasi_executives')->where('id', $id)->delete();
            return redirect()->back()->with('success', 'Rekomendasi berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus rekomendasi.');
        }
    }
    
    public function exportLaporanPDF(Request $request)
    {
        $periode = $request->periode ?? 'bulan_ini';
        $dates = $this->getPeriodeDates($periode);
        
        $report = [
            'periode' => $periode,
            'ringkasan' => $this->getRingkasanExecutive($dates),
            'analytics' => $this->getAnalytics($dates),
            'insights' => $this->getInsights($dates)
        ];
        
        $pdf = PDF::loadView('kepala-sekolah.laporan-pdf', compact('report'));
        return $pdf->download('laporan-executive-' . date('Y-m-d') . '.pdf');
    }
    
    // HELPER METHODS
    private function hitungEfektivitasSanksi()
    {
        $total = Sanksi::count();
        $selesai = Sanksi::where('status_sanksi', 'selesai')->count();
        return $total > 0 ? round(($selesai / $total) * 100, 2) : 0;
    }
    
    private function hitungTingkatDisiplin()
    {
        $totalSiswa = Siswa::count();
        $siswaBermasalah = Siswa::whereHas('pelanggarans')->count();
        $siswaDisiplin = $totalSiswa - $siswaBermasalah;
        return $totalSiswa > 0 ? round(($siswaDisiplin / $totalSiswa) * 100, 2) : 0;
    }
    
    private function hitungRasioPrestasi()
    {
        $totalSiswa = Siswa::count();
        $siswaBerprestasi = Siswa::whereHas('prestasis')->count();
        return $totalSiswa > 0 ? round(($siswaBerprestasi / $totalSiswa) * 100, 2) : 0;
    }
    
    private function getTrendStatus()
    {
        $bulanIni = Pelanggaran::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi', 'verified'])
            ->count();
        $bulanLalu = Pelanggaran::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi', 'verified'])
            ->count();
        
        if ($bulanLalu == 0) return 'stabil';
        $perubahan = (($bulanIni - $bulanLalu) / $bulanLalu) * 100;
        
        if ($perubahan > 10) return 'naik';
        if ($perubahan < -10) return 'turun';
        return 'stabil';
    }
    
    private function getTrendBulanan()
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $data[] = [
                'bulan' => $bulan->format('M Y'),
                'pelanggaran' => Pelanggaran::whereYear('created_at', $bulan->year)
                    ->whereMonth('created_at', $bulan->month)
                    ->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi', 'verified'])
                    ->count(),
                'prestasi' => Prestasi::whereYear('created_at', $bulan->year)
                    ->whereMonth('created_at', $bulan->month)
                    ->whereIn('status_verifikasi', ['verified', 'diverifikasi', 'terverifikasi'])
                    ->count()
            ];
        }
        return $data;
    }
    
    private function getAlerts()
    {
        $alerts = [];
        
        $siswaBeratCount = Siswa::withSum(['pelanggarans' => function($q) {
            $q->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi', 'verified']);
        }], 'poin')
        ->having('pelanggarans_sum_poin', '>=', 100)
        ->count();
        
        if ($siswaBeratCount > 0) {
            $alerts[] = [
                'type' => 'danger',
                'icon' => 'exclamation-triangle',
                'message' => "$siswaBeratCount siswa dengan poin >= 100 memerlukan perhatian"
            ];
        }
        
        if ($this->getTrendStatus() == 'naik') {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'chart-line',
                'message' => 'Trend pelanggaran meningkat dari bulan lalu'
            ];
        }
        
        return $alerts;
    }
    
    private function hitungTingkatKepatuhan()
    {
        $total = PelaksanaanSanksi::count();
        $selesai = PelaksanaanSanksi::where('status', 'selesai')->count();
        return $total > 0 ? round(($selesai / $total) * 100, 2) : 0;
    }
    
    private function getEfektivitasPerKategori()
    {
        return DB::table('sanksis')
            ->select('kategori_poin', 
                    DB::raw('count(*) as total'),
                    DB::raw('sum(case when status_sanksi = "selesai" then 1 else 0 end) as selesai'))
            ->groupBy('kategori_poin')
            ->get()
            ->map(function($item) {
                $item->efektivitas = $item->total > 0 ? round(($item->selesai / $item->total) * 100, 2) : 0;
                return $item;
            });
    }
    
    private function getTrendPelanggaranBulanan()
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $data[] = [
                'bulan' => $bulan->format('M Y'),
                'total' => Pelanggaran::whereYear('created_at', $bulan->year)
                    ->whereMonth('created_at', $bulan->month)
                    ->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi', 'verified'])
                    ->count()
            ];
        }
        return $data;
    }
    
    private function getTrendPrestasiBulanan()
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $data[] = [
                'bulan' => $bulan->format('M Y'),
                'total' => Prestasi::whereYear('created_at', $bulan->year)
                    ->whereMonth('created_at', $bulan->month)
                    ->whereIn('status_verifikasi', ['verified', 'diverifikasi', 'terverifikasi'])
                    ->count()
            ];
        }
        return $data;
    }
    
    private function getRingkasanExecutive($dates)
    {
        $totalPelanggaran = Pelanggaran::whereBetween('created_at', $dates)
            ->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi', 'verified'])->count();
        $totalPrestasi = Prestasi::whereBetween('created_at', $dates)
            ->whereIn('status_verifikasi', ['verified', 'diverifikasi', 'terverifikasi'])->count();
        $totalSanksi = Sanksi::whereBetween('created_at', $dates)->count();
        $siswaTerlibat = Pelanggaran::whereBetween('created_at', $dates)
            ->distinct('siswa_id')->count('siswa_id');
        
        return [
            'total_pelanggaran' => $totalPelanggaran,
            'total_prestasi' => $totalPrestasi,
            'total_sanksi' => $totalSanksi,
            'siswa_terlibat' => $siswaTerlibat,
            'total_siswa' => Siswa::count(),
            'rasio_pelanggaran' => Siswa::count() > 0 ? round(($siswaTerlibat / Siswa::count()) * 100, 2) : 0
        ];
    }
    
    private function getAnalytics($dates)
    {
        $topPelanggaran = DB::table('pelanggarans')
            ->join('jenis_pelanggarans', 'pelanggarans.jenis_pelanggaran_id', '=', 'jenis_pelanggarans.id')
            ->whereBetween('pelanggarans.created_at', $dates)
            ->whereIn('pelanggarans.status_verifikasi', ['diverifikasi', 'terverifikasi', 'verified'])
            ->select('jenis_pelanggarans.nama_pelanggaran', DB::raw('count(*) as total'))
            ->groupBy('jenis_pelanggarans.nama_pelanggaran')
            ->orderBy('total', 'desc')
            ->limit(5)->get();
        
        $topKelas = DB::table('pelanggarans')
            ->join('siswas', 'pelanggarans.siswa_id', '=', 'siswas.id')
            ->join('kelas', 'siswas.kelas_id', '=', 'kelas.id')
            ->whereBetween('pelanggarans.created_at', $dates)
            ->whereIn('pelanggarans.status_verifikasi', ['diverifikasi', 'terverifikasi', 'verified'])
            ->select('kelas.nama_kelas', DB::raw('count(*) as total'))
            ->groupBy('kelas.nama_kelas')
            ->orderBy('total', 'desc')
            ->limit(5)->get();
        
        $topPrestasi = DB::table('prestasis')
            ->join('jenis_prestasis', 'prestasis.jenis_prestasi_id', '=', 'jenis_prestasis.id')
            ->whereBetween('prestasis.created_at', $dates)
            ->whereIn('prestasis.status_verifikasi', ['verified', 'diverifikasi', 'terverifikasi'])
            ->select('jenis_prestasis.nama_prestasi', DB::raw('count(*) as total'))
            ->groupBy('jenis_prestasis.nama_prestasi')
            ->orderBy('total', 'desc')
            ->limit(5)->get();
        
        return [
            'top_pelanggaran' => $topPelanggaran,
            'top_kelas' => $topKelas,
            'top_prestasi' => $topPrestasi
        ];
    }
    
    private function getInsights($dates)
    {
        $insights = [];
        
        $efektivitas = $this->hitungEfektivitasSanksi();
        if ($efektivitas < 50) {
            $insights[] = "Efektivitas sanksi rendah ($efektivitas%), perlu evaluasi";
        } elseif ($efektivitas >= 80) {
            $insights[] = "Efektivitas sanksi baik ($efektivitas%), pertahankan";
        }
        
        $tingkatDisiplin = $this->hitungTingkatDisiplin();
        if ($tingkatDisiplin < 70) {
            $insights[] = "Tingkat disiplin perlu ditingkatkan ($tingkatDisiplin%)";
        } elseif ($tingkatDisiplin >= 85) {
            $insights[] = "Tingkat disiplin sangat baik ($tingkatDisiplin%)";
        }
        
        $rasioPrestasi = $this->hitungRasioPrestasi();
        if ($rasioPrestasi < 30) {
            $insights[] = "Rasio prestasi rendah ($rasioPrestasi%), perlu program motivasi";
        } elseif ($rasioPrestasi >= 50) {
            $insights[] = "Rasio prestasi baik ($rasioPrestasi%), siswa aktif berprestasi";
        }
        
        $trendStatus = $this->getTrendStatus();
        if ($trendStatus == 'naik') {
            $insights[] = "Trend pelanggaran meningkat, perlu tindakan preventif";
        } elseif ($trendStatus == 'turun') {
            $insights[] = "Trend pelanggaran menurun, program berjalan efektif";
        }
        
        return $insights;
    }
    
    private function getPeriodeDates($periode)
    {
        switch ($periode) {
            case 'hari_ini':
                return [now()->startOfDay(), now()->endOfDay()];
            case 'minggu_ini':
                return [now()->startOfWeek(), now()->endOfWeek()];
            case 'bulan_ini':
                return [now()->startOfMonth(), now()->endOfMonth()];
            case 'tahun_ini':
                return [now()->startOfYear(), now()->endOfYear()];
            default:
                return [now()->startOfMonth(), now()->endOfMonth()];
        }
    }
}
