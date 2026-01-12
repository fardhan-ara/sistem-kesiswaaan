@extends('layouts.app')

@section('title', 'Kelola Wali Kelas')
@section('page-title', 'Kelola Wali Kelas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Wali Kelas</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.homeroom-teachers.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Assign Wali Kelas
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Guru</th>
                                <th>Kelas</th>
                                <th>Tahun Ajaran</th>
                                <th>Assigned By</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assignments as $assignment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $assignment->user->nama }}</td>
                                <td>{{ $assignment->kelas->nama_kelas }}</td>
                                <td>{{ $assignment->tahunAjaran->nama_tahun_ajaran ?? '-' }}</td>
                                <td>{{ $assignment->assignedBy->nama }}</td>
                                <td>{{ $assignment->assigned_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <form action="{{ route('admin.homeroom-teachers.destroy', $assignment) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus assignment ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada wali kelas yang di-assign</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{ $assignments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection