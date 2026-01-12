@extends('layouts.app')

@section('title', 'Siswa Kelas')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-4">
        <h2><i class="fas fa-users"></i> Siswa Kelas {{ $kelas->nama_kelas }}</h2>
        <a href="{{ route('wali-kelas.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Jenis Kelamin</th>
                            <th>Tahun Ajaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswas as $index => $siswa)
                        <tr>
                            <td>{{ $siswas->firstItem() + $index }}</td>
                            <td>{{ $siswa->nis }}</td>
                            <td><strong>{{ $siswa->nama_siswa }}</strong></td>
                            <td>{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td>{{ $siswa->tahunAjaran->nama ?? '-' }}</td>
                            <td>
                                <a href="{{ route('wali-kelas.siswa.show', $siswa->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada siswa</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $siswas->links() }}
        </div>
    </div>
</div>
@endsection
