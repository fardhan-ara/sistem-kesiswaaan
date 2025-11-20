<?php

namespace App\Http\Controllers;

use App\Models\Pelanggaran;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\JenisPelanggaran;
use App\Models\Sanksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\StorePelanggaranRequest;

class PelanggaranController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Pelanggaran::with(['siswa.kelas', 'guru', 'jenisPelanggaran']);
        
        // Filter berdasarkan role
        if ($user->role == 'guru') {
            $guru = \App\Models\Guru::where('users_id', $user->id)->first();
            if ($guru) {
                $query->where('guru_pencatat', $guru->id);
            }
        } elseif ($user->role == 'wali_kelas') {
            $guru = \App\Models\Guru::where('users_id', $user->id)->first();
            if ($guru) {
                $kelasIds = \App\Models\Kelas::where('wali_kelas_id', $guru->id)->pluck('id');
                $query->whereHas('siswa', function($q) use ($kelasIds) {
                    $q->whereIn('kelas_id', $kelasIds);
                });
            }
        }
        
        $pelanggarans = $query->latest()->paginate(20);
        
        // Statistik untuk chart
        $statistik = [
            'total' => Pelanggaran::count(),
            'menunggu' => Pelanggaran::where('status_verifikasi', 'menunggu')->count(),
            'terverifikasi' => Pelanggaran::where('status_verifikasi', 'terverifikasi')->count(),
            'ditolak' => Pelanggaran::where('status_verifikasi', 'ditolak')->count(),
        ];
        
        return view('pelanggaran.index', compact('pelanggarans', 'statistik'));
    }

    public function create()
    {
        $siswas = Siswa::with('kelas')
            ->where('tahun_ajaran_id', TahunAjaran::where('status_aktif', 1)->first()->id ?? null)
            ->orderBy('nama_siswa')
            ->get();
        $gurus = Guru::where('status', 'aktif')->orderBy('nama_guru')->get();
        $jenisPelanggarans = JenisPelanggaran::orderBy('kategori')->orderBy('poin')->get();
        
        return view('pelanggaran.create', compact('siswas', 'gurus', 'jenisPelanggarans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'guru_pencatat' => 'required|exists:gurus,id',
            'jenis_pelanggaran_id' => 'required|exists:jenis_pelanggarans,id',
            'keterangan' => 'nullable|string',
            'tanggal_pelanggaran' => 'nullable|date'
        ]);

        try {
            $jenisPelanggaran = JenisPelanggaran::find($request->jenis_pelanggaran_id);
            $siswa = Siswa::find($request->siswa_id);
            
            $pelanggaran = Pelanggaran::create([
                'siswa_id' => $request->siswa_id,
                'guru_pencatat' => $request->guru_pencatat,
                'jenis_pelanggaran_id' => $request->jenis_pelanggaran_id,
                'tahun_ajaran_id' => $siswa->tahun_ajaran_id,
                'poin' => $jenisPelanggaran->poin,
                'keterangan' => $request->keterangan,
                'tanggal_pelanggaran' => $request->tanggal_pelanggaran ?? now(),
                'status_verifikasi' => 'menunggu'
            ]);
            
            // Auto-create sanksi jika poin berat
            if ($jenisPelanggaran->poin >= 31) {
                Sanksi::create([
                    'pelanggaran_id' => $pelanggaran->id,
                    'jenis_sanksi' => $jenisPelanggaran->sanksi_rekomendasi ?? 'Sanksi Berat',
                    'deskripsi_sanksi' => 'Auto-generated dari pelanggaran berat',
                    'tanggal_mulai' => now(),
                    'tanggal_selesai' => now()->addDays(7),
                    'status_sanksi' => 'direncanakan'
                ]);
            }

            return redirect()->route('pelanggaran.index')
                ->with('success', 'Data pelanggaran berhasil ditambahkan dan menunggu verifikasi');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function show(Pelanggaran $pelanggaran)
    {
        $pelanggaran->load(['siswa.kelas', 'guru', 'jenisPelanggaran', 'sanksis']);
        return view('pelanggaran.show', compact('pelanggaran'));
    }

    public function edit(Pelanggaran $pelanggaran)
    {
        // Hanya bisa edit jika status masih menunggu
        if ($pelanggaran->status_verifikasi != 'menunggu') {
            return redirect()->back()
                ->with('error', 'Tidak dapat mengedit pelanggaran yang sudah diverifikasi/ditolak');
        }
        
        $siswas = Siswa::with('kelas')->orderBy('nama_siswa')->get();
        $gurus = Guru::where('status', 'aktif')->orderBy('nama_guru')->get();
        $jenisPelanggarans = JenisPelanggaran::orderBy('kategori')->orderBy('poin')->get();
        
        return view('pelanggaran.edit', compact('pelanggaran', 'siswas', 'gurus', 'jenisPelanggarans'));
    }

    public function update(Request $request, Pelanggaran $pelanggaran)
    {
        if ($pelanggaran->status_verifikasi != 'menunggu') {
            return redirect()->back()
                ->with('error', 'Tidak dapat mengupdate pelanggaran yang sudah diverifikasi/ditolak');
        }
        
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'guru_pencatat' => 'required|exists:gurus,id',
            'jenis_pelanggaran_id' => 'required|exists:jenis_pelanggarans,id',
            'keterangan' => 'nullable|string'
        ]);

        try {
            $jenisPelanggaran = JenisPelanggaran::find($request->jenis_pelanggaran_id);
            
            $pelanggaran->update([
                'siswa_id' => $request->siswa_id,
                'guru_pencatat' => $request->guru_pencatat,
                'jenis_pelanggaran_id' => $request->jenis_pelanggaran_id,
                'poin' => $jenisPelanggaran->poin,
                'keterangan' => $request->keterangan
            ]);

            return redirect()->route('pelanggaran.index')
                ->with('success', 'Data pelanggaran berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate data: ' . $e->getMessage());
        }
    }

    public function destroy(Pelanggaran $pelanggaran)
    {
        try {
            // Cek apakah ada sanksi
            if ($pelanggaran->sanksis()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus pelanggaran yang memiliki sanksi');
            }
            
            $pelanggaran->delete();
            
            return redirect()->route('pelanggaran.index')
                ->with('success', 'Data pelanggaran berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function verify(Pelanggaran $pelanggaran)
    {
        if (!in_array(auth()->user()->role, ['admin', 'kesiswaan'])) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $guru = \App\Models\Guru::where('users_id', auth()->id())->first();
            
            $pelanggaran->update([
                'status_verifikasi' => 'terverifikasi',
                'guru_verifikator' => $guru ? $guru->id : null,
                'tanggal_verifikasi' => now()
            ]);
            
            return redirect()->back()
                ->with('success', 'Pelanggaran berhasil diverifikasi');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal verifikasi: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Pelanggaran $pelanggaran)
    {
        if (!in_array(auth()->user()->role, ['admin', 'kesiswaan'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'alasan_penolakan' => 'nullable|string'
        ]);

        try {
            $guru = \App\Models\Guru::where('users_id', auth()->id())->first();
            
            $pelanggaran->update([
                'status_verifikasi' => 'ditolak',
                'guru_verifikator' => $guru ? $guru->id : null,
                'tanggal_verifikasi' => now(),
                'alasan_penolakan' => $request->alasan_penolakan
            ]);
            
            return redirect()->back()
                ->with('success', 'Pelanggaran ditolak');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menolak: ' . $e->getMessage());
        }
    }
}
