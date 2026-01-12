<?php

namespace App\Http\Controllers;

use App\Models\ClassHomeroomTeacher;
use App\Models\User;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeroomTeacherController extends Controller
{
    public function index()
    {
        $assignments = ClassHomeroomTeacher::with(['user', 'kelas', 'tahunAjaran', 'assignedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('admin.homeroom-teachers.index', compact('assignments'));
    }

    public function create()
    {
        $teachers = User::where('role', 'guru')
            ->where('status', 'approved')
            ->orderBy('nama')
            ->get();
            
        $classes = Kelas::orderBy('nama_kelas')->get();
            
        $tahunAjarans = TahunAjaran::where('status_aktif', 'aktif')
            ->orderBy('tahun_mulai', 'desc')
            ->get();
            
        return view('admin.homeroom-teachers.create', compact('teachers', 'classes', 'tahunAjarans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id'
        ]);

        // Cek apakah user adalah guru
        $user = User::findOrFail($validated['user_id']);
        if ($user->role !== 'guru') {
            return back()->withErrors(['user_id' => 'Hanya guru yang bisa ditugaskan sebagai wali kelas.']);
        }

        // Cek apakah kelas sudah memiliki wali kelas untuk tahun ajaran ini
        $existing = ClassHomeroomTeacher::where('kelas_id', $validated['kelas_id'])
            ->where('tahun_ajaran_id', $validated['tahun_ajaran_id'])
            ->first();

        if ($existing) {
            return back()->withErrors(['kelas_id' => 'Kelas ini sudah memiliki wali kelas untuk tahun ajaran tersebut.']);
        }

        ClassHomeroomTeacher::create([
            'user_id' => $validated['user_id'],
            'kelas_id' => $validated['kelas_id'],
            'tahun_ajaran_id' => $validated['tahun_ajaran_id'],
            'assigned_by' => Auth::id(),
            'assigned_at' => now()
        ]);

        return redirect()->route('admin.homeroom-teachers.index')
            ->with('success', 'Wali kelas berhasil ditugaskan!');
    }

    public function destroy(ClassHomeroomTeacher $homeroomTeacher)
    {
        $homeroomTeacher->delete();
        
        return redirect()->route('admin.homeroom-teachers.index')
            ->with('success', 'Penugasan wali kelas berhasil dihapus!');
    }

    // API endpoint untuk assign wali kelas
    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id'
        ]);

        $user = User::findOrFail($validated['user_id']);
        if ($user->role !== 'guru') {
            return response()->json(['error' => 'Hanya guru yang bisa ditugaskan sebagai wali kelas.'], 422);
        }

        $existing = ClassHomeroomTeacher::where('kelas_id', $validated['kelas_id'])
            ->where('tahun_ajaran_id', $validated['tahun_ajaran_id'])
            ->first();

        if ($existing) {
            return response()->json(['error' => 'Kelas ini sudah memiliki wali kelas.'], 422);
        }

        $assignment = ClassHomeroomTeacher::create([
            'user_id' => $validated['user_id'],
            'kelas_id' => $validated['kelas_id'],
            'tahun_ajaran_id' => $validated['tahun_ajaran_id'],
            'assigned_by' => Auth::id(),
            'assigned_at' => now()
        ]);

        return response()->json([
            'message' => 'Wali kelas berhasil ditugaskan!',
            'data' => $assignment->load(['user', 'kelas', 'tahunAjaran'])
        ], 201);
    }

    public function apiDestroy(ClassHomeroomTeacher $homeroomTeacher)
    {
        $homeroomTeacher->delete();
        
        return response()->json(['message' => 'Penugasan wali kelas berhasil dihapus!']);
    }
}