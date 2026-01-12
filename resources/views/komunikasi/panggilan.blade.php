@extends('layouts.app')

@section('title', 'Panggilan Orang Tua')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-4">
        <h2><i class="fas fa-bell"></i> Panggilan Orang Tua</h2>
        @if(in_array(Auth::user()->role, ['admin', 'kesiswaan', 'guru']))
        <a href="{{ route('komunikasi.create-panggilan') }}" class="btn btn-warning">
            <i class="fas fa-plus"></i> Buat Panggilan
        </a>
        @endif
    </div>

    <div class="row">
        @forelse($panggilan as $p)
        <div class="col-md-6 mb-3">
            <div class="card {{ $p->status === 'menunggu_konfirmasi' ? 'border-warning' : '' }}">
                <div class="card-header {{ $p->status === 'menunggu_konfirmasi' ? 'bg-warning' : 'bg-info' }} text-white">
                    <h5 class="mb-0">{{ $p->judul }}</h5>
                </div>
                <div class="card-body">
                    <p><strong>Siswa:</strong> {{ $p->siswa->nama_siswa }} - {{ $p->siswa->kelas->nama_kelas ?? '-' }}</p>
                    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($p->tanggal_panggilan)->format('d/m/Y H:i') }}</p>
                    <p><strong>Tempat:</strong> {{ $p->tempat }}</p>
                    <p><strong>Keterangan:</strong><br>{{ $p->keterangan }}</p>

                    @if($p->pelanggaran)
                    <div class="alert alert-danger">
                        <strong>Terkait Pelanggaran:</strong><br>
                        {{ $p->pelanggaran->jenisPelanggaran->nama_pelanggaran ?? '-' }}
                    </div>
                    @endif

                    <p><strong>Status:</strong>
                        @if($p->status === 'menunggu_konfirmasi')
                        <span class="badge bg-warning">Menunggu Konfirmasi</span>
                        @elseif($p->status === 'dikonfirmasi')
                        <span class="badge bg-success">Dikonfirmasi</span>
                        @elseif($p->status === 'selesai')
                        <span class="badge bg-info">Selesai</span>
                        @else
                        <span class="badge bg-secondary">Dibatalkan</span>
                        @endif
                    </p>

                    @if($p->catatan_hasil)
                    <div class="alert alert-success">
                        <strong>Catatan Hasil:</strong><br>
                        {{ $p->catatan_hasil }}
                    </div>
                    @endif

                    <div class="mt-3">
                        @if(Auth::user()->role === 'ortu' && $p->status === 'menunggu_konfirmasi')
                        <form action="{{ route('komunikasi.konfirmasi-panggilan', $p->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success" onclick="return confirm('Konfirmasi kehadiran?')">
                                <i class="fas fa-check"></i> Konfirmasi Kehadiran
                            </button>
                        </form>
                        @endif

                        @if(in_array(Auth::user()->role, ['admin', 'kesiswaan', 'guru']) && $p->status === 'dikonfirmasi')
                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#selesaiModal{{ $p->id }}">
                            <i class="fas fa-check-circle"></i> Selesaikan
                        </button>

                        <!-- Modal Selesaikan -->
                        <div class="modal fade" id="selesaiModal{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('komunikasi.selesaikan-panggilan', $p->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Selesaikan Panggilan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label class="form-label">Catatan Hasil Pertemuan</label>
                                            <textarea name="catatan_hasil" class="form-control" rows="4" required></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-info">Selesaikan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <small class="text-muted">Dibuat oleh: {{ $p->pembuatPanggilan->nama }}</small>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">Belum ada panggilan orang tua</div>
        </div>
        @endforelse
    </div>
</div>
@endsection
