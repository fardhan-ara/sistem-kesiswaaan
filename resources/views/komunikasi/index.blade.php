@extends('layouts.app')

@section('title', 'Komunikasi dengan Orang Tua')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-4">
        <h2><i class="fas fa-comments"></i> Komunikasi & Pembinaan</h2>
        <div>
            @if(in_array(Auth::user()->role, ['kesiswaan', 'wali_kelas', 'bk', 'ortu']))
            <a href="{{ route('komunikasi.create') }}" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Kirim Pesan
            </a>
            @endif
            
            @if(in_array(Auth::user()->role, ['kesiswaan', 'wali_kelas', 'bk']))
            <a href="{{ route('komunikasi.panggilan') }}" class="btn btn-warning">
                <i class="fas fa-bell"></i> Panggilan Ortu
            </a>
            @endif
        </div>
    </div>

    @if(Auth::user()->role === 'ortu')
    {{-- Tampilan untuk Orang Tua --}}
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#semua" role="tab">Semua Pesan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#panggilan" role="tab">
                        Panggilan Ortu
                        @php
                            $biodata = \App\Models\BiodataOrtu::where('user_id', Auth::id())->first();
                            $panggilanMenunggu = $biodata ? \App\Models\PanggilanOrtu::where('siswa_id', $biodata->siswa_id)
                                ->where('status', 'menunggu')->count() : 0;
                        @endphp
                        @if($panggilanMenunggu > 0)
                        <span class="badge badge-danger">{{ $panggilanMenunggu }}</span>
                        @endif
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                {{-- Tab Semua Pesan --}}
                <div class="tab-pane fade show active" id="semua" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="50"></th>
                                    <th>Jenis</th>
                                    <th>Subjek</th>
                                    <th>Kepada/Pengirim</th>
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
                                        <form action="{{ route('komunikasi.destroy', $k->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pesan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada pesan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $komunikasis->links() }}
                </div>

                {{-- Tab Panggilan Ortu --}}
                <div class="tab-pane fade" id="panggilan" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Judul</th>
                                    <th>Siswa</th>
                                    <th>Tempat</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $panggilan = $biodata ? \App\Models\PanggilanOrtu::where('siswa_id', $biodata->siswa_id)
                                        ->with(['siswa', 'pembuatPanggilan'])
                                        ->orderBy('tanggal_panggilan', 'desc')
                                        ->get() : collect();
                                @endphp
                                @forelse($panggilan as $p)
                                <tr class="{{ $p->status === 'menunggu' ? 'table-danger' : '' }}">
                                    <td>{{ \Carbon\Carbon::parse($p->tanggal_panggilan)->format('d/m/Y H:i') }}</td>
                                    <td><strong>{{ $p->judul }}</strong></td>
                                    <td>{{ $p->siswa->nama_siswa }}</td>
                                    <td>{{ $p->tempat }}</td>
                                    <td>
                                        @if($p->status === 'menunggu')
                                        <span class="badge badge-danger">Menunggu Konfirmasi</span>
                                        @elseif($p->status === 'dikonfirmasi')
                                        <span class="badge badge-warning">Dikonfirmasi</span>
                                        @else
                                        <span class="badge badge-success">Selesai</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($p->status === 'menunggu')
                                        <button class="btn btn-sm btn-success" onclick="if(confirm('Konfirmasi kehadiran?')) document.getElementById('konfirmasi-{{ $p->id }}').submit();">
                                            <i class="fas fa-check"></i> Konfirmasi
                                        </button>
                                        <form id="konfirmasi-{{ $p->id }}" action="{{ route('komunikasi.konfirmasi-panggilan', $p->id) }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                        @else
                                        <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailPanggilan{{ $p->id }}">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                
                                {{-- Modal Detail --}}
                                <div class="modal fade" id="detailPanggilan{{ $p->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detail Panggilan</h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Judul:</strong> {{ $p->judul }}</p>
                                                <p><strong>Siswa:</strong> {{ $p->siswa->nama_siswa }}</p>
                                                <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($p->tanggal_panggilan)->format('d/m/Y H:i') }}</p>
                                                <p><strong>Tempat:</strong> {{ $p->tempat }}</p>
                                                <p><strong>Keterangan:</strong><br>{{ $p->keterangan }}</p>
                                                @if($p->catatan_hasil)
                                                <hr>
                                                <p><strong>Catatan Hasil:</strong><br>{{ $p->catatan_hasil }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Tidak ada panggilan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @else
    {{-- Tampilan untuk Kesiswaan/Wali Kelas/BK --}}
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
                            <th>Kepada</th>
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
                            <td>{{ $k->penerima->nama }}</td>
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
                                <form action="{{ route('komunikasi.destroy', $k->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pesan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada pesan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $komunikasis->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
