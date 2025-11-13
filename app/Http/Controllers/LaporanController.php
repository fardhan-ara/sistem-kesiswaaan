<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Pelanggaran;
use App\Models\Prestasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $kelas = \App\Models\Kelas::all();
        return view('laporan.index', compact('kelas'));
    }

    public function pelanggaranPdf(Request $request)
    {
        $query = Pelanggaran::with(['siswa.kelas', 'guru', 'jenisPelanggaran']);
        
        $filter_kelas = null;
        if ($request->kelas_id) {
            $kelas = \App\Models\Kelas::find($request->kelas_id);
            $filter_kelas = $kelas ? $kelas->nama_kelas : null;
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }
        
        $filter_tanggal = null;
        if ($request->tanggal_mulai && $request->tanggal_selesai) {
            $filter_tanggal = date('d-m-Y', strtotime($request->tanggal_mulai)) . ' s/d ' . date('d-m-Y', strtotime($request->tanggal_selesai));
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }
        
        $pelanggarans = $query->get();
        
        $data = [];
        foreach ($pelanggarans as $index => $p) {
            $data[] = [
                $index + 1,
                $p->siswa->nis ?? '-',
                $p->siswa->nama_siswa ?? '-',
                $p->siswa->kelas->nama_kelas ?? '-',
                $p->jenisPelanggaran->nama_pelanggaran ?? '-',
                $p->jenisPelanggaran->poin ?? 0,
                date('d-m-Y', strtotime($p->tanggal_pelanggaran)),
                $p->guru->nama_guru ?? '-'
            ];
        }
        
        $pdf = Pdf::loadView('laporan.laporan_pdf', [
            'judul' => 'Pelanggaran Siswa',
            'columns' => ['No', 'NIS', 'Nama Siswa', 'Kelas', 'Jenis Pelanggaran', 'Poin', 'Tanggal', 'Guru'],
            'data' => $data,
            'filter_kelas' => $filter_kelas,
            'filter_tanggal' => $filter_tanggal
        ])->setPaper('a4', 'landscape');
        return $pdf->stream('laporan-pelanggaran.pdf');
    }

    public function prestasiPdf(Request $request)
    {
        $query = Prestasi::with(['siswa.kelas', 'jenisPrestasi']);
        
        $filter_kelas = null;
        if ($request->kelas_id) {
            $kelas = \App\Models\Kelas::find($request->kelas_id);
            $filter_kelas = $kelas ? $kelas->nama_kelas : null;
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }
        
        $filter_tanggal = null;
        if ($request->tanggal_mulai && $request->tanggal_selesai) {
            $filter_tanggal = date('d-m-Y', strtotime($request->tanggal_mulai)) . ' s/d ' . date('d-m-Y', strtotime($request->tanggal_selesai));
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }
        
        $prestasis = $query->get();
        
        $data = [];
        foreach ($prestasis as $index => $p) {
            $data[] = [
                $index + 1,
                $p->siswa->nis ?? '-',
                $p->siswa->nama_siswa ?? '-',
                $p->siswa->kelas->nama_kelas ?? '-',
                $p->jenisPrestasi->nama_prestasi ?? '-',
                $p->jenisPrestasi->poin ?? 0,
                date('d-m-Y', strtotime($p->tanggal_prestasi)),
                ucfirst($p->status)
            ];
        }
        
        $pdf = Pdf::loadView('laporan.laporan_pdf', [
            'judul' => 'Prestasi Siswa',
            'columns' => ['No', 'NIS', 'Nama Siswa', 'Kelas', 'Jenis Prestasi', 'Poin', 'Tanggal', 'Status'],
            'data' => $data,
            'filter_kelas' => $filter_kelas,
            'filter_tanggal' => $filter_tanggal
        ])->setPaper('a4', 'landscape');
        return $pdf->stream('laporan-prestasi.pdf');
    }

    public function siswaPdf(Request $request)
    {
        $query = Siswa::with('kelas');
        
        $filter_kelas = null;
        if ($request->kelas_id) {
            $kelas = \App\Models\Kelas::find($request->kelas_id);
            $filter_kelas = $kelas ? $kelas->nama_kelas : null;
            $query->where('kelas_id', $request->kelas_id);
        }
        
        $siswas = $query->get();
        
        $data = [];
        foreach ($siswas as $index => $s) {
            $total_poin = $s->pelanggarans()->whereHas('sanksi', function($q) {
                $q->where('status', 'verified');
            })->sum('poin');
            
            $data[] = [
                $index + 1,
                $s->nis,
                $s->nama_siswa,
                $s->kelas->nama_kelas ?? '-',
                $s->jenis_kelamin,
                $total_poin
            ];
        }
        
        $pdf = Pdf::loadView('laporan.laporan_pdf', [
            'judul' => 'Data Siswa',
            'columns' => ['No', 'NIS', 'Nama Siswa', 'Kelas', 'Jenis Kelamin', 'Total Poin Pelanggaran'],
            'data' => $data,
            'filter_kelas' => $filter_kelas
        ])->setPaper('a4', 'landscape');
        return $pdf->stream('laporan-siswa.pdf');
    }
}
