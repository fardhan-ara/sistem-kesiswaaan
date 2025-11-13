<?php

namespace App\Http\Controllers;

use App\Models\Pelanggaran;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\JenisPelanggaran;
use App\Models\Sanksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\StorePelanggaranRequest;

class PelanggaranController extends Controller
{
    public function index()
    {
        $pelanggarans = Pelanggaran::with(['siswa.kelas', 'guru', 'jenisPelanggaran'])->latest()->get();
        return view('pelanggaran.index', compact('pelanggarans'));
    }

    public function create()
    {
        $siswas = Siswa::with('kelas')->get();
        $gurus = Guru::all();
        $jenisPelanggarans = JenisPelanggaran::all();
        return view('pelanggaran.create', compact('siswas', 'gurus', 'jenisPelanggarans'));
    }

    public function store(StorePelanggaranRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $jenisPelanggaran = JenisPelanggaran::find($validated['jenis_pelanggaran_id']);
            
            $pelanggaran = Pelanggaran::create([
                'siswa_id' => $validated['siswa_id'],
                'guru_id' => $validated['guru_id'],
                'jenis_pelanggaran_id' => $validated['jenis_pelanggaran_id'],
                'poin' => $jenisPelanggaran->poin,
                'keterangan' => $validated['keterangan'] ?? null,
                'status_verifikasi' => 'pending'
            ]);

            $siswa = Siswa::find($validated['siswa_id']);
            $totalPoin = Pelanggaran::where('siswa_id', $siswa->id)
                ->where('status_verifikasi', 'verified')
                ->sum('poin');

            if ($totalPoin >= 100) {
                $sanksi = Sanksi::create([
                    'pelanggaran_id' => $pelanggaran->id,
                    'nama_sanksi' => $jenisPelanggaran->sanksi_rekomendasi ?? 'Sanksi Otomatis',
                    'tanggal_mulai' => now(),
                    'tanggal_selesai' => now()->addDays(7),
                    'status_sanksi' => 'aktif'
                ]);

                $siswaUser = $siswa->user;
                $orangTua = User::where('role', 'ortu')->where('id', $siswa->users_id)->first();
                
                if ($siswaUser) {
                    $siswaUser->notify(new \App\Notifications\SanksiDijadwalkanNotification($sanksi));
                }
                
                if ($orangTua) {
                    $orangTua->notify(new \App\Notifications\SanksiDijadwalkanNotification($sanksi));
                }
            }

            $kesiswaan = User::where('role', 'kesiswaan')->get();
            $orangTua = User::where('role', 'ortu')->where('id', $siswa->users_id)->first();
            
            foreach ($kesiswaan as $user) {
                $user->notify(new \App\Notifications\PelanggaranBaruNotification($pelanggaran));
            }
            
            if ($orangTua) {
                $orangTua->notify(new \App\Notifications\PelanggaranBaruNotification($pelanggaran));
            }
        });

        return redirect()->route('pelanggaran.index')->with('success', 'Data pelanggaran berhasil ditambahkan');
    }

    public function edit(Pelanggaran $pelanggaran)
    {
        $siswas = Siswa::with('kelas')->get();
        $gurus = Guru::all();
        $jenisPelanggarans = JenisPelanggaran::all();
        return view('pelanggaran.edit', compact('pelanggaran', 'siswas', 'gurus', 'jenisPelanggarans'));
    }

    public function update(Request $request, Pelanggaran $pelanggaran)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'guru_id' => 'required|exists:gurus,id',
            'jenis_pelanggaran_id' => 'required|exists:jenis_pelanggarans,id'
        ]);

        $jenisPelanggaran = JenisPelanggaran::find($request->jenis_pelanggaran_id);
        
        $pelanggaran->update([
            'siswa_id' => $request->siswa_id,
            'guru_id' => $request->guru_id,
            'jenis_pelanggaran_id' => $request->jenis_pelanggaran_id,
            'poin' => $jenisPelanggaran->poin,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('pelanggaran.index')->with('success', 'Data pelanggaran berhasil diupdate');
    }

    public function destroy(Pelanggaran $pelanggaran)
    {
        $pelanggaran->delete();
        return redirect()->route('pelanggaran.index')->with('success', 'Data pelanggaran berhasil dihapus');
    }

    public function verify(Pelanggaran $pelanggaran)
    {
        if (!in_array(auth()->user()->role, ['admin', 'kesiswaan'])) {
            abort(403, 'Unauthorized action.');
        }

        $pelanggaran->update(['status_verifikasi' => 'verified']);
        return redirect()->route('pelanggaran.index')->with('success', 'Pelanggaran berhasil diverifikasi');
    }
}
