<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{


    public function index(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat mengakses halaman ini');
        }
        
        $query = User::query();
        
        if ($request->role) {
            $query->where('role', $request->role);
        }
        
        if ($request->nama) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }
        
        if ($request->email) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }
        
        $users = $query->orderByRaw('last_login_at IS NULL, last_login_at DESC')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
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

        User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => 'pending',
            'email_verified_at' => now()
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan! Status: Menunggu Persetujuan.');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Proteksi admin utama
        if ($user->email === 'admin@test.com') {
            return redirect()->route('users.index')
                ->with('error', 'Admin utama tidak dapat diubah!');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,kesiswaan,guru,siswa,ortu,bk,kepala_sekolah',
            'status' => 'required|in:pending,approved,rejected',
            'password' => 'nullable|min:6|confirmed',
            'siswa_id' => 'required_if:role,ortu|nullable|exists:siswas,id'
        ], [
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role wajib dipilih',
            'status.required' => 'Status wajib dipilih',
            'siswa_id.required_if' => 'Siswa wajib dipilih untuk role Orang Tua'
        ]);

        $updateData = [
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'status' => $validated['status']
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        if ($validated['role'] === 'ortu' && !empty($validated['siswa_id'])) {
            $updateData['metadata'] = json_encode(['siswa_id' => $validated['siswa_id']]);
        }

        $user->update($updateData);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        // Proteksi admin utama
        if ($user->email === 'admin@test.com') {
            return redirect()->route('users.index')
                ->with('error', 'Admin utama tidak dapat dihapus!');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus!');
    }

    public function approve(User $user)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat menyetujui user');
        }
        
        \Log::info('Approve user called', ['user_id' => $user->id, 'user_email' => $user->email, 'admin_id' => Auth::id()]);
        
        $user->update([
            'status' => 'approved',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'rejection_reason' => null
        ]);

        if ($user->guru) {
            $user->guru->update(['status_approval' => 'approved']);
            \Log::info('Guru approved', ['guru_id' => $user->guru->id]);
        }
        if ($user->siswa) {
            $user->siswa->update(['status_approval' => 'approved']);
            \Log::info('Siswa approved', ['siswa_id' => $user->siswa->id]);
        }

        \Log::info('User approved successfully', ['user_id' => $user->id]);
        return redirect()->route('users.index')
            ->with('success', 'User berhasil disetujui!');
    }

    public function reject(Request $request, User $user)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat menolak user');
        }
        
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $user->update([
            'status' => 'rejected',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'rejection_reason' => $validated['rejection_reason']
        ]);

        if ($user->guru) {
            $user->guru->update(['status_approval' => 'rejected']);
        }
        if ($user->siswa) {
            $user->siswa->update(['status_approval' => 'rejected']);
        }

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditolak!');
    }
}