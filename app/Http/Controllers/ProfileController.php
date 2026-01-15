<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\BiodataOrtu;

class ProfileController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();
            
            if ($user->role === 'admin') {
                return redirect()->route('dashboard')
                    ->with('info', 'Admin tidak memiliki halaman profil');
            }
            
            $profileData = $this->getProfileData($user);
            
            return view('profile.index', compact('user', 'profileData'));
        } catch (\Exception $e) {
            \Log::error('Profile Error: ' . $e->getMessage());
            return redirect()->route('dashboard')
                ->with('error', 'Terjadi kesalahan saat memuat profil');
        }
    }
    
    private function getProfileData($user)
    {
        try {
            $userId = $user->id;
            
            switch ($user->role) {
                case 'siswa':
                    return $this->getSiswaProfile($userId);
                    
                case 'guru':
                case 'wali_kelas':
                case 'bk':
                    return $this->getGuruProfile($userId, $user);
                    
                case 'ortu':
                    return $this->getOrtuProfile($userId);
                    
                case 'kesiswaan':
                case 'kepala_sekolah':
                    return ['type' => 'staff', 'data' => null];
                    
                default:
                    return ['type' => 'unknown', 'data' => null];
            }
        } catch (\Exception $e) {
            \Log::error('getProfileData Error: ' . $e->getMessage());
            return ['type' => 'error', 'data' => null, 'message' => 'Terjadi kesalahan'];
        }
    }
    
    private function getSiswaProfile($userId)
    {
        $siswa = Siswa::with(['kelas', 'tahunAjaran'])
            ->where('users_id', $userId)
            ->first();
        
        if (!$siswa) {
            return ['type' => 'siswa', 'data' => null, 'message' => 'Data siswa tidak ditemukan'];
        }
        
        return [
            'type' => 'siswa',
            'data' => $siswa,
            'stats' => [
                'total_pelanggaran' => DB::table('pelanggarans')
                    ->where('siswa_id', $siswa->id)
                    ->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi'])
                    ->count(),
                'total_prestasi' => DB::table('prestasis')
                    ->where('siswa_id', $siswa->id)
                    ->where('status_verifikasi', 'verified')
                    ->count(),
                'total_poin' => DB::table('pelanggarans')
                    ->where('siswa_id', $siswa->id)
                    ->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi'])
                    ->sum('poin') ?? 0
            ]
        ];
    }
    
    private function getGuruProfile($userId, $user)
    {
        $guru = Guru::where('users_id', $userId)->first();
        
        if (!$guru) {
            return ['type' => 'guru', 'data' => null, 'message' => 'Data guru tidak ditemukan'];
        }
        
        $kelasWali = null;
        if ($user->is_wali_kelas) {
            $kelasWali = DB::table('kelas')
                ->where('wali_kelas_id', $guru->id)
                ->where('is_active', true)
                ->first();
        }
        
        return [
            'type' => 'guru',
            'data' => $guru,
            'kelas_wali' => $kelasWali,
            'stats' => [
                'total_pelanggaran' => DB::table('pelanggarans')
                    ->where('guru_pencatat', $guru->id)
                    ->count(),
                'total_prestasi' => DB::table('prestasis')
                    ->where('guru_pencatat', $guru->id)
                    ->count()
            ]
        ];
    }
    
    private function getOrtuProfile($userId)
    {
        $biodata = BiodataOrtu::with(['siswa.kelas', 'siswa.tahunAjaran'])
            ->where('user_id', $userId)
            ->first();
        
        if (!$biodata) {
            return ['type' => 'ortu', 'data' => null, 'message' => 'Biodata orang tua belum dilengkapi'];
        }
        
        $stats = null;
        if ($biodata->status_approval === 'approved' && $biodata->siswa) {
            $stats = [
                'total_pelanggaran' => DB::table('pelanggarans')
                    ->where('siswa_id', $biodata->siswa_id)
                    ->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi'])
                    ->count(),
                'total_prestasi' => DB::table('prestasis')
                    ->where('siswa_id', $biodata->siswa_id)
                    ->where('status_verifikasi', 'verified')
                    ->count(),
                'total_poin' => DB::table('pelanggarans')
                    ->where('siswa_id', $biodata->siswa_id)
                    ->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi'])
                    ->sum('poin') ?? 0
            ];
        }
        
        return [
            'type' => 'ortu',
            'data' => $biodata,
            'stats' => $stats
        ];
    }
    
    public function edit()
    {
        $user = auth()->user();
        
        if ($user->role === 'admin') {
            return redirect()->route('dashboard')
                ->with('info', 'Admin tidak dapat mengedit profil');
        }
        
        $profileData = $this->getProfileData($user);
        
        return view('profile.edit', compact('user', 'profileData'));
    }
    
    public function update(Request $request)
    {
        // CRITICAL: Validasi user yang sedang login
        $user = auth()->user();
        
        if ($user->role === 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'Admin tidak dapat mengedit profil');
        }
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_telp' => 'nullable|string|max:20',
            'password' => 'nullable|min:8|confirmed',
            'foto' => 'nullable|image|max:2048'
        ]);
        
        DB::beginTransaction();
        try {
            // Update user table
            $userData = [
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'no_telp' => $validated['no_telp'] ?? null
            ];
            
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($validated['password']);
            }
            
            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('profile', 'public');
                $userData['foto'] = $path;
            }
            
            // CRITICAL: Update HANYA user yang sedang login
            User::where('id', $user->id)->update($userData);
            
            // Update data terkait berdasarkan role
            switch ($user->role) {
                case 'siswa':
                    // Update nama siswa HANYA untuk user ini
                    Siswa::where('users_id', $user->id)
                        ->update(['nama_siswa' => $validated['nama']]);
                    break;
                    
                case 'guru':
                case 'wali_kelas':
                case 'bk':
                    // Update nama guru HANYA untuk user ini
                    Guru::where('users_id', $user->id)
                        ->update(['nama_guru' => $validated['nama']]);
                    break;
            }
            
            DB::commit();
            
            return redirect()->route('profile.index')
                ->with('success', 'Profil berhasil diperbarui');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage())
                ->withInput();
        }
    }
}
