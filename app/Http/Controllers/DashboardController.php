<?php

namespace App\Http\Controllers;

use App\Models\Pelanggaran;
use App\Models\Prestasi;
use App\Models\Sanksi;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPelanggaran = Pelanggaran::count();
        $totalPrestasi = Prestasi::count();
        $sanksiAktif = Sanksi::where('status_sanksi', 'aktif')->count();
        
        $pelanggaranPerBulan = Pelanggaran::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('COUNT(*) as total')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('bulan')
        ->pluck('total', 'bulan');

        $prestasiPerBulan = Prestasi::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('COUNT(*) as total')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('bulan')
        ->pluck('total', 'bulan');

        $sanksiPerBulan = Sanksi::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('COUNT(*) as total')
        )
        ->where('status_sanksi', 'aktif')
        ->whereYear('created_at', date('Y'))
        ->groupBy('bulan')
        ->pluck('total', 'bulan');

        $topPelanggar = Siswa::with('kelas')
            ->withCount('pelanggarans')
            ->orderBy('pelanggarans_count', 'desc')
            ->take(5)
            ->get();

        $topPrestasi = Siswa::with('kelas')
            ->withCount('prestasis')
            ->orderBy('prestasis_count', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalPelanggaran',
            'totalPrestasi',
            'sanksiAktif',
            'pelanggaranPerBulan',
            'prestasiPerBulan',
            'sanksiPerBulan',
            'topPelanggar',
            'topPrestasi'
        ));
    }
}
