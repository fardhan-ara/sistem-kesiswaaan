<?php

namespace App\Http\Controllers;

use App\Models\KomunikasiOrtu;
use App\Models\BalasanKomunikasi;
use App\Models\PanggilanOrtu;
use App\Models\Siswa;
use App\Models\BiodataOrtu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomunikasiOrtuController extends Controller
{
    // Inbox - Daftar pesan masuk
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'ortu') {
            $komunikasis = KomunikasiOrtu::where(function($q) use ($user) {
                    $q->where('penerima_id', $user->id)
                      ->orWhere('pengirim_id', $user->id);
                })
                ->with(['pengirim', 'penerima', 'siswa', 'balasan'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        } else {
            $komunikasis = KomunikasiOrtu::where('pengirim_id', $user->id)
                ->orWhere('penerima_id', $user->id)
                ->with(['pengirim', 'penerima', 'siswa', 'balasan'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }

        return view('komunikasi.index', compact('komunikasis'));
    }

    // Form kirim pesan baru
    public function create()
    {
        $user = Auth::user();
        
        if ($user->role === 'ortu') {
            $biodata = BiodataOrtu::where('user_id', $user->id)->first();
            $siswas = $biodata ? Siswa::where('id', $biodata->siswa_id)->get() : collect();
        } else {
            $siswas = Siswa::with('kelas')->orderBy('nama_siswa')->get();
        }

        return view('komunikasi.create', compact('siswas'));
    }

    // Kirim pesan
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Jika ortu, siswa_id otomatis dari biodata
        if ($user->role === 'ortu') {
            $biodata = BiodataOrtu::where('user_id', $user->id)->first();
            $siswaId = $biodata->siswa_id;
        } else {
            $siswaId = $request->siswa_id;
        }
        
        $validated = $request->validate([
            'penerima_id' => 'required|exists:users,id',
            'jenis' => 'required|in:pesan,laporan_pembinaan,konsultasi',
            'subjek' => 'required|string|max:255',
            'isi_pesan' => 'required|string',
            'lampiran' => 'nullable|file|max:5120'
        ]);

        $lampiran = null;
        if ($request->hasFile('lampiran')) {
            $lampiran = $request->file('lampiran')->store('komunikasi', 'public');
        }

        KomunikasiOrtu::create([
            'siswa_id' => $siswaId,
            'pengirim_id' => Auth::id(),
            'penerima_id' => $validated['penerima_id'],
            'jenis' => $validated['jenis'],
            'subjek' => $validated['subjek'],
            'isi_pesan' => $validated['isi_pesan'],
            'lampiran' => $lampiran
        ]);

        return redirect()->route('komunikasi.index')->with('success', 'Pesan berhasil dikirim.');
    }

    // Detail pesan
    public function show($id)
    {
        $komunikasi = KomunikasiOrtu::with(['pengirim', 'penerima', 'siswa', 'balasan.pengirim'])->findOrFail($id);
        
        // Mark as read
        if ($komunikasi->penerima_id == Auth::id() && !$komunikasi->dibaca_at) {
            $komunikasi->update(['status' => 'dibaca', 'dibaca_at' => now()]);
        }

        return view('komunikasi.show', compact('komunikasi'));
    }

    // Balas pesan
    public function reply(Request $request, $id)
    {
        $validated = $request->validate([
            'isi_balasan' => 'required|string',
            'lampiran' => 'nullable|file|max:5120'
        ]);

        $komunikasi = KomunikasiOrtu::findOrFail($id);

        $lampiran = null;
        if ($request->hasFile('lampiran')) {
            $lampiran = $request->file('lampiran')->store('komunikasi', 'public');
        }

        BalasanKomunikasi::create([
            'komunikasi_id' => $id,
            'pengirim_id' => Auth::id(),
            'isi_balasan' => $validated['isi_balasan'],
            'lampiran' => $lampiran
        ]);

        $komunikasi->update(['status' => 'dibalas']);

        return redirect()->back()->with('success', 'Balasan berhasil dikirim.');
    }

    // Daftar panggilan orang tua
    public function panggilan()
    {
        $user = Auth::user();
        
        if ($user->role === 'ortu') {
            $biodata = BiodataOrtu::where('user_id', $user->id)->first();
            $panggilan = $biodata ? PanggilanOrtu::where('siswa_id', $biodata->siswa_id)
                ->with(['siswa', 'pelanggaran', 'pembuatPanggilan'])
                ->orderBy('tanggal_panggilan', 'desc')
                ->get() : collect();
        } else {
            $panggilan = PanggilanOrtu::with(['siswa', 'pelanggaran', 'pembuatPanggilan'])
                ->orderBy('tanggal_panggilan', 'desc')
                ->paginate(20);
        }

        return view('komunikasi.panggilan', compact('panggilan'));
    }

    // Form buat panggilan ortu
    public function createPanggilan()
    {
        $siswas = Siswa::with('kelas')->orderBy('nama_siswa')->get();
        return view('komunikasi.create_panggilan', compact('siswas'));
    }

    // Simpan panggilan ortu
    public function storePanggilan(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'pelanggaran_id' => 'nullable|exists:pelanggarans,id',
            'judul' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'tanggal_panggilan' => 'required|date',
            'tempat' => 'required|string|max:255'
        ]);

        PanggilanOrtu::create([
            'siswa_id' => $validated['siswa_id'],
            'pelanggaran_id' => $validated['pelanggaran_id'],
            'dibuat_oleh' => Auth::id(),
            'judul' => $validated['judul'],
            'keterangan' => $validated['keterangan'],
            'tanggal_panggilan' => $validated['tanggal_panggilan'],
            'tempat' => $validated['tempat']
        ]);

        return redirect()->route('komunikasi.panggilan')->with('success', 'Panggilan orang tua berhasil dibuat.');
    }

    // Konfirmasi kehadiran
    public function konfirmasiPanggilan($id)
    {
        $panggilan = PanggilanOrtu::findOrFail($id);
        $panggilan->update([
            'status' => 'dikonfirmasi',
            'dikonfirmasi_at' => now()
        ]);

        return redirect()->back()->with('success', 'Kehadiran berhasil dikonfirmasi.');
    }

    // Selesaikan panggilan dengan catatan
    public function selesaikanPanggilan(Request $request, $id)
    {
        $validated = $request->validate([
            'catatan_hasil' => 'required|string'
        ]);

        $panggilan = PanggilanOrtu::findOrFail($id);
        $panggilan->update([
            'status' => 'selesai',
            'catatan_hasil' => $validated['catatan_hasil']
        ]);

        return redirect()->back()->with('success', 'Panggilan selesai dengan catatan.');
    }

    // Hapus pesan
    public function destroy($id)
    {
        $komunikasi = KomunikasiOrtu::findOrFail($id);
        
        // Cek authorization - hanya pengirim atau penerima yang bisa hapus
        if ($komunikasi->pengirim_id != Auth::id() && $komunikasi->penerima_id != Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus pesan ini.');
        }
        
        $komunikasi->delete();
        return redirect()->route('komunikasi.index')->with('success', 'Pesan berhasil dihapus.');
    }
}
