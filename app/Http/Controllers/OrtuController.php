<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BiodataOrtu;
use App\Models\Siswa;
use App\Models\Pelanggaran;
use App\Models\Prestasi;
use App\Models\Sanksi;
use App\Models\BimbinganKonseling;
use Illuminate\Support\Facades\Auth;

class OrtuController extends Controller
{
    private function getSiswaOrtu()
    {
        $biodata = BiodataOrtu::where('user_id', Auth::id())
            ->where('status_approval', 'approved')
            ->first();
        
        if (!$biodata) {
            return null;
        }
        
        return Siswa::with(['kelas', 'tahunAjaran'])->find($biodata->siswa_id);
    }
    
    public function pelanggaran()
    {
        $siswa = $this->getSiswaOrtu();
        
        if (!$siswa) {
            return redirect()->route('dashboard')
                ->with('error', 'Akses ditolak. Biodata belum disetujui atau data siswa tidak ditemukan.');
        }
        
        $pelanggarans = Pelanggaran::where('siswa_id', $siswa->id)
            ->with(['jenisPelanggaran', 'guru'])
            ->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi'])
            ->latest()
            ->paginate(10);
        
        $totalPoin = Pelanggaran::where('siswa_id', $siswa->id)
            ->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi'])
            ->sum('poin');
        
        return view('ortu.pelanggaran', compact('siswa', 'pelanggarans', 'totalPoin'));
    }
    
    public function prestasi()
    {
        $siswa = $this->getSiswaOrtu();
        
        if (!$siswa) {
            return redirect()->route('dashboard')
                ->with('error', 'Akses ditolak. Biodata belum disetujui atau data siswa tidak ditemukan.');
        }
        
        $prestasis = Prestasi::where('siswa_id', $siswa->id)
            ->with(['jenisPrestasi', 'guru'])
            ->where('status_verifikasi', 'verified')
            ->latest()
            ->paginate(10);
        
        return view('ortu.prestasi', compact('siswa', 'prestasis'));
    }
    
    public function sanksi()
    {
        $siswa = $this->getSiswaOrtu();
        
        if (!$siswa) {
            return redirect()->route('dashboard')
                ->with('error', 'Akses ditolak. Biodata belum disetujui atau data siswa tidak ditemukan.');
        }
        
        $sanksis = Sanksi::where('siswa_id', $siswa->id)
            ->with(['pelanggaran.jenisPelanggaran'])
            ->latest()
            ->paginate(10);
        
        return view('ortu.sanksi', compact('siswa', 'sanksis'));
    }
    
    public function bimbingan()
    {
        $siswa = $this->getSiswaOrtu();
        
        if (!$siswa) {
            return redirect()->route('dashboard')
                ->with('error', 'Akses ditolak. Biodata belum disetujui atau data siswa tidak ditemukan.');
        }
        
        $bimbingans = BimbinganKonseling::where('siswa_id', $siswa->id)
            ->with(['guru', 'siswa'])
            ->latest()
            ->paginate(10);
        
        return view('ortu.bimbingan', compact('siswa', 'bimbingans'));
    }
    
    public function profil()
    {
        $siswa = $this->getSiswaOrtu();
        
        if (!$siswa) {
            return redirect()->route('dashboard')
                ->with('error', 'Akses ditolak. Biodata belum disetujui atau data siswa tidak ditemukan.');
        }
        
        $biodata = BiodataOrtu::where('user_id', Auth::id())->first();
        
        $stats = [
            'total_pelanggaran' => Pelanggaran::where('siswa_id', $siswa->id)
                ->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi'])
                ->count(),
            'total_prestasi' => Prestasi::where('siswa_id', $siswa->id)
                ->where('status_verifikasi', 'verified')
                ->count(),
            'total_poin' => Pelanggaran::where('siswa_id', $siswa->id)
                ->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi'])
                ->sum('poin'),
            'sanksi_aktif' => Sanksi::where('siswa_id', $siswa->id)
                ->where('status_sanksi', 'aktif')
                ->count()
        ];
        
        return view('ortu.profil', compact('siswa', 'biodata', 'stats'));
    }
}
