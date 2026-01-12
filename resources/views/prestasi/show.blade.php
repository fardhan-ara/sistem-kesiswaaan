@extends('layouts.app')

@section('title', 'Detail Prestasi')
@section('page-title', 'Detail Prestasi')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-trophy mr-1"></i> Detail Prestasi</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Siswa</th>
                        <td>{{ $prestasi->siswa->nama_siswa ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>NIS</th>
                        <td>{{ $prestasi->siswa->nis ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Kelas</th>
                        <td>{{ $prestasi->siswa->kelas->nama_kelas ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Prestasi</th>
                        <td>{{ $prestasi->jenisPrestasi->nama_prestasi ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Tingkat</th>
                        <td>{{ $prestasi->jenisPrestasi->tingkat ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Poin</th>
                        <td><span class="badge badge-success">{{ $prestasi->poin }}</span></td>
                    </tr>
                    <tr>
                        <th>Guru Pembimbing</th>
                        <td>{{ $prestasi->guru->nama_guru ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Prestasi</th>
                        <td>{{ $prestasi->tanggal_prestasi ? \Carbon\Carbon::parse($prestasi->tanggal_prestasi)->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td>{{ $prestasi->keterangan ?? '-' }}</td>
                    </tr>
                    @if($prestasi->bukti_prestasi)
                    <tr>
                        <th>Bukti Prestasi</th>
                        <td>
                            <a href="{{ asset('storage/' . $prestasi->bukti_prestasi) }}" target="_blank" class="btn btn-sm btn-info">
                                <i class="fas fa-file"></i> Lihat Bukti
                            </a>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th>Status Verifikasi</th>
                        <td>
                            @switch($prestasi->status_verifikasi)
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
                    @if($prestasi->status_verifikasi == 'rejected' && $prestasi->alasan_penolakan)
                    <tr>
                        <th>Alasan Penolakan</th>
                        <td class="text-danger">{{ $prestasi->alasan_penolakan }}</td>
                    </tr>
                    @endif
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('prestasi.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                @if(in_array(auth()->user()->role, ['admin', 'kesiswaan']) && $prestasi->status_verifikasi == 'pending')
                    <button type="button" class="btn btn-success" onclick="verifikasiPrestasi({{ $prestasi->id }})">
                        <i class="fas fa-check"></i> Verifikasi
                    </button>
                    <button type="button" class="btn btn-danger" onclick="tolakPrestasi({{ $prestasi->id }})">
                        <i class="fas fa-times"></i> Tolak
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<form id="verify-form" action="{{ route('prestasi.verify', $prestasi) }}" method="POST" style="display: none;">
    @csrf
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function verifikasiPrestasi(id) {
    Swal.fire({
        title: 'Verifikasi Prestasi',
        text: 'Apakah Anda yakin ingin memverifikasi prestasi ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Verifikasi!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('verify-form').submit();
        }
    });
}

function tolakPrestasi(id) {
    Swal.fire({
        title: 'Tolak Prestasi',
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
            form.action = '/prestasi/' + id + '/reject';
            
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
        timer: 3000,
        showConfirmButton: false
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
