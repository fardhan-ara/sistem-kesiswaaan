<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLanding()
    {
        return view('landing');
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function showAdminLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('dashboard');
            }
            Auth::logout();
        }
        return view('auth.admin_login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter'
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            Auth::user()->update(['last_login_at' => now(), 'last_activity_at' => now()]);
            return $this->redirectBasedOnRole(Auth::user());
        }

        // Redirect back to landing page with error
        return redirect()->route('landing')
            ->with('error', 'Email atau password tidak valid.')
            ->withInput();
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            if ($user->role !== 'admin') {
                Auth::logout();
                throw ValidationException::withMessages(['email' => 'Akses ditolak. Hanya admin yang dapat login di sini.']);
            }
            $user->update(['last_login_at' => now(), 'last_activity_at' => now()]);
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Selamat datang Admin!');
        }

        throw ValidationException::withMessages(['email' => 'Email atau password tidak valid.']);
    }

    private function redirectBasedOnRole($user)
    {
        // Status check untuk semua role kecuali admin
        if ($user->role !== 'admin') {
            if ($user->status === 'pending') {
                Auth::logout();
                return redirect()->route('landing')->with('warning', 'Akun Anda menunggu persetujuan admin. Silakan cek email untuk notifikasi.');
            }
            if ($user->status === 'rejected') {
                Auth::logout();
                return redirect()->route('landing')->with('error', 'Akun ditolak oleh admin. Alasan: ' . ($user->rejection_reason ?? 'Tidak ada keterangan'));
            }
        }

        // Role-based redirect - semua ke dashboard yang sama, DashboardController yang handle role
        switch ($user->role) {
            case 'admin':
                return redirect()->intended(route('dashboard'))->with('success', 'Selamat datang Admin!');
            
            case 'kesiswaan':
                return redirect()->intended(route('dashboard'))->with('success', 'Selamat datang Staff Kesiswaan!');
            
            case 'guru':
                return redirect()->intended(route('dashboard'))->with('success', 'Selamat datang Guru!');
            
            case 'bk':
                return redirect()->intended(route('dashboard'))->with('success', 'Selamat datang Guru BK!');
            
            case 'kepala_sekolah':
                return redirect()->intended(route('dashboard'))->with('success', 'Selamat datang Kepala Sekolah!');
            
            case 'siswa':
                return redirect()->intended(route('dashboard'))->with('success', 'Selamat datang Siswa!');
            
            case 'ortu':
                return redirect()->intended(route('dashboard'))->with('success', 'Selamat datang Orang Tua!');
            
            default:
                return redirect()->route('dashboard');
        }
    }

    public function showRegister()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        return view('auth.register');
    }

    public function showPublicRegister()
    {
        // Jika sudah login, tampilkan pesan dan tombol logout
        $isLoggedIn = Auth::check();
        $currentUser = $isLoggedIn ? Auth::user() : null;
        
        $kelas = \App\Models\Kelas::all();
        $tahunAjarans = \App\Models\TahunAjaran::where('status_aktif', 'aktif')->get();
        
        return view('auth.register_public', compact('kelas', 'tahunAjarans', 'isLoggedIn', 'currentUser'));
    }

    public function register(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,kesiswaan,guru,siswa,ortu,bk,kepala_sekolah'
        ], [
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role wajib dipilih'
        ]);

        $user = User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => 'approved',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'email_verified_at' => now()
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil didaftarkan!');
    }

    public function publicRegister(Request $request)
    {
        // Validasi awal tanpa unique check dulu untuk custom error message
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:kesiswaan,guru,siswa,ortu,bk',
            'mata_pelajaran' => 'required_if:role,guru|nullable|string|max:100',
            'nis' => 'required_if:role,siswa|nullable|string|max:20',
            'kelas_id' => 'required_if:role,siswa|nullable|exists:kelas,id',
            'tahun_ajaran_id' => 'required_if:role,siswa|nullable|exists:tahun_ajarans,id',
            'jenis_kelamin' => 'required_if:role,siswa,guru|nullable|in:L,P',
            'nama_anak' => 'required_if:role,ortu|nullable|string|max:255',
            'nis_anak' => 'required_if:role,ortu|nullable|string|max:20'
        ], [
            'nama_anak.required_if' => 'Nama anak wajib diisi untuk pendaftaran orang tua',
            'nis_anak.required_if' => 'NIS anak wajib diisi untuk pendaftaran orang tua'
        ]);

        // Cek email sudah terdaftar atau belum
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            return back()->withErrors([
                'email' => 'Email ' . $request->email . ' sudah terdaftar dengan role ' . $existingUser->role . '. Silakan gunakan email lain atau login jika ini akun Anda.'
            ])->withInput();
        }

        // Validasi NIS untuk siswa
        if ($request->role === 'siswa') {
            $existingNis = \App\Models\Siswa::where('nis', $request->nis)->first();
            if ($existingNis) {
                return back()->withErrors([
                    'nis' => 'NIS ' . $request->nis . ' sudah terdaftar. Silakan gunakan NIS yang berbeda atau hubungi admin.'
                ])->withInput();
            }
        }

        // Validasi kecocokan data anak untuk role ortu
        if ($request->role === 'ortu') {
            $siswa = \App\Models\Siswa::where('nis', $request->nis_anak)
                ->whereRaw('LOWER(nama_siswa) = ?', [strtolower($request->nama_anak)])
                ->first();
            
            if (!$siswa) {
                return back()->withErrors([
                    'nis_anak' => 'Data anak dengan NIS "' . $request->nis_anak . '" tidak ditemukan atau nama tidak sesuai. Pastikan NIS benar (nama tidak harus sama persis huruf besar/kecilnya).'
                ])->withInput();
            }
        }

        // Semua validasi lolos, buat user
        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'pending',
            'nama_anak' => $request->nama_anak ?? null,
            'nis_anak' => $request->nis_anak ?? null
        ]);

        if ($request->role === 'guru') {
            \App\Models\Guru::create([
                'users_id' => $user->id,
                'nip' => 'TEMP-' . time(),
                'nama_guru' => $request->nama,
                'jenis_kelamin' => $request->jenis_kelamin ?? null,
                'bidang_studi' => $request->mata_pelajaran ?? null,
                'status' => 'aktif',
                'status_approval' => 'pending'
            ]);
        } elseif ($request->role === 'siswa') {
            \App\Models\Siswa::create([
                'users_id' => $user->id,
                'nis' => $request->nis,
                'nama_siswa' => $request->nama,
                'kelas_id' => $request->kelas_id,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tahun_ajaran_id' => $request->tahun_ajaran_id,
                'status_approval' => 'pending'
            ]);
        }

        // Tidak auto login, redirect ke landing dengan pesan
        return redirect()->route('landing')
            ->with('success', 'Pendaftaran berhasil! Akun Anda menunggu persetujuan admin. Silakan cek email untuk notifikasi.');
    }

    public function removeUserData(Request $request)
    {
        // Implementation for removing user data if needed
        return response()->json(['success' => true]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing')
            ->with('success', 'Anda telah berhasil logout.');
    }
}