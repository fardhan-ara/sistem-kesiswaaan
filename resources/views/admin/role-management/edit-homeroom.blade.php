@extends('layouts.app')

@section('title', 'Edit Wali Kelas')
@section('page-title', 'Edit Wali Kelas')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Edit Assignment Wali Kelas</h3>
            </div>
            <form action="{{ route('admin.update-homeroom', $assignment->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label>Guru</label>
                        <select name="user_id" class="form-control" required>
                            @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ $assignment->user_id == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kelas</label>
                        <select name="kelas_id" class="form-control" required>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $assignment->kelas_id == $class->id ? 'selected' : '' }}>
                                {{ $class->nama_kelas }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tahun Ajaran</label>
                        <select name="tahun_ajaran_id" class="form-control" required>
                            @foreach($tahunAjarans as $tahun)
                            <option value="{{ $tahun->id }}" {{ $assignment->tahun_ajaran_id == $tahun->id ? 'selected' : '' }}>
                                {{ $tahun->tahun_ajaran }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="{{ route('admin.role-management') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection