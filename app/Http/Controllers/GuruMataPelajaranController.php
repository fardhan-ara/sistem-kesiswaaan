<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruMataPelajaranController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $guru = Guru::where('users_id', $user->id)->firstOrFail();
        
        $mataPelajaranList = [
            'Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'IPA', 'IPS',
            'Fisika', 'Kimia', 'Biologi', 'Sejarah', 'Geografi', 'Ekonomi',
            'Sosiologi', 'PKn', 'Agama', 'Seni Budaya', 'PJOK', 'Prakarya',
            'Bahasa Daerah', 'Produktif TKJ', 'Produktif RPL', 'Produktif MM',
            'Produktif PPLG', 'Produktif TJKT', 'Produktif DKV', 'Produktif Animasi'
        ];
        
        return view('guru.mata-pelajaran', compact('guru', 'mataPelajaranList'));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        $guru = Guru::where('users_id', $user->id)->firstOrFail();
        
        $validated = $request->validate([
            'mata_pelajaran' => 'nullable|array',
            'mata_pelajaran.*' => 'string'
        ]);
        
        $guru->update([
            'mata_pelajaran' => $validated['mata_pelajaran'] ?? []
        ]);
        
        return redirect()->back()->with('success', 'Mata pelajaran berhasil diupdate!');
    }
}
