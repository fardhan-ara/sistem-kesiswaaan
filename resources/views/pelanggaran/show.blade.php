@extends('layouts.app')

@section('title', 'Detail Pelanggaran')
@section('page-title', 'Detail Pelanggaran')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Detail Pelanggaran</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Siswa</th>
                        <td>{{ $pelanggaran->siswa->nama_siswa ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>NIS</th>
                        <td>{{ $pelanggaran->siswa->nis ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Kelas</th>
                        <td>{{ $pelanggaran->siswa->kelas->nama_kelas ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Pelanggaran</th>
                        <td>
                            @php
                                $pelanggaranList = [];
                                if ($pelanggaran->pelanggaran_list) {
                                    $pelanggaranList = json_decode($pelanggaran->pelanggaran_list, true);
                                }
                            @endphp
                            
                            <!-- Tampilkan pelanggaran utama -->
                            @if($pelanggaran->jenisPelanggaran)
                                <div class="mb-1">
                                    <span class="badge badge-primary">• {{ $pelanggaran->jenisPelanggaran->nama_pelanggaran }} ({{ $pelanggaran->jenisPelanggaran->poin }} poin)</span>
                                </div>
                            @endif
                            
                            <!-- Tampilkan pelanggaran tambahan -->
                            @if(is_array($pelanggaranList) && count($pelanggaranList) > 0)
                                @foreach($pelanggaranList as $item)
                                    <div class="mb-1">
                                        <span class="badge badge-info">• {{ $item['nama'] }} ({{ $item['poin'] }} poin)</span>
                                    </div>
                                @endforeach
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>{{ $pelanggaran->jenisPelanggaran->kelompok ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Poin</th>
                        <td><span class="badge badge-danger">{{ $pelanggaran->poin }}</span></td>
                    </tr>
                    <tr>
                        <th>Guru Pencatat</th>
                        <td>{{ $pelanggaran->guru->nama_guru ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Pelanggaran</th>
                        <td>{{ $pelanggaran->tanggal_pelanggaran ? \Carbon\Carbon::parse($pelanggaran->tanggal_pelanggaran)->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td>{{ $pelanggaran->keterangan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status Verifikasi</th>
                        <td>
                            @switch($pelanggaran->status_verifikasi)
                                @case('terverifikasi')
                                @case('verified')
                                    <span class="badge badge-success">Terverifikasi</span>
                                    @break
                                @case('ditolak')
                                @case('rejected')
                                    <span class="badge badge-danger">Ditolak</span>
                                    @break
                                @default
                                    <span class="badge badge-warning">Menunggu</span>
                            @endswitch
                        </td>
                    </tr>
                    @if($pelanggaran->status_verifikasi == 'ditolak' && $pelanggaran->alasan_penolakan)
                    <tr>
                        <th>Alasan Penolakan</th>
                        <td class="text-danger">{{ $pelanggaran->alasan_penolakan }}</td>
                    </tr>
                    @endif
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('pelanggaran.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                @if(in_array(auth()->user()->role, ['admin', 'kesiswaan']) && $pelanggaran->status_verifikasi == 'menunggu')
                    <form action="{{ route('pelanggaran.verify', $pelanggaran) }}" method="POST" style="display:inline;" onsubmit="console.log('Form submitted'); return true;">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Verifikasi
                        </button>
                    </form>
                    <form action="{{ route('pelanggaran.reject', $pelanggaran) }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="text" name="alasan_penolakan" placeholder="Alasan" required style="width:200px;">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times"></i> Tolak
                        </button>
                    </form>
                    <div class="mt-2">
                        <small class="text-muted">Debug: Route = {{ route('pelanggaran.verify', $pelanggaran) }}</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-gavel mr-1"></i> Sanksi Terkait</h3>
            </div>
            <div class="card-body">
                @if($pelanggaran->sanksis && $pelanggaran->sanksis->count() > 0)
                    @foreach($pelanggaran->sanksis as $sanksi)
                    <div class="alert alert-warning">
                        <strong>{{ $sanksi->jenis_sanksi }}</strong><br>
                        <small>{{ $sanksi->deskripsi_sanksi }}</small><br>
                        <small class="text-muted">Status: {{ $sanksi->status_sanksi }}</small>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted">Tidak ada sanksi</p>
                @endif
            </div>
        </div>
    </div>
</div>

<form id="verify-form-{{ $pelanggaran->id }}" action="{{ route('pelanggaran.verify', $pelanggaran) }}" method="POST" style="display: none;">
    @csrf
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function verifikasiPelanggaran(id) {
    Swal.fire({
        title: 'Verifikasi Pelanggaran',
        text: 'Apakah Anda yakin ingin memverifikasi pelanggaran ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Verifikasi!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('verify-form-' + id).submit();
        }
    });
}

function tolakPelanggaran(id) {
    Swal.fire({
        title: 'Tolak Pelanggaran',
        input: 'textarea',
        inputLabel: 'Alasan Penolakan',
        inputPlaceholder: 'Masukkan alasan penolakan...',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Tolak',
        cancelButtonText: 'Batal',
        inputValidator: (value) => {
            if (!value) {
                return 'Alasan penolakan harus diisi!'
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/pelanggaran/' + id + '/reject';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            const alasanInput = document.createElement('input');
            alasanInput.type = 'hidden';
            alasanInput.name = 'alasan_penolakan';
            alasanInput.value = result.value;
            form.appendChild(alasanInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false
    }).then(() => {
        location.reload();
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif
</script>
@endsection
