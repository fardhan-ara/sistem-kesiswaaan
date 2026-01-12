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
    public function index(Request $request)
    {
        try {
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
            
            // Filter dari request
            if ($request->status) {
                $query->where('status_verifikasi', $request->status);
            }
            
            if ($request->siswa) {
                $query->whereHas('siswa', function($q) use ($request) {
                    $q->where('nama_siswa', 'like', '%' . $request->siswa . '%');
                });
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
        } catch (\Exception $e) {
            \Log::error('Pelanggaran Index Error: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Gagal memuat halaman pelanggaran: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $tahunAjaranAktif = \App\Models\TahunAjaran::where('status_aktif', 'aktif')->first();
            
            $siswas = Siswa::with('kelas')
                ->when($tahunAjaranAktif, function($q) use ($tahunAjaranAktif) {
                    return $q->where('tahun_ajaran_id', $tahunAjaranAktif->id);
                })
                ->orderBy('nama_siswa')
                ->get();
                
            $gurus = Guru::where('status', 'aktif')->orderBy('nama_guru')->get();
            $jenisPelanggarans = JenisPelanggaran::orderBy('kategori')->orderBy('nama_pelanggaran')->get();
            
            // Get unique categories for filter
            $kategoris = JenisPelanggaran::select('kategori')
                ->distinct()
                ->orderBy('kategori')
                ->pluck('kategori');
            
            return view('pelanggaran.create', compact('siswas', 'gurus', 'jenisPelanggarans', 'kategoris'));
        } catch (\Exception $e) {
            return redirect()->route('pelanggaran.index')
                ->with('error', 'Gagal membuka form: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        \Log::info('=== PELANGGARAN STORE CALLED ===');
        \Log::info('Request data:', $request->all());
        
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'guru_pencatat' => 'required|exists:gurus,id',
            'jenis_pelanggaran_id' => 'required|exists:jenis_pelanggarans,id',
            'keterangan' => 'nullable|string',
            'tanggal_pelanggaran' => 'nullable|date'
        ]);
        
        \Log::info('Validation passed:', $validated);

        try {
            $jenisPelanggaran = JenisPelanggaran::findOrFail($request->jenis_pelanggaran_id);
            $siswa = Siswa::findOrFail($request->siswa_id);
            
            \Log::info('Found siswa:', ['id' => $siswa->id, 'nama' => $siswa->nama_siswa]);
            \Log::info('Found jenis:', ['id' => $jenisPelanggaran->id, 'poin' => $jenisPelanggaran->poin]);
            
            $data = [
                'siswa_id' => $validated['siswa_id'],
                'guru_pencatat' => $validated['guru_pencatat'],
                'jenis_pelanggaran_id' => $validated['jenis_pelanggaran_id'],
                'tahun_ajaran_id' => $siswa->tahun_ajaran_id ?? 1,
                'poin' => $jenisPelanggaran->poin,
                'keterangan' => $validated['keterangan'] ?? null,
                'tanggal_pelanggaran' => $validated['tanggal_pelanggaran'] ?? now(),
                'status_verifikasi' => 'menunggu'
            ];
            
            \Log::info('Data to insert:', $data);
            
            $pelanggaran = Pelanggaran::create($data);
            
            \Log::info('Pelanggaran created successfully!', ['id' => $pelanggaran->id]);
            
            // Cek total poin siswa untuk auto sanksi
            $this->checkAndCreateSanksi($siswa->id);
            
            return redirect('/pelanggaran')->with('success', 'Data pelanggaran berhasil ditambahkan!');
        } catch (\Exception $e) {
            \Log::error('=== PELANGGARAN STORE ERROR ===');
            \Log::error('Error: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile() . ':' . $e->getLine());
            \Log::error('Trace: ' . $e->getTraceAsString());
            
            return redirect()->back()->withInput()->with('error', 'ERROR: ' . $e->getMessage());
        }
    }

    public function show(Pelanggaran $pelanggaran)
    {
        $pelanggaran->load(['siswa.kelas', 'guru', 'jenisPelanggaran', 'sanksis']);
        return view('pelanggaran.show', compact('pelanggaran'));
    }

    public function edit(Pelanggaran $pelanggaran)
    {
        \Log::info('=== EDIT PELANGGARAN CALLED ===', ['id' => $pelanggaran->id]);
        
        try {
            $siswas = Siswa::with('kelas')->orderBy('nama_siswa')->get();
            $gurus = Guru::where('status', 'aktif')->orderBy('nama_guru')->get();
            $jenisPelanggarans = JenisPelanggaran::orderBy('kategori')->orderBy('nama_pelanggaran')->get();
            $kategoris = JenisPelanggaran::select('kategori')->distinct()->orderBy('kategori')->pluck('kategori');
            $totalPoin = Pelanggaran::where('siswa_id', $pelanggaran->siswa_id)
                ->whereIn('status_verifikasi', ['terverifikasi', 'diverifikasi', 'verified'])
                ->sum('poin');
            
            \Log::info('Edit data loaded successfully', ['totalPoin' => $totalPoin]);
            return view('pelanggaran.edit', compact('pelanggaran', 'siswas', 'gurus', 'jenisPelanggarans', 'kategoris', 'totalPoin'));
        } catch (\Exception $e) {
            \Log::error('Edit error:', ['message' => $e->getMessage()]);
            return redirect()->route('pelanggaran.index')->with('error', 'Gagal membuka halaman edit: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Pelanggaran $pelanggaran)
    {
        \Log::info('=== UPDATE PELANGGARAN START ===');
        \Log::info('Request:', $request->all());
        \Log::info('Pelanggaran ID:', ['id' => $pelanggaran->id]);
        
        try {
            $validated = $request->validate([
                'keterangan' => 'nullable|string',
                'pelanggaran_tambahan' => 'nullable|array',
                'pelanggaran_tambahan.*' => 'exists:jenis_pelanggarans,id'
            ]);
            
            \Log::info('Validation passed');
            
            $pelanggaran->update([
                'keterangan' => $request->keterangan
            ]);
            \Log::info('Keterangan updated');
            
            $jumlahTambahan = 0;
            if ($request->has('pelanggaran_tambahan') && is_array($request->pelanggaran_tambahan)) {
                \Log::info('Pelanggaran tambahan:', $request->pelanggaran_tambahan);
                foreach ($request->pelanggaran_tambahan as $jenisId) {
                    $jenisPelanggaran = JenisPelanggaran::find($jenisId);
                    
                    $newPelanggaran = Pelanggaran::create([
                        'siswa_id' => $pelanggaran->siswa_id,
                        'guru_pencatat' => $pelanggaran->guru_pencatat,
                        'jenis_pelanggaran_id' => $jenisId,
                        'tahun_ajaran_id' => $pelanggaran->tahun_ajaran_id,
                        'poin' => $jenisPelanggaran->poin,
                        'tanggal_pelanggaran' => now(),
                        'status_verifikasi' => 'menunggu'
                    ]);
                    $jumlahTambahan++;
                    \Log::info('Created new pelanggaran:', ['id' => $newPelanggaran->id, 'poin' => $jenisPelanggaran->poin]);
                }
            }

            \Log::info('=== UPDATE SUCCESS ===');
            $message = 'Data pelanggaran berhasil diupdate';
            if ($jumlahTambahan > 0) {
                $message .= ' dan ' . $jumlahTambahan . ' pelanggaran baru ditambahkan';
            }
            
            return redirect()->route('pelanggaran.index')->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('=== UPDATE ERROR ===');
            \Log::error('Error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('pelanggaran.index')->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function destroy(Pelanggaran $pelanggaran)
    {
        try {
            $pelanggaran->delete();
            return redirect('/pelanggaran')->with('success', 'Data pelanggaran berhasil dihapus');
        } catch (\Exception $e) {
            return redirect('/pelanggaran')->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function verify(Request $request, Pelanggaran $pelanggaran)
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
            
            // Cek total poin siswa untuk auto sanksi
            $this->checkAndCreateSanksi($pelanggaran->siswa_id);
            
            return redirect()->route('pelanggaran.index')->with('success', 'Pelanggaran berhasil disetujui!');
        } catch (\Exception $e) {
            return redirect()->route('pelanggaran.index')->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Pelanggaran $pelanggaran)
    {
        if (!in_array(auth()->user()->role, ['admin', 'kesiswaan'])) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $guru = \App\Models\Guru::where('users_id', auth()->id())->first();
            
            $pelanggaran->update([
                'status_verifikasi' => 'ditolak',
                'guru_verifikator' => $guru ? $guru->id : null,
                'tanggal_verifikasi' => now(),
                'alasan_penolakan' => $request->alasan_penolakan
            ]);
            
            return redirect()->route('pelanggaran.index')->with('success', 'Pelanggaran berhasil ditolak!');
        } catch (\Exception $e) {
            return redirect()->route('pelanggaran.index')->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
    
    private function checkAndCreateSanksi($siswaId)
    {
        $totalPoin = Pelanggaran::where('siswa_id', $siswaId)
            ->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi'])
            ->sum('poin');
        
        if ($totalPoin >= 100) {
            $sanksiAktif = Sanksi::where('siswa_id', $siswaId)
                ->where('status_sanksi', 'aktif')
                ->first();
            
            if (!$sanksiAktif) {
                Sanksi::create([
                    'siswa_id' => $siswaId,
                    'pelanggaran_id' => null,
                    'nama_sanksi' => 'Sanksi Otomatis - Poin Pelanggaran >= 100',
                    'kategori_poin' => 'sangat_berat',
                    'total_poin' => $totalPoin,
                    'tanggal_mulai' => now(),
                    'tanggal_selesai' => now()->addDays(14),
                    'status_sanksi' => 'aktif',
                    'keterangan' => 'Sanksi dibuat otomatis karena total poin pelanggaran mencapai ' . $totalPoin . ' poin'
                ]);
                
                \Log::info('Auto sanksi created', ['siswa_id' => $siswaId, 'total_poin' => $totalPoin]);
            }
        }
    }
}
