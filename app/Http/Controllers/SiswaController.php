<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Http\Requests\StoreSiswaRequest;
use App\Http\Requests\UpdateSiswaRequest;
use Illuminate\Support\Facades\Log;

class SiswaController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        try {
            $query = Siswa::with(['kelas', 'tahunAjaran', 'user']);
            
            if ($request->status_approval) {
                $query->where('status_approval', $request->status_approval);
            }
            
            if ($request->kelas_id) {
                $query->where('kelas_id', $request->kelas_id);
            }
            
            if ($request->jenis_kelamin) {
                $query->where('jenis_kelamin', $request->jenis_kelamin);
            }
            
            if ($request->nama) {
                $query->where('nama_siswa', 'like', '%' . $request->nama . '%');
            }
            
            if ($request->nis) {
                $query->where('nis', 'like', '%' . $request->nis . '%');
            }
            
            $siswas = $query->latest()->paginate(20);
                
            return view('siswa.index', compact('siswas'));
        } catch (\Exception $e) {
            Log::error('Error loading siswa index: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Gagal memuat data siswa: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $kelas = Kelas::orderBy('nama_kelas')->get();
            $tahunAjarans = TahunAjaran::orderBy('tahun_mulai', 'desc')->get();
            $users = \App\Models\User::where('role', 'siswa')
                ->whereDoesntHave('siswa')
                ->get();
                
            return view('siswa.create', compact('kelas', 'tahunAjarans', 'users'));
        } catch (\Exception $e) {
            Log::error('Error loading siswa create form: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat form tambah siswa: ' . $e->getMessage());
        }
    }

    public function store(StoreSiswaRequest $request)
    {
        try {
            $data = $request->validated();
            
            Log::info('Storing siswa data', $data);
            
            if (!isset($data['tahun_ajaran_id'])) {
                $tahunAjaranAktif = TahunAjaran::where('status_aktif', 'aktif')->first();
                $data['tahun_ajaran_id'] = $tahunAjaranAktif ? $tahunAjaranAktif->id : null;
            }
            
            $user = \App\Models\User::findOrFail($data['users_id']);
            $data['nama_siswa'] = $user->nama;
            
            $siswa = Siswa::create($data);
            
            Log::info('Siswa created successfully', ['id' => $siswa->id]);
            
            return redirect('/siswa')
                ->with('success', 'Data siswa berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error storing siswa: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data siswa: ' . $e->getMessage());
        }
    }

    public function show(Siswa $siswa)
    {
        $siswa->load(['kelas', 'tahunAjaran', 'user']);
        return response()->json($siswa);
    }

    public function edit(Siswa $siswa)
    {
        try {
            $kelas = Kelas::orderBy('nama_kelas')->get();
            $tahunAjarans = TahunAjaran::orderBy('tahun_mulai', 'desc')->get();
            $users = \App\Models\User::where('role', 'siswa')
                ->where(function($q) use ($siswa) {
                    $q->whereDoesntHave('siswa')
                      ->orWhere('id', $siswa->users_id);
                })
                ->get();
                
            return view('siswa.edit', compact('siswa', 'kelas', 'tahunAjarans', 'users'));
        } catch (\Exception $e) {
            Log::error('Error loading siswa edit form: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat form edit siswa: ' . $e->getMessage());
        }
    }

    public function update(UpdateSiswaRequest $request, Siswa $siswa)
    {
        try {
            $data = $request->validated();
            
            Log::info('Updating siswa', ['id' => $siswa->id, 'data' => $data]);
            
            $siswa->update($request->only(['nis', 'jenis_kelamin', 'kelas_id', 'tahun_ajaran_id']));
            
            Log::info('Siswa updated successfully', ['id' => $siswa->id]);
            
            return redirect('/siswa')
                ->with('success', 'Data siswa berhasil diupdate');
        } catch (\Exception $e) {
            Log::error('Error updating siswa: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate data siswa: ' . $e->getMessage());
        }
    }

    public function destroy(Siswa $siswa)
    {
        try {
            Log::info('Attempting to delete siswa', ['id' => $siswa->id]);
            
            if ($siswa->pelanggarans()->count() > 0 || $siswa->prestasis()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus siswa yang memiliki data pelanggaran atau prestasi');
            }
            
            $siswa->delete();
            
            Log::info('Siswa deleted successfully', ['id' => $siswa->id]);
            
            return redirect('/siswa')
                ->with('success', 'Data siswa berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting siswa: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()
                ->with('error', 'Gagal menghapus data siswa: ' . $e->getMessage());
        }
    }

    public function approve($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->update(['status_approval' => 'approved']);
        if ($siswa->user) {
            $siswa->user->update(['status' => 'approved', 'verified_by' => auth()->id(), 'verified_at' => now()]);
        }
        return redirect('/siswa')->with('success', 'Siswa berhasil disetujui!');
    }

    public function reject($id)
    {
        $siswa = Siswa::findOrFail($id);
        if ($siswa->user) {
            $siswa->user->update(['status' => 'rejected', 'verified_by' => auth()->id(), 'verified_at' => now(), 'rejection_reason' => 'Ditolak oleh admin']);
        }
        $siswa->update(['status_approval' => 'rejected']);
        return redirect('/siswa')->with('success', 'Siswa berhasil ditolak!');
    }
}
