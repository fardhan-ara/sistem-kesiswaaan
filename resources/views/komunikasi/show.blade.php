@extends('layouts.app')

@section('title', 'Detail Pesan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-4">
        <h2><i class="fas fa-envelope-open"></i> Detail Pesan</h2>
        <a href="{{ route('komunikasi.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ $komunikasi->subjek }}</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Jenis:</strong>
                        @if($komunikasi->jenis === 'pesan')
                        <span class="badge bg-primary">Pesan</span>
                        @elseif($komunikasi->jenis === 'laporan_pembinaan')
                        <span class="badge bg-info">Laporan Pembinaan</span>
                        @else
                        <span class="badge bg-success">Konsultasi</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <strong>Dari:</strong> {{ $komunikasi->pengirim->nama }} ({{ ucfirst($komunikasi->pengirim->role) }})<br>
                        <strong>Kepada:</strong> {{ $komunikasi->penerima->nama }} ({{ ucfirst($komunikasi->penerima->role) }})<br>
                        <strong>Siswa:</strong> {{ $komunikasi->siswa->nama_siswa }} - {{ $komunikasi->siswa->kelas->nama_kelas ?? '-' }}<br>
                        <strong>Tanggal:</strong> {{ $komunikasi->created_at->format('d/m/Y H:i') }}
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6>Isi Pesan:</h6>
                        <p style="white-space: pre-wrap;">{{ $komunikasi->isi_pesan }}</p>
                    </div>

                    @if($komunikasi->lampiran)
                    <div class="mb-3">
                        <strong>Lampiran:</strong>
                        <a href="{{ asset('storage/' . $komunikasi->lampiran) }}" target="_blank" class="btn btn-sm btn-info">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Balasan -->
            @if($komunikasi->balasan->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-comments"></i> Balasan ({{ $komunikasi->balasan->count() }})</h5>
                </div>
                <div class="card-body">
                    @foreach($komunikasi->balasan as $balasan)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $balasan->pengirim->nama }}</strong>
                            <small class="text-muted">{{ $balasan->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <p class="mt-2" style="white-space: pre-wrap;">{{ $balasan->isi_balasan }}</p>
                        @if($balasan->lampiran)
                        <a href="{{ asset('storage/' . $balasan->lampiran) }}" target="_blank" class="btn btn-sm btn-info">
                            <i class="fas fa-download"></i> Lampiran
                        </a>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Form Balas -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-reply"></i> Balas Pesan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('komunikasi.reply', $komunikasi->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <textarea name="isi_balasan" class="form-control" rows="4" placeholder="Tulis balasan..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lampiran (Opsional)</label>
                            <input type="file" name="lampiran" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Kirim Balasan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informasi</h5>
                </div>
                <div class="card-body">
                    <p><strong>Status:</strong>
                        @if($komunikasi->status === 'terkirim')
                        <span class="badge bg-secondary">Terkirim</span>
                        @elseif($komunikasi->status === 'dibaca')
                        <span class="badge bg-info">Dibaca</span>
                        @else
                        <span class="badge bg-success">Dibalas</span>
                        @endif
                    </p>

                    @if($komunikasi->dibaca_at)
                    <p><strong>Dibaca:</strong><br>{{ $komunikasi->dibaca_at->format('d/m/Y H:i') }}</p>
                    @endif

                    <p><strong>Total Balasan:</strong> {{ $komunikasi->balasan->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
