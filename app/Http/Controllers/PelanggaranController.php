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
            
            // Statistik untuk chart - sinkron dengan dashboard
            $statistik = [
                'total' => Pelanggaran::count(),
                'menunggu' => Pelanggaran::where('status_verifikasi', 'menunggu')->count(),
                'terverifikasi' => Pelanggaran::whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi', 'verified'])->count(),
                'ditolak' => Pelanggaran::where('status_verifikasi', 'ditolak')->count(),
            ];
            
            // Data chart per bulan (6 bulan terakhir) - sinkron dengan dashboard
            $chartData = [];
            $chartLabels = [];
            for ($i = 5; $i >= 0; $i--) {
                $bulan = now()->subMonths($i);
                $chartLabels[] = $bulan->format('M Y');
                $chartData[] = Pelanggaran::whereYear('created_at', $bulan->year)
                    ->whereMonth('created_at', $bulan->month)
                    ->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi', 'verified'])
                    ->count();
            }
            
            // Top 5 jenis pelanggaran - sinkron dengan dashboard
            $topJenisPelanggaran = \DB::table('pelanggarans')
                ->join('jenis_pelanggarans', 'pelanggarans.jenis_pelanggaran_id', '=', 'jenis_pelanggarans.id')
                ->select('jenis_pelanggarans.nama_pelanggaran', \DB::raw('count(*) as total'))
                ->whereIn('pelanggarans.status_verifikasi', ['diverifikasi', 'terverifikasi', 'verified'])
                ->groupBy('jenis_pelanggarans.nama_pelanggaran')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get();
            
            return view('pelanggaran.index', compact('pelanggarans', 'statistik', 'chartData', 'chartLabels', 'topJenisPelanggaran'));
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
        $validated = $request->validate([
            'jenis_pelanggaran_id' => 'required|exists:jenis_pelanggarans,id',
            'keterangan' => 'nullable|string|max:500',
            'tanggal_pelanggaran' => 'required|date'
        ]);
        
        try {
            $jenisPelanggaran = JenisPelanggaran::findOrFail($validated['jenis_pelanggaran_id']);
            
            $pelanggaran->update([
                'jenis_pelanggaran_id' => $validated['jenis_pelanggaran_id'],
                'poin' => $jenisPelanggaran->poin,
                'keterangan' => $validated['keterangan'],
                'tanggal_pelanggaran' => $validated['tanggal_pelanggaran']
            ]);
            
            return redirect()->route('pelanggaran.index')->with('success', 'Data pelanggaran berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
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
        \Log::info('=== VERIFY CALLED ===', [
            'id' => $pelanggaran->id,
            'user' => auth()->id(),
            'role' => auth()->user()->role,
            'status' => $pelanggaran->status_verifikasi
        ]);
        
        if (!in_array(auth()->user()->role, ['admin', 'kesiswaan'])) {
            \Log::error('Unauthorized');
            abort(403, 'Unauthorized action.');
        }

        if (in_array($pelanggaran->status_verifikasi, ['diverifikasi', 'terverifikasi'])) {
            \Log::warning('Already verified');
            return redirect()->back()->with('error', 'Pelanggaran sudah diverifikasi sebelumnya!');
        }

        try {
            \DB::beginTransaction();
            
            $guru = \App\Models\Guru::where('users_id', auth()->id())->first();
            
            $pelanggaran->status_verifikasi = 'diverifikasi';
            $pelanggaran->guru_verifikator = $guru ? $guru->id : null;
            $pelanggaran->tanggal_verifikasi = now();
            $pelanggaran->save();
            
            \Log::info('Updated', ['new_status' => $pelanggaran->fresh()->status_verifikasi]);
            
            $this->checkAndCreateSanksi($pelanggaran->siswa_id);
            
            \DB::commit();
            \Log::info('Committed');
            return redirect()->back()->with('success', 'Pelanggaran berhasil disetujui!');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
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
            
            return redirect()->back()->with('success', 'Pelanggaran berhasil ditolak!');
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
                $lastPelanggaran = Pelanggaran::where('siswa_id', $siswaId)
                    ->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi'])
                    ->latest()
                    ->first();
                
                Sanksi::create([
                    'siswa_id' => $siswaId,
                    'pelanggaran_id' => $lastPelanggaran->id,
                    'nama_sanksi' => 'Sanksi Otomatis - Poin >= 100',
                    'jenis_sanksi' => 'Skorsing',
                    'deskripsi_sanksi' => 'Sanksi otomatis karena total poin pelanggaran mencapai ' . $totalPoin . ' poin',
                    'tanggal_mulai' => now(),
                    'tanggal_selesai' => now()->addDays(14),
                    'status_sanksi' => 'aktif'
                ]);
                
                \Log::info('Auto sanksi created', ['siswa_id' => $siswaId, 'total_poin' => $totalPoin]);
            }
        }
    }
}
