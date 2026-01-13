<?php

namespace App\Http\Controllers;

use App\Models\Prestasi;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\JenisPrestasi;
use Illuminate\Http\Request;
use App\Http\Requests\StorePrestasiRequest;

class PrestasiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Prestasi::with(['siswa.kelas', 'jenisPrestasi', 'guru', 'verifikator']);
            
            // Filter by status
            if ($request->filled('status')) {
                $query->where('status_verifikasi', $request->status);
            }
            
            // Filter by siswa
            if ($request->filled('siswa')) {
                $query->whereHas('siswa', function($q) use ($request) {
                    $q->where('nama_siswa', 'like', '%' . $request->siswa . '%')
                      ->orWhere('nis', 'like', '%' . $request->siswa . '%');
                });
            }
            
            // Filter by jenis prestasi
            if ($request->filled('prestasi')) {
                $query->whereHas('jenisPrestasi', function($q) use ($request) {
                    $q->where('nama_prestasi', 'like', '%' . $request->prestasi . '%');
                });
            }
            
            // Filter by tingkat
            if ($request->filled('tingkat')) {
                $query->whereHas('jenisPrestasi', function($q) use ($request) {
                    $q->where('tingkat', $request->tingkat);
                });
            }
            
            // Filter by kelas
            if ($request->filled('kelas')) {
                $query->whereHas('siswa', function($q) use ($request) {
                    $q->where('kelas_id', $request->kelas);
                });
            }
            
            // Filter by date range
            if ($request->filled('tanggal_mulai')) {
                $query->whereDate('tanggal_prestasi', '>=', $request->tanggal_mulai);
            }
            if ($request->filled('tanggal_selesai')) {
                $query->whereDate('tanggal_prestasi', '<=', $request->tanggal_selesai);
            }
            
            $prestasis = $query->latest('tanggal_prestasi')->paginate(20)->withQueryString();
            
            // Get filter options
            $tingkats = JenisPrestasi::select('tingkat')->distinct()->pluck('tingkat');
            $kelass = \App\Models\Kelas::orderBy('nama_kelas')->get();
            
            return view('prestasi.index', compact('prestasis', 'tingkats', 'kelass'));
        } catch (\Exception $e) {
            \Log::error('Prestasi Index Error: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Gagal memuat halaman prestasi: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $siswas = Siswa::with('kelas')->orderBy('nama_siswa')->get();
            $gurus = Guru::where('status', 'aktif')->orderBy('nama_guru')->get();
            $jenisPrestasis = JenisPrestasi::orderBy('tingkat')->orderBy('nama_prestasi')->get();
            
            // Get unique tingkat for filter
            $tingkats = JenisPrestasi::select('tingkat')
                ->distinct()
                ->orderByRaw("FIELD(tingkat, 'sekolah', 'kecamatan', 'kota', 'provinsi', 'nasional', 'internasional')")
                ->pluck('tingkat');
            
            // Get unique kategori_penampilan for filter
            $kategoriPenampilans = JenisPrestasi::select('kategori_penampilan')
                ->distinct()
                ->orderBy('kategori_penampilan')
                ->pluck('kategori_penampilan');
            
            return view('prestasi.create', compact('siswas', 'gurus', 'jenisPrestasis', 'tingkats', 'kategoriPenampilans'));
        } catch (\Exception $e) {
            return redirect()->route('prestasi.index')
                ->with('error', 'Gagal membuka form: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        \Log::info('=== PRESTASI STORE CALLED ===');
        \Log::info('Request data:', $request->all());
        
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'guru_pencatat' => 'required|exists:gurus,id',
            'jenis_prestasi_id' => 'required|exists:jenis_prestasis,id',
            'keterangan' => 'nullable|string|max:1000',
            'tanggal_prestasi' => 'nullable|date|before_or_equal:today'
        ], [
            'siswa_id.required' => 'Siswa harus dipilih',
            'siswa_id.exists' => 'Siswa tidak ditemukan',
            'guru_pencatat.required' => 'Guru pencatat harus dipilih',
            'guru_pencatat.exists' => 'Guru tidak ditemukan',
            'jenis_prestasi_id.required' => 'Jenis prestasi harus dipilih',
            'jenis_prestasi_id.exists' => 'Jenis prestasi tidak ditemukan',
            'keterangan.max' => 'Keterangan maksimal 1000 karakter',
            'tanggal_prestasi.date' => 'Format tanggal tidak valid',
            'tanggal_prestasi.before_or_equal' => 'Tanggal prestasi tidak boleh lebih dari hari ini'
        ]);
        
        \Log::info('Validation passed:', $validated);

        try {
            $jenisPrestasi = JenisPrestasi::findOrFail($request->jenis_prestasi_id);
            $siswa = Siswa::findOrFail($request->siswa_id);
            
            // Validate poin_reward exists
            if (!$jenisPrestasi->poin_reward || $jenisPrestasi->poin_reward <= 0) {
                return redirect()->back()->withInput()
                    ->with('error', 'Jenis prestasi tidak memiliki poin reward yang valid');
            }
            
            \Log::info('Found siswa:', ['id' => $siswa->id, 'nama' => $siswa->nama_siswa]);
            \Log::info('Found jenis:', ['id' => $jenisPrestasi->id, 'poin_reward' => $jenisPrestasi->poin_reward]);

            $data = [
                'siswa_id' => $validated['siswa_id'],
                'guru_pencatat' => $validated['guru_pencatat'],
                'jenis_prestasi_id' => $validated['jenis_prestasi_id'],
                'tahun_ajaran_id' => $siswa->tahun_ajaran_id ?? 1,
                'poin' => $jenisPrestasi->poin_reward,
                'keterangan' => $validated['keterangan'] ?? null,
                'tanggal_prestasi' => $validated['tanggal_prestasi'] ?? now(),
                'status_verifikasi' => 'pending'
            ];
            
            \Log::info('Data to insert:', $data);

            $prestasi = Prestasi::create($data);
            
            \Log::info('Prestasi created successfully!', ['id' => $prestasi->id]);

            return redirect()->route('prestasi.index')
                ->with('success', 'Prestasi berhasil ditambahkan! Menunggu verifikasi dari Admin/Kesiswaan.');
        } catch (\Exception $e) {
            \Log::error('=== PRESTASI STORE ERROR ===');
            \Log::error('Error: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile() . ':' . $e->getLine());
            \Log::error('Trace: ' . $e->getTraceAsString());
            
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan prestasi: ' . $e->getMessage());
        }
    }

    public function show(Prestasi $prestasi)
    {
        $prestasi->load(['siswa.kelas', 'guru', 'jenisPrestasi']);
        return view('prestasi.show', compact('prestasi'));
    }

    public function edit(Prestasi $prestasi)
    {
        $siswas = Siswa::with('kelas')->orderBy('nama_siswa')->get();
        $gurus = Guru::where('status', 'aktif')->orderBy('nama_guru')->get();
        $jenisPrestasis = JenisPrestasi::orderBy('tingkat')->orderBy('nama_prestasi')->get();
        
        $tingkats = JenisPrestasi::select('tingkat')
            ->distinct()
            ->orderByRaw("FIELD(tingkat, 'sekolah', 'kecamatan', 'kota', 'provinsi', 'nasional', 'internasional')")
            ->pluck('tingkat');
        
        $kategoriPenampilans = JenisPrestasi::select('kategori_penampilan')
            ->distinct()
            ->orderBy('kategori_penampilan')
            ->pluck('kategori_penampilan');
        
        $totalPoin = Prestasi::where('siswa_id', $prestasi->siswa_id)
            ->where('status_verifikasi', 'verified')
            ->sum('poin');
        
        return view('prestasi.edit', compact('prestasi', 'siswas', 'gurus', 'jenisPrestasis', 'tingkats', 'kategoriPenampilans', 'totalPoin'));
    }

    public function update(Request $request, Prestasi $prestasi)
    {
        $request->validate([
            'keterangan' => 'nullable|string',
            'prestasi_tambahan' => 'nullable|array',
            'prestasi_tambahan.*' => 'exists:jenis_prestasis,id'
        ]);

        try {
            $prestasi->update([
                'keterangan' => $request->keterangan
            ]);
            
            $jumlahTambahan = 0;
            if ($request->has('prestasi_tambahan') && is_array($request->prestasi_tambahan)) {
                foreach ($request->prestasi_tambahan as $jenisId) {
                    $jenisPrestasi = JenisPrestasi::find($jenisId);
                    
                    Prestasi::create([
                        'siswa_id' => $prestasi->siswa_id,
                        'guru_pencatat' => $prestasi->guru_pencatat,
                        'jenis_prestasi_id' => $jenisId,
                        'tahun_ajaran_id' => $prestasi->tahun_ajaran_id,
                        'poin' => $jenisPrestasi->poin_reward,
                        'tanggal_prestasi' => now(),
                        'status_verifikasi' => 'pending'
                    ]);
                    $jumlahTambahan++;
                }
            }

            $message = 'Data prestasi berhasil diupdate';
            if ($jumlahTambahan > 0) {
                $message .= ' dan ' . $jumlahTambahan . ' prestasi baru ditambahkan';
            }
            
            return redirect()->route('prestasi.index')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('prestasi.index')->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function destroy(Prestasi $prestasi)
    {
        try {
            $prestasi->delete();
            return redirect()->route('prestasi.index')->with('success', 'Data prestasi berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function verify(Request $request, Prestasi $prestasi)
    {
        if (!in_array(auth()->user()->role, ['admin', 'kesiswaan'])) {
            abort(403, 'Anda tidak memiliki akses untuk verifikasi prestasi.');
        }

        // CRITICAL FIX: Check if already verified
        if ($prestasi->status_verifikasi !== 'pending') {
            return redirect()->back()->with('error', 'Prestasi sudah diverifikasi sebelumnya. Status saat ini: ' . $prestasi->status_verifikasi);
        }

        try {
            \DB::beginTransaction();
            
            $guru = \App\Models\Guru::where('users_id', auth()->id())->first();
            $action = $request->input('action', 'approve');
            
            if ($action === 'approve') {
                $prestasi->update([
                    'status_verifikasi' => 'verified',
                    'guru_verifikator' => $guru ? $guru->id : null,
                    'tanggal_verifikasi' => now()
                ]);
                
                \Log::info('Prestasi verified', [
                    'prestasi_id' => $prestasi->id,
                    'siswa' => $prestasi->siswa->nama_siswa,
                    'poin' => $prestasi->poin,
                    'verifikator' => auth()->user()->name,
                    'ip' => request()->ip()
                ]);
                
                $message = 'Prestasi berhasil disetujui! Poin +' . $prestasi->poin . ' untuk ' . $prestasi->siswa->nama_siswa;
            } else {
                $prestasi->update([
                    'status_verifikasi' => 'rejected',
                    'guru_verifikator' => $guru ? $guru->id : null,
                    'tanggal_verifikasi' => now()
                ]);
                
                \Log::info('Prestasi rejected', [
                    'prestasi_id' => $prestasi->id,
                    'siswa' => $prestasi->siswa->nama_siswa,
                    'verifikator' => auth()->user()->name,
                    'ip' => request()->ip()
                ]);
                
                $message = 'Prestasi ditolak';
            }
            
            \DB::commit();
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Verify prestasi error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal verifikasi: ' . $e->getMessage());
        }
    }

    public function reject(Prestasi $prestasi)
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, ['admin', 'kesiswaan'])) {
            abort(403, 'Unauthorized action.');
        }

        $prestasi->update([
            'status_verifikasi' => 'ditolak',
            'guru_verifikator' => $user->guru->id ?? null,
            'tanggal_verifikasi' => now()
        ]);
        
        return redirect()->back()->with('success', 'Prestasi ditolak');
    }
}
