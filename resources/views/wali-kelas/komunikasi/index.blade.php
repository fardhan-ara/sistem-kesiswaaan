@extends('layouts.app')

@section('title', 'Komunikasi dengan Orang Tua')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-4">
        <h2><i class="fas fa-comments"></i> Komunikasi dengan Orang Tua - {{ $kelas->nama_kelas }}</h2>
        <div>
            <a href="{{ route('komunikasi.create') }}" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Kirim Pesan
            </a>
            <a href="{{ route('wali-kelas.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="50"></th>
                            <th>Jenis</th>
                            <th>Subjek</th>
                            <th>Siswa</th>
                            <th>Kepada/Dari</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($komunikasis as $k)
                        <tr class="{{ $k->status === 'terkirim' && $k->penerima_id == Auth::id() ? 'table-warning' : '' }}">
                            <td>
                                @if($k->status === 'terkirim' && $k->penerima_id == Auth::id())
                                <i class="fas fa-envelope text-warning"></i>
                                @else
                                <i class="fas fa-envelope-open text-muted"></i>
                                @endif
                            </td>
                            <td>
                                @if($k->jenis === 'pesan')
                                <span class="badge badge-primary">Pesan</span>
                                @elseif($k->jenis === 'laporan_pembinaan')
                                <span class="badge badge-info">Laporan Pembinaan</span>
                                @else
                                <span class="badge badge-success">Konsultasi</span>
                                @endif
                            </td>
                            <td><strong>{{ $k->subjek }}</strong></td>
                            <td>{{ $k->siswa->nama_siswa }}</td>
                            <td>
                                @if($k->penerima_id == Auth::id())
                                Dari: {{ $k->pengirim->nama }}
                                @else
                                Kepada: {{ $k->penerima->nama }}
                                @endif
                            </td>
                            <td>{{ $k->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($k->status === 'terkirim')
                                <span class="badge badge-secondary">Terkirim</span>
                                @elseif($k->status === 'dibaca')
                                <span class="badge badge-info">Dibaca</span>
                                @else
                                <span class="badge badge-success">Dibalas</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('komunikasi.show', $k->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada komunikasi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $komunikasis->links() }}
        </div>
    </div>
</div>
@endsection
