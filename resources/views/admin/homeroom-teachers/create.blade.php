@extends('layouts.app')

@section('title', 'Assign Wali Kelas')
@section('page-title', 'Assign Wali Kelas')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Assignment Wali Kelas</h3>
            </div>
            <form action="{{ route('admin.homeroom-teachers.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="user_id">Pilih Guru</label>
                        <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('user_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->nama }} ({{ $teacher->email }})
                            </option>
                            @endforeach
                        </select>
                        @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="kelas_id">Pilih Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('kelas_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->nama_kelas }}
                            </option>
                            @endforeach
                        </select>
                        @error('kelas_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tahun_ajaran_id">Tahun Ajaran</label>
                        <select name="tahun_ajaran_id" id="tahun_ajaran_id" class="form-control @error('tahun_ajaran_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Tahun Ajaran --</option>
                            @foreach($tahunAjarans as $tahun)
                            <option value="{{ $tahun->id }}" {{ old('tahun_ajaran_id') == $tahun->id ? 'selected' : '' }}>
                                {{ $tahun->nama_tahun_ajaran }}
                            </option>
                            @endforeach
                        </select>
                        @error('tahun_ajaran_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Assign Wali Kelas
                    </button>
                    <a href="{{ route('admin.homeroom-teachers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Informasi</h3>
            </div>
            <div class="card-body">
                <p><strong>Catatan:</strong></p>
                <ul>
                    <li>Hanya guru yang bisa di-assign sebagai wali kelas</li>
                    <li>Satu kelas hanya bisa punya satu wali kelas per tahun ajaran</li>
                    <li>Guru bisa jadi wali kelas untuk beberapa kelas di tahun ajaran berbeda</li>
                    <li>Setelah di-assign, guru akan mendapat akses menu wali kelas</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection