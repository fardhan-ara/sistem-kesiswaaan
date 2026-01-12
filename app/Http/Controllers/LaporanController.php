<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Pelanggaran;
use App\Models\Prestasi;
use App\Models\Kelas;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman form filter laporan
     */
    public function index()
    {
        // Ambil semua kelas untuk dropdown filter
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('laporan.index', compact('kelas'));
    }

    /**
     * Generate PDF Laporan Pelanggaran
     * Alur: Validasi -> Query dengan Eloquent -> Format Data -> Generate PDF
     */
    public function pelanggaranPdf(Request $request)
    {
        // 1. VALIDASI INPUT
        $validated = $request->validate([
            'kelas_id' => 'nullable|exists:kelas,id',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai'
        ], [
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai'
        ]);

        // 2. QUERY DATA dengan Eager Loading (optimasi N+1 problem)
        $query = Pelanggaran::with(['siswa.kelas', 'guru', 'jenisPelanggaran'])
            ->where('status_verifikasi', 'diverifikasi'); // Hanya yang terverifikasi
        
        // Filter berdasarkan kelas
        $filter_kelas = null;
        if ($request->kelas_id) {
            $kelas = Kelas::find($request->kelas_id);
            $filter_kelas = $kelas->nama_kelas;
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }
        
        // Filter berdasarkan tanggal
        $filter_tanggal = null;
        if ($request->tanggal_mulai && $request->tanggal_selesai) {
            $filter_tanggal = Carbon::parse($request->tanggal_mulai)->isoFormat('D MMMM Y') . 
                            ' s/d ' . 
                            Carbon::parse($request->tanggal_selesai)->isoFormat('D MMMM Y');
            $query->whereBetween('tanggal_pelanggaran', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }
        
        $pelanggarans = $query->orderBy('tanggal_pelanggaran', 'desc')->get();
        
        // 3. FORMAT DATA untuk tabel PDF
        $data = [];
        foreach ($pelanggarans as $index => $p) {
            $data[] = [
                $index + 1,
                $p->siswa->nis ?? '-',
                $p->siswa->nama_siswa ?? '-',
                $p->siswa->kelas->nama_kelas ?? '-',
                $p->jenisPelanggaran->nama_pelanggaran ?? '-',
                $p->poin ?? 0,
                Carbon::parse($p->tanggal_pelanggaran)->isoFormat('D MMM Y'),
                $p->guru->nama_guru ?? '-'
            ];
        }
        
        // 4. GENERATE PDF dengan DomPDF
        $pdf = Pdf::loadView('laporan.laporan_pdf', [
            'judul' => 'LAPORAN DATA PELANGGARAN SISWA',
            'columns' => ['No', 'NIS', 'Nama Siswa', 'Kelas', 'Jenis Pelanggaran', 'Poin', 'Tanggal', 'Guru Pencatat'],
            'data' => $data,
            'filter_kelas' => $filter_kelas,
            'filter_tanggal' => $filter_tanggal,
            'total_data' => count($data)
        ])->setPaper('a4', 'landscape');
        
        // Stream PDF ke browser (bisa langsung dilihat)
        return $pdf->stream('Laporan-Pelanggaran-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Generate PDF Laporan Prestasi
     */
    public function prestasiPdf(Request $request)
    {
        // 1. VALIDASI
        $validated = $request->validate([
            'kelas_id' => 'nullable|exists:kelas,id',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai'
        ]);

        // 2. QUERY dengan Eager Loading
        $query = Prestasi::with(['siswa.kelas', 'jenisPrestasi', 'guru'])
            ->where('status_verifikasi', 'verified');
        
        $filter_kelas = null;
        if ($request->kelas_id) {
            $kelas = Kelas::find($request->kelas_id);
            $filter_kelas = $kelas->nama_kelas;
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }
        
        $filter_tanggal = null;
        if ($request->tanggal_mulai && $request->tanggal_selesai) {
            $filter_tanggal = Carbon::parse($request->tanggal_mulai)->isoFormat('D MMMM Y') . 
                            ' s/d ' . 
                            Carbon::parse($request->tanggal_selesai)->isoFormat('D MMMM Y');
            $query->whereBetween('tanggal_prestasi', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }
        
        $prestasis = $query->orderBy('tanggal_prestasi', 'desc')->get();
        
        // 3. FORMAT DATA
        $data = [];
        foreach ($prestasis as $index => $p) {
            $data[] = [
                $index + 1,
                $p->siswa->nis ?? '-',
                $p->siswa->nama_siswa ?? '-',
                $p->siswa->kelas->nama_kelas ?? '-',
                $p->jenisPrestasi->nama_prestasi ?? '-',
                $p->poin ?? 0,
                Carbon::parse($p->tanggal_prestasi)->isoFormat('D MMM Y'),
                $p->guru->nama_guru ?? '-'
            ];
        }
        
        // 4. GENERATE PDF
        $pdf = Pdf::loadView('laporan.laporan_pdf', [
            'judul' => 'LAPORAN DATA PRESTASI SISWA',
            'columns' => ['No', 'NIS', 'Nama Siswa', 'Kelas', 'Jenis Prestasi', 'Poin', 'Tanggal', 'Guru Pencatat'],
            'data' => $data,
            'filter_kelas' => $filter_kelas,
            'filter_tanggal' => $filter_tanggal,
            'total_data' => count($data)
        ])->setPaper('a4', 'landscape');
        
        return $pdf->stream('Laporan-Prestasi-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Generate PDF Laporan Data Siswa
     */
    public function siswaPdf(Request $request)
    {
        // 1. VALIDASI
        $validated = $request->validate([
            'kelas_id' => 'nullable|exists:kelas,id'
        ]);

        // 2. QUERY dengan Eager Loading
        $query = Siswa::with(['kelas', 'tahunAjaran']);
        
        $filter_kelas = null;
        if ($request->kelas_id) {
            $kelas = Kelas::find($request->kelas_id);
            $filter_kelas = $kelas->nama_kelas;
            $query->where('kelas_id', $request->kelas_id);
        }
        
        $siswas = $query->orderBy('nama_siswa')->get();
        
        // 3. FORMAT DATA dengan hitung total pelanggaran & prestasi
        $data = [];
        foreach ($siswas as $index => $s) {
            // Hitung total poin pelanggaran yang terverifikasi
            $total_pelanggaran = $s->pelanggarans()
                ->where('status_verifikasi', 'diverifikasi')
                ->count();
            
            // Hitung total prestasi yang terverifikasi
            $total_prestasi = $s->prestasis()
                ->where('status_verifikasi', 'verified')
                ->count();
            
            $data[] = [
                $index + 1,
                $s->nis,
                $s->nama_siswa,
                $s->kelas->nama_kelas ?? '-',
                $s->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
                $total_pelanggaran,
                $total_prestasi
            ];
        }
        
        // 4. GENERATE PDF
        $pdf = Pdf::loadView('laporan.laporan_pdf', [
            'judul' => 'LAPORAN DATA SISWA',
            'columns' => ['No', 'NIS', 'Nama Siswa', 'Kelas', 'Jenis Kelamin', 'Total Pelanggaran', 'Total Prestasi'],
            'data' => $data,
            'filter_kelas' => $filter_kelas,
            'total_data' => count($data)
        ])->setPaper('a4', 'landscape');
        
        return $pdf->stream('Laporan-Siswa-' . date('Y-m-d') . '.pdf');
    }
}
