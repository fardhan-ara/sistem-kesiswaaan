<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{


    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role' => 'required|string'
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'role.required' => 'Role harus dipilih sebelum login'
        ]);

        if (Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password']
        ], $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            // Pastikan role yang dipilih pengguna sesuai dengan role akun
            $selectedRole = $request->input('role');

            // Pemetaan role backend ke beberapa nilai front-end yang mungkin dipilih
            $roleMap = [
                'admin' => ['admin'],
                'kesiswaan' => ['bk', 'kesiswaan', 'verifikator'],
                'guru' => ['guru'],
                'wali_kelas' => ['walikelas', 'wali_kelas'],
                'wali_guru' => ['wali_guru', 'waliguru', 'wali-guru'],
                'siswa' => ['siswa'],
                'ortu' => ['ortu']
            ];

            $userRoleKey = $user->role;
            $allowed = false;
            if (isset($roleMap[$userRoleKey])) {
                $allowed = in_array(strtolower($selectedRole), $roleMap[$userRoleKey]);
            } else {
                // fallback: bandingkan dengan normalisasi sederhana
                $allowed = str_replace('_', '', strtolower($userRoleKey)) === str_replace('_', '', strtolower($selectedRole));
            }

            if (! $allowed) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'role' => 'Role yang dipilih tidak sesuai dengan akun. Pastikan Anda memilih role yang benar.'
                ]);
            }
            
            // Redirect berdasarkan role
            return $this->redirectBasedOnRole($user);
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau password tidak valid.',
        ]);
    }

    private function redirectBasedOnRole($user)
    {
        switch ($user->role) {
            case 'admin':
                return redirect()->intended(route('dashboard'))
                    ->with('success', 'Selamat datang Admin!');
            case 'kesiswaan':
                return redirect()->intended(route('dashboard'))
                    ->with('success', 'Selamat datang Staff Kesiswaan!');
            case 'guru':
            case 'wali_kelas':
            case 'wali_guru':
                return redirect()->intended(route('pelanggaran.index'))
                    ->with('success', 'Selamat datang Guru!');
            case 'siswa':
                return redirect()->intended(route('dashboard'))
                    ->with('success', 'Selamat datang Siswa!');
            case 'ortu':
                return redirect()->intended(route('dashboard'))
                    ->with('success', 'Selamat datang Orang Tua!');
            default:
                return redirect()->route('dashboard');
        }
    }

    public function showRegister()
    {
        // Hanya admin yang bisa akses halaman register
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Hanya admin yang bisa register user baru
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,kesiswaan,guru,wali_kelas,wali_guru,siswa,ortu'
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
            'email_verified_at' => now()
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil didaftarkan!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil logout.');
    }
}
