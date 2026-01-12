@extends('layouts.app')
@section('title', 'Tambah Tahun Ajaran')
@section('page-title', 'Tambah Tahun Ajaran')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-calendar-plus mr-1"></i> Form Tambah Tahun Ajaran</h3>
    </div>
    <form action="{{ route('tahun-ajaran.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tahun Mulai <span class="text-danger">*</span></label>
                        <input type="number" name="tahun_mulai" class="form-control @error('tahun_mulai') is-invalid @enderror" 
                            value="{{ old('tahun_mulai', date('Y')) }}" min="2020" max="2050" required>
                        @error('tahun_mulai')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tahun Selesai <span class="text-danger">*</span></label>
                        <input type="number" name="tahun_selesai" class="form-control @error('tahun_selesai') is-invalid @enderror" 
                            value="{{ old('tahun_selesai', date('Y')+1) }}" min="2020" max="2050" required>
                        @error('tahun_selesai')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">Format output: Tahun Mulai/Tahun Selesai (contoh: 2024/2025)</small>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Semester <span class="text-danger">*</span></label>
                        <select name="semester" class="form-control @error('semester') is-invalid @enderror" required>
                            <option value="">-- Pilih Semester --</option>
                            <option value="ganjil" {{ old('semester') == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="genap" {{ old('semester') == 'genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                        @error('semester')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select name="status_aktif" class="form-control @error('status_aktif') is-invalid @enderror" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="aktif" {{ old('status_aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status_aktif') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('status_aktif')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">Hanya 1 tahun ajaran yang bisa aktif</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="{{ route('tahun-ajaran.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>
@endsection