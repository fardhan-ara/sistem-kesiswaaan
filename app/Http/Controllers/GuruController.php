<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::with('user')->latest()->paginate(10);
        return view('guru.index', compact('gurus'));
    }

    public function create()
    {
        $users = User::whereIn('role', ['guru', 'wali_kelas'])
            ->whereDoesntHave('guru')
            ->get();
        return view('guru.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'users_id' => 'required|exists:users,id',
            'nip' => 'required|string|unique:gurus,nip',
            'nama_guru' => 'required|string|max:255',
            'bidang_studi' => 'nullable|string|max:255',
            'status' => 'nullable|in:aktif,tidak_aktif'
        ]);

        Guru::create($request->all());
        return redirect()->route('guru.index')->with('success', 'Guru berhasil ditambahkan!');
    }

    public function edit(Guru $guru)
    {
        $users = User::whereIn('role', ['guru', 'wali_kelas'])->where(function($q) use ($guru) {
            $q->whereDoesntHave('guru')->orWhere('id', $guru->user_id);
        })->get();
        return view('guru.edit', compact('guru', 'users'));
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'users_id' => 'required|exists:users,id',
            'nip' => 'required|string|unique:gurus,nip,' . $guru->id,
            'nama_guru' => 'required|string|max:255',
            'bidang_studi' => 'nullable|string|max:255',
            'status' => 'nullable|in:aktif,tidak_aktif'
        ]);

        $guru->update($request->all());
        return redirect()->route('guru.index')->with('success', 'Guru berhasil diupdate!');
    }

    public function destroy(Guru $guru)
    {
        $guru->delete();
        return redirect()->route('guru.index')->with('success', 'Guru berhasil dihapus!');
    }
}