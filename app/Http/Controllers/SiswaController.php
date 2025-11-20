<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Http\Requests\StoreSiswaRequest;
use App\Http\Requests\UpdateSiswaRequest;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::with(['kelas', 'tahunAjaran', 'user'])
            ->withCount([
                'pelanggarans as total_pelanggaran',
                'prestasis as total_prestasi'
            ])
            ->withSum('pelanggarans', 'poin')
            ->latest()
            ->paginate(20);
            
        return view('siswa.index', compact('siswas'));
    }

    public function create()
    {
        $kelas = Kelas::where('tahun_ajaran_id', TahunAjaran::where('status_aktif', 1)->first()->id ?? null)
            ->orderBy('nama_kelas')
            ->get();
        $tahunAjarans = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();
        $users = \App\Models\User::where('role', 'siswa')
            ->whereDoesntHave('siswa')
            ->get();
            
        return view('siswa.create', compact('kelas', 'tahunAjarans', 'users'));
    }

    public function store(StoreSiswaRequest $request)
    {
        try {
            $data = $request->validated();
            
            // Set tahun ajaran aktif jika tidak dipilih
            if (!isset($data['tahun_ajaran_id'])) {
                $tahunAjaranAktif = TahunAjaran::where('status_aktif', 1)->first();
                $data['tahun_ajaran_id'] = $tahunAjaranAktif ? $tahunAjaranAktif->id : null;
            }
            
            Siswa::create($data);
            
            return redirect()->route('siswa.index')
                ->with('success', 'Data siswa berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data siswa: ' . $e->getMessage());
        }
    }

    public function show(Siswa $siswa)
    {
        $siswa->load(['kelas', 'tahunAjaran', 'user', 'pelanggarans.jenisPelanggaran', 'prestasis.jenisPrestasi']);
        
        $totalPoin = $siswa->pelanggarans()->sum('poin');
        $pelanggaranTerbaru = $siswa->pelanggarans()->latest()->take(10)->get();
        $prestasiTerbaru = $siswa->prestasis()->latest()->take(10)->get();
        
        return view('siswa.show', compact('siswa', 'totalPoin', 'pelanggaranTerbaru', 'prestasiTerbaru'));
    }

    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $tahunAjarans = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();
        $users = \App\Models\User::where('role', 'siswa')
            ->where(function($q) use ($siswa) {
                $q->whereDoesntHave('siswa')
                  ->orWhere('id', $siswa->users_id);
            })
            ->get();
            
        return view('siswa.edit', compact('siswa', 'kelas', 'tahunAjarans', 'users'));
    }

    public function update(UpdateSiswaRequest $request, Siswa $siswa)
    {
        try {
            $siswa->update($request->validated());
            
            return redirect()->route('siswa.index')
                ->with('success', 'Data siswa berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate data siswa: ' . $e->getMessage());
        }
    }

    public function destroy(Siswa $siswa)
    {
        try {
            // Cek apakah siswa memiliki pelanggaran atau prestasi
            if ($siswa->pelanggarans()->count() > 0 || $siswa->prestasis()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus siswa yang memiliki data pelanggaran atau prestasi');
            }
            
            $siswa->delete();
            
            return redirect()->route('siswa.index')
                ->with('success', 'Data siswa berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data siswa: ' . $e->getMessage());
        }
    }
}
