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
            $query = Prestasi::with(['siswa.kelas', 'jenisPrestasi', 'guru']);
            
            if ($request->filled('status')) {
                $query->where('status_verifikasi', $request->status);
            }
            if ($request->filled('siswa')) {
                $query->whereHas('siswa', function($q) use ($request) {
                    $q->where('nama_siswa', 'like', '%' . $request->siswa . '%');
                });
            }
            if ($request->filled('prestasi')) {
                $query->whereHas('jenisPrestasi', function($q) use ($request) {
                    $q->where('nama_prestasi', 'like', '%' . $request->prestasi . '%');
                });
            }
            
            $prestasis = $query->latest()->paginate(20)->withQueryString();
            return view('prestasi.index', compact('prestasis'));
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
            'keterangan' => 'nullable|string',
            'tanggal_prestasi' => 'nullable|date'
        ]);
        
        \Log::info('Validation passed:', $validated);

        try {
            $jenisPrestasi = JenisPrestasi::findOrFail($request->jenis_prestasi_id);
            $siswa = Siswa::findOrFail($request->siswa_id);
            
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

            return redirect('/prestasi')->with('success', 'Data prestasi berhasil ditambahkan!');
        } catch (\Exception $e) {
            \Log::error('=== PRESTASI STORE ERROR ===');
            \Log::error('Error: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile() . ':' . $e->getLine());
            \Log::error('Trace: ' . $e->getTraceAsString());
            
            return redirect()->back()->withInput()->with('error', 'ERROR: ' . $e->getMessage());
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
            abort(403, 'Unauthorized action.');
        }

        try {
            $guru = \App\Models\Guru::where('users_id', auth()->id())->first();
            $action = $request->input('action', 'approve');
            
            if ($action === 'approve') {
                $prestasi->update([
                    'status_verifikasi' => 'verified',
                    'guru_verifikator' => $guru ? $guru->id : null,
                    'tanggal_verifikasi' => now()
                ]);
                $message = 'Prestasi berhasil disetujui';
            } else {
                $prestasi->update([
                    'status_verifikasi' => 'rejected',
                    'guru_verifikator' => $guru ? $guru->id : null,
                    'tanggal_verifikasi' => now()
                ]);
                $message = 'Prestasi ditolak';
            }
            
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
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
