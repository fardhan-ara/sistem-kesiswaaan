<?php

namespace App\Http\Controllers;

use App\Models\BiodataOrtu;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BiodataOrtuController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'nis' => 'required|string|max:20',
            'nama_ayah' => 'nullable|string|max:255',
            'telp_ayah' => 'nullable|string|max:15',
            'nama_ibu' => 'nullable|string|max:255',
            'telp_ibu' => 'nullable|string|max:15',
            'nama_wali' => 'nullable|string|max:255',
            'telp_wali' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'foto_kk' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Validasi: Cari siswa berdasarkan nama dan NIS
        $siswa = Siswa::where('nama_siswa', 'LIKE', '%' . $validated['nama_siswa'] . '%')
            ->where('nis', $validated['nis'])
            ->first();

        if (!$siswa) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Data siswa tidak ditemukan. Pastikan Nama dan NIS sesuai dengan data di sekolah.');
        }

        // Cek apakah siswa sudah terhubung dengan ortu lain
        $existingBiodata = BiodataOrtu::where('siswa_id', $siswa->id)
            ->whereIn('status_approval', ['pending', 'approved'])
            ->first();

        if ($existingBiodata) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Siswa ini sudah terhubung dengan akun orang tua lain.');
        }

        // Upload files
        $fotoKK = $request->file('foto_kk')->store('biodata_ortu/kk', 'public');

        BiodataOrtu::create([
            'user_id' => Auth::id(),
            'siswa_id' => $siswa->id,
            'nama_ayah' => $validated['nama_ayah'],
            'telp_ayah' => $validated['telp_ayah'],
            'nama_ibu' => $validated['nama_ibu'],
            'telp_ibu' => $validated['telp_ibu'],
            'nama_wali' => $validated['nama_wali'],
            'telp_wali' => $validated['telp_wali'],
            'alamat' => $validated['alamat'],
            'foto_kk' => $fotoKK,
            'status_approval' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Biodata berhasil dikirim. Menunggu persetujuan admin.');
    }

    public function index()
    {
        try {
            $biodatas = BiodataOrtu::with(['user', 'siswa.kelas', 'approver'])
                ->orderBy('status_approval')
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return view('biodata_ortu.index', compact('biodatas'));
        } catch (\Exception $e) {
            \Log::error('Error in BiodataOrtuController@index: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $biodata = BiodataOrtu::with(['user', 'siswa.kelas', 'approver'])->findOrFail($id);
        return view('biodata_ortu.show', compact('biodata'));
    }

    public function approve($id)
    {
        $biodata = BiodataOrtu::findOrFail($id);
        $biodata->update([
            'status_approval' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Biodata disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['rejection_reason' => 'required|string|max:500']);

        $biodata = BiodataOrtu::findOrFail($id);
        $biodata->update([
            'status_approval' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Biodata ditolak.');
    }
}
