<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Pelanggaran;
use App\Models\Prestasi;
use App\Models\JenisPelanggaran;
use App\Models\JenisPrestasi;
use App\Models\Guru;
use App\Models\KomunikasiOrtu;
use App\Models\BiodataOrtu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class WaliKelasController extends Controller
{
    // Dashboard Wali Kelas
    public function dashboard()
    {
        $user = Auth::user();
        
        $kelas = $user->currentHomeroomClass();
        if (!$kelas) {
            return redirect()->route('dashboard')->with('error', 'Anda belum ditugaskan sebagai wali kelas.');
        }
        
        $kelas->load(['siswas', 'tahunAjaran']);
        
        $totalSiswa = $kelas->siswas->count();
        $siswaLakiLaki = $kelas->siswas->where('jenis_kelamin', 'L')->count();
        $siswaPerempuan = $kelas->siswas->where('jenis_kelamin', 'P')->count();
        
        $totalPelanggaran = Pelanggaran::whereIn('siswa_id', $kelas->siswas->pluck('id'))->count();
        $pelanggaranMenunggu = Pelanggaran::whereIn('siswa_id', $kelas->siswas->pluck('id'))
            ->where('status_verifikasi', 'menunggu')->count();
        
        $totalPrestasi = Prestasi::whereIn('siswa_id', $kelas->siswas->pluck('id'))->count();
        $prestasiMenunggu = Prestasi::whereIn('siswa_id', $kelas->siswas->pluck('id'))
            ->where('status_verifikasi', 'pending')->count();
        
        $siswaList = $kelas->siswas()->with(['pelanggarans', 'prestasis'])->get()->map(function($siswa) {
            return [
                'id' => $siswa->id,
                'nama' => $siswa->nama_siswa,
                'nis' => $siswa->nis,
                'jenis_kelamin' => $siswa->jenis_kelamin,
                'total_poin_pelanggaran' => $siswa->pelanggarans()->where('status_verifikasi', 'diverifikasi')->sum('poin'),
                'total_poin_prestasi' => $siswa->prestasis()->where('status_verifikasi', 'verified')->sum('poin'),
                'jumlah_pelanggaran' => $siswa->pelanggarans()->count(),
                'jumlah_prestasi' => $siswa->prestasis()->count(),
            ];
        });
        
        return view('wali-kelas.dashboard', compact(
            'kelas', 'totalSiswa', 'siswaLakiLaki', 'siswaPerempuan',
            'totalPelanggaran', 'pelanggaranMenunggu', 'totalPrestasi', 'prestasiMenunggu',
            'siswaList'
        ));
    }
    
    // Daftar Siswa Kelas
    public function siswa()
    {
        $user = Auth::user();
        $kelas = $user->currentHomeroomClass();
        
        if (!$kelas) {
            return redirect()->route('dashboard')->with('error', 'Anda belum ditugaskan sebagai wali kelas.');
        }
        
        $siswas = $kelas->siswas()->with(['kelas', 'tahunAjaran'])->paginate(20);
        
        return view('wali-kelas.siswa.index', compact('siswas', 'kelas'));
    }
    
    // Detail Siswa
    public function siswaShow($id)
    {
        $user = Auth::user();
        $kelas = $user->currentHomeroomClass();
        
        if (!$kelas) {
            return redirect()->route('dashboard')->with('error', 'Anda belum ditugaskan sebagai wali kelas.');
        }
        
        $siswa = Siswa::with(['kelas', 'pelanggarans.jenisPelanggaran', 'prestasis.jenisPrestasi'])
            ->where('kelas_id', $kelas->id)
            ->findOrFail($id);
        
        $totalPoinPelanggaran = $siswa->pelanggarans()->where('status_verifikasi', 'diverifikasi')->sum('poin');
        $totalPoinPrestasi = $siswa->prestasis()->where('status_verifikasi', 'verified')->sum('poin');
        
        return view('wali-kelas.siswa.show', compact('siswa', 'totalPoinPelanggaran', 'totalPoinPrestasi'));
    }
    
    // Form Input Pelanggaran
    public function pelanggaranCreate()
    {
        $user = Auth::user();
        $kelas = $user->currentHomeroomClass();
        
        if (!$kelas) {
            return redirect()->route('dashboard')->with('error', 'Anda belum ditugaskan sebagai wali kelas.');
        }
        
        $siswas = $kelas->siswas()->orderBy('nama_siswa')->get();
        $jenisPelanggarans = JenisPelanggaran::orderBy('nama_pelanggaran')->get();
        
        $guru = Guru::where('users_id', $user->id)->first();
        
        if (!$guru) {
            return redirect()->route('wali-kelas.dashboard')
                ->with('error', 'Data guru Anda belum terdaftar. Hubungi admin.');
        }
        
        return view('wali-kelas.pelanggaran.create', compact('siswas', 'jenisPelanggarans', 'guru', 'kelas'));
    }
    
    // Simpan Pelanggaran
    public function pelanggaranStore(Request $request)
    {
        $user = Auth::user();
        $guru = Guru::where('users_id', $user->id)->first();
        
        if (!$guru) {
            return redirect()->route('wali-kelas.dashboard')
                ->with('error', 'Data guru Anda belum terdaftar.');
        }
        
        $kelas = $user->currentHomeroomClass();
        if (!$kelas) {
            return redirect()->route('dashboard')->with('error', 'Anda belum ditugaskan sebagai wali kelas.');
        }
        
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'jenis_pelanggaran_id' => 'required|exists:jenis_pelanggarans,id',
            'tanggal_pelanggaran' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);
        
        $siswa = Siswa::where('id', $validated['siswa_id'])
            ->where('kelas_id', $kelas->id)
            ->firstOrFail();
        
        $jenis = JenisPelanggaran::findOrFail($validated['jenis_pelanggaran_id']);
        
        Pelanggaran::create([
            'siswa_id' => $siswa->id,
            'guru_pencatat' => $guru->id,
            'jenis_pelanggaran_id' => $jenis->id,
            'tahun_ajaran_id' => $siswa->tahun_ajaran_id,
            'poin' => $jenis->poin,
            'tanggal_pelanggaran' => $validated['tanggal_pelanggaran'],
            'status_verifikasi' => 'menunggu',
            'keterangan' => $validated['keterangan']
        ]);
        
        return redirect()->route('wali-kelas.dashboard')->with('success', 'Pelanggaran berhasil dicatat.');
    }
    
    // Form Input Prestasi
    public function prestasiCreate()
    {
        $user = Auth::user();
        $kelas = $user->currentHomeroomClass();
        
        if (!$kelas) {
            return redirect()->route('dashboard')->with('error', 'Anda belum ditugaskan sebagai wali kelas.');
        }
        
        $siswas = $kelas->siswas()->orderBy('nama_siswa')->get();
        $jenisPrestasis = JenisPrestasi::orderBy('nama_prestasi')->get();
        
        $guru = Guru::where('users_id', $user->id)->first();
        
        if (!$guru) {
            return redirect()->route('wali-kelas.dashboard')
                ->with('error', 'Data guru Anda belum terdaftar. Hubungi admin.');
        }
        
        return view('wali-kelas.prestasi.create', compact('siswas', 'jenisPrestasis', 'guru', 'kelas'));
    }
    
    // Simpan Prestasi
    public function prestasiStore(Request $request)
    {
        $user = Auth::user();
        $guru = Guru::where('users_id', $user->id)->first();
        
        if (!$guru) {
            return redirect()->route('wali-kelas.dashboard')
                ->with('error', 'Data guru Anda belum terdaftar.');
        }
        
        $kelas = $user->currentHomeroomClass();
        if (!$kelas) {
            return redirect()->route('dashboard')->with('error', 'Anda belum ditugaskan sebagai wali kelas.');
        }
        
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'jenis_prestasi_id' => 'required|exists:jenis_prestasis,id',
            'tanggal_prestasi' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);
        
        $siswa = Siswa::where('id', $validated['siswa_id'])
            ->where('kelas_id', $kelas->id)
            ->firstOrFail();
        
        $jenis = JenisPrestasi::findOrFail($validated['jenis_prestasi_id']);
        
        Prestasi::create([
            'siswa_id' => $siswa->id,
            'guru_pencatat' => $guru->id,
            'jenis_prestasi_id' => $jenis->id,
            'tahun_ajaran_id' => $siswa->tahun_ajaran_id,
            'poin' => $jenis->poin_reward,
            'tanggal_prestasi' => $validated['tanggal_prestasi'],
            'status_verifikasi' => 'pending',
            'keterangan' => $validated['keterangan']
        ]);
        
        return redirect()->route('wali-kelas.dashboard')->with('success', 'Prestasi berhasil dicatat.');
    }
    
    // Komunikasi dengan Ortu
    public function komunikasi()
    {
        $user = Auth::user();
        $kelas = $user->currentHomeroomClass();
        
        if (!$kelas) {
            return redirect()->route('dashboard')->with('error', 'Anda belum ditugaskan sebagai wali kelas.');
        }
        
        $siswaIds = $kelas->siswas->pluck('id');
        
        $komunikasis = KomunikasiOrtu::whereIn('siswa_id', $siswaIds)
            ->where(function($q) use ($user) {
                $q->where('pengirim_id', $user->id)
                  ->orWhere('penerima_id', $user->id);
            })
            ->with(['pengirim', 'penerima', 'siswa'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('wali-kelas.komunikasi.index', compact('komunikasis', 'kelas'));
    }
    
    // Laporan Kelas PDF
    public function laporanKelas()
    {
        $user = Auth::user();
        $kelas = Kelas::with(['siswas', 'tahunAjaran', 'waliKelas'])->findOrFail($user->kelas_id);
        
        $data = $kelas->siswas->map(function($siswa) {
            return [
                'nis' => $siswa->nis,
                'nama' => $siswa->nama_siswa,
                'jenis_kelamin' => $siswa->jenis_kelamin,
                'pelanggaran' => $siswa->pelanggarans()->where('status_verifikasi', 'diverifikasi')->count(),
                'poin_pelanggaran' => $siswa->pelanggarans()->where('status_verifikasi', 'diverifikasi')->sum('poin'),
                'prestasi' => $siswa->prestasis()->where('status_verifikasi', 'verified')->count(),
                'poin_prestasi' => $siswa->prestasis()->where('status_verifikasi', 'verified')->sum('poin'),
            ];
        });
        
        $pdf = PDF::loadView('wali-kelas.laporan-pdf', compact('kelas', 'data'));
        return $pdf->download('Laporan_Kelas_' . $kelas->nama_kelas . '.pdf');
    }
}
