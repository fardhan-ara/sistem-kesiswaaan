<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Guru::with('user');
            
            if ($request->status_approval) {
                $query->where('status_approval', $request->status_approval);
            }
            
            if ($request->status) {
                $query->where('status', $request->status);
            }
            
            if ($request->nama) {
                $query->where('nama_guru', 'like', '%' . $request->nama . '%');
            }
            
            $gurus = $query->latest()->paginate(10);
            return view('guru.index', compact('gurus'));
        } catch (\Exception $e) {
            \Log::error('Error in GuruController@index: ' . $e->getMessage());
            return view('guru.index', ['gurus' => collect()->paginate(10)])
                ->with('error', 'Terjadi kesalahan saat memuat data guru.');
        }
    }

    public function create()
    {
        try {
            $users = User::whereIn('role', ['guru', 'wali_kelas'])
                ->whereDoesntHave('guru')
                ->get();
            return view('guru.create', compact('users'));
        } catch (\Exception $e) {
            \Log::error('Error in GuruController@create: ' . $e->getMessage());
            return redirect()->route('guru.index')
                ->with('error', 'Terjadi kesalahan saat membuka halaman tambah guru.');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'users_id' => 'required|exists:users,id',
            'nip' => 'required|string|unique:gurus,nip',
            'jenis_kelamin' => 'required|in:L,P',
            'bidang_studi' => 'nullable|string|max:255',
            'status' => 'nullable|in:aktif,tidak_aktif'
        ]);

        $user = User::findOrFail($request->users_id);
        Guru::create([
            'users_id' => $request->users_id,
            'nip' => $request->nip,
            'nama_guru' => $user->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'bidang_studi' => $request->bidang_studi,
            'status' => $request->status ?? 'aktif'
        ]);
        return redirect()->route('guru.index')->with('success', 'Guru berhasil ditambahkan!');
    }

    public function show(Guru $guru)
    {
        $guru->load('user');
        return response()->json($guru);
    }

    public function edit(Guru $guru)
    {
        try {
            return view('guru.edit', compact('guru'));
        } catch (\Exception $e) {
            \Log::error('Error in GuruController@edit: ' . $e->getMessage());
            return redirect()->route('guru.index')
                ->with('error', 'Terjadi kesalahan saat membuka halaman edit guru.');
        }
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nip' => 'required|string|unique:gurus,nip,' . $guru->id,
            'jenis_kelamin' => 'required|in:L,P',
            'bidang_studi' => 'nullable|string|max:255',
            'status' => 'nullable|in:aktif,tidak_aktif'
        ]);

        $guru->update($request->only(['nip', 'jenis_kelamin', 'bidang_studi', 'status']));
        return redirect()->route('guru.index')->with('success', 'Guru berhasil diupdate!');
    }

    public function destroy(Guru $guru)
    {
        $guru->user()->delete();
        return redirect()->route('guru.index')->with('success', 'Guru berhasil dihapus!');
    }

    public function approve($id)
    {
        $guru = Guru::findOrFail($id);
        $guru->update(['status_approval' => 'approved']);
        $guru->user->update(['status' => 'approved', 'verified_by' => auth()->id(), 'verified_at' => now()]);
        return redirect()->route('guru.index')->with('success', 'Guru berhasil disetujui!');
    }

    public function reject($id)
    {
        $guru = Guru::findOrFail($id);
        $guru->user->update(['status' => 'rejected', 'verified_by' => auth()->id(), 'verified_at' => now(), 'rejection_reason' => 'Ditolak oleh admin']);
        $guru->update(['status_approval' => 'rejected']);
        return redirect()->route('guru.index')->with('success', 'Guru berhasil ditolak!');
    }
}