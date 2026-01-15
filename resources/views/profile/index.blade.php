@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    @if($user->foto)
                        <img class="profile-user-img img-fluid img-circle" src="{{ asset('storage/' . $user->foto) }}" alt="Foto Profil" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->nama) }}&background=667eea&color=fff&size=128'">
                    @else
                        <img class="profile-user-img img-fluid img-circle" src="https://ui-avatars.com/api/?name={{ urlencode($user->nama) }}&background=667eea&color=fff&size=128" alt="Foto Profil">
                    @endif
                </div>

                <h3 class="profile-username text-center">{{ $user->nama }}</h3>

                <p class="text-muted text-center">
                    @switch($user->role)
                        @case('siswa') <span class="badge badge-info">Siswa</span> @break
                        @case('guru') <span class="badge badge-success">Guru</span> @break
                        @case('wali_kelas') <span class="badge badge-primary">Wali Kelas</span> @break
                        @case('bk') <span class="badge badge-warning">BK</span> @break
                        @case('ortu') <span class="badge badge-secondary">Orang Tua</span> @break
                        @case('kesiswaan') <span class="badge badge-info">Kesiswaan</span> @break
                        @case('kepala_sekolah') <span class="badge badge-danger">z Sekolah</span> @break
                    @endswitch
                </p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Email</b> <a class="float-right">{{ $user->email }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>No. Telepon</b> <a class="float-right">{{ $user->no_telp ?? '-' }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Status</b> 
                        <span class="float-right">
                            @if($user->status === 'approved')
                                <span class="badge badge-success">Aktif</span>
                            @elseif($user->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @else
                                <span class="badge badge-danger">Ditolak</span>
                            @endif
                        </span>
                    </li>
                </ul>

                <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-block"><i class="fas fa-edit"></i> Edit Profil</a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        @if($profileData['type'] === 'siswa' && $profileData['data'])
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user-graduate"></i> Data Siswa</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">NIS</th>
                            <td>{{ $profileData['data']->nis }}</td>
                        </tr>
                        <tr>
                            <th>Nama Lengkap</th>
                            <td>{{ $profileData['data']->nama_siswa }}</td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <td>{{ $profileData['data']->kelas->nama_kelas ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tahun Ajaran</th>
                            <td>{{ $profileData['data']->tahunAjaran->tahun_ajaran ?? '-' }} ({{ $profileData['data']->tahunAjaran->semester ?? '-' }})</td>
                        </tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td>{{ $profileData['data']->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar"></i> Statistik</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Pelanggaran</span>
                                    <span class="info-box-number">{{ $profileData['stats']['total_pelanggaran'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-trophy"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Prestasi</span>
                                    <span class="info-box-number">{{ $profileData['stats']['total_prestasi'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-danger">
                                <span class="info-box-icon"><i class="fas fa-star"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Poin</span>
                                    <span class="info-box-number">{{ $profileData['stats']['total_poin'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @elseif($profileData['type'] === 'guru' && $profileData['data'])
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chalkboard-teacher"></i> Data Guru</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">NIP</th>
                            <td>{{ $profileData['data']->nip }}</td>
                        </tr>
                        <tr>
                            <th>Nama Lengkap</th>
                            <td>{{ $profileData['data']->nama_guru }}</td>
                        </tr>
                        <tr>
                            <th>Bidang Studi</th>
                            <td>{{ $profileData['data']->bidang_studi ?? '-' }}</td>
                        </tr>
                        @if($profileData['kelas_wali'])
                        <tr>
                            <th>Wali Kelas</th>
                            <td><span class="badge badge-primary">{{ $profileData['kelas_wali']->nama_kelas }}</span></td>
                        </tr>
                        @endif
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($profileData['data']->status === 'aktif')
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Tidak Aktif</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar"></i> Statistik</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-clipboard-list"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pelanggaran Dicatat</span>
                                    <span class="info-box-number">{{ $profileData['stats']['total_pelanggaran'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-award"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Prestasi Dicatat</span>
                                    <span class="info-box-number">{{ $profileData['stats']['total_prestasi'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @elseif($profileData['type'] === 'ortu' && $profileData['data'])
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-users"></i> Data Orang Tua</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Nama Ayah</th>
                            <td>{{ $profileData['data']->nama_ayah ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Telp Ayah</th>
                            <td>{{ $profileData['data']->telp_ayah ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Nama Ibu</th>
                            <td>{{ $profileData['data']->nama_ibu ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Telp Ibu</th>
                            <td>{{ $profileData['data']->telp_ibu ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $profileData['data']->alamat ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status Approval</th>
                            <td>
                                @if($profileData['data']->status_approval === 'approved')
                                    <span class="badge badge-success">Disetujui</span>
                                @elseif($profileData['data']->status_approval === 'pending')
                                    <span class="badge badge-warning">Menunggu</span>
                                @else
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($profileData['data']->status_approval === 'approved' && $profileData['data']->siswa)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-child"></i> Data Anak</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">NIS</th>
                            <td>{{ $profileData['data']->siswa->nis }}</td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td>{{ $profileData['data']->siswa->nama_siswa }}</td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <td>{{ $profileData['data']->siswa->kelas->nama_kelas ?? '-' }}</td>
                        </tr>
                    </table>

                    @if($profileData['stats'])
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-ban"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pelanggaran</span>
                                    <span class="info-box-number">{{ $profileData['stats']['total_pelanggaran'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-trophy"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Prestasi</span>
                                    <span class="info-box-number">{{ $profileData['stats']['total_prestasi'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-danger">
                                <span class="info-box-icon"><i class="fas fa-star"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Poin</span>
                                    <span class="info-box-number">{{ $profileData['stats']['total_poin'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

        @elseif($profileData['type'] === 'staff')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user-tie"></i> Informasi Akun</h3>
                </div>
                <div class="card-body">
                    <p>Anda login sebagai <strong>{{ ucfirst($user->role) }}</strong></p>
                    <p>Akun Anda aktif dan dapat mengakses sistem sesuai dengan hak akses yang diberikan.</p>
                </div>
            </div>

        @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> {{ $profileData['message'] ?? 'Data profil tidak ditemukan' }}
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif

@if(session('info'))
    Swal.fire({
        icon: 'info',
        title: 'Informasi',
        text: '{{ session('info') }}',
        timer: 3000
    });
@endif
</script>
@endsection
