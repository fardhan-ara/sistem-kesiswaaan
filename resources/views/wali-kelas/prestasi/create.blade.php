@extends('layouts.app')

@section('title', 'Input Prestasi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-4">
        <h2><i class="fas fa-medal"></i> Input Prestasi Siswa</h2>
        <a href="{{ route('wali-kelas.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('wali-kelas.prestasi.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Siswa <span class="text-danger">*</span></label>
                    <select name="siswa_id" class="form-control" required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($siswas as $siswa)
                        <option value="{{ $siswa->id }}">{{ $siswa->nama_siswa }} ({{ $siswa->nis }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis Prestasi <span class="text-danger">*</span></label>
                    <select name="jenis_prestasi_id" class="form-control" required>
                        <option value="">-- Pilih Jenis Prestasi --</option>
                        @foreach($jenisPrestasis as $jenis)
                        <option value="{{ $jenis->id }}">
                            {{ $jenis->nama_prestasi }} ({{ $jenis->poin_reward }} poin)
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Prestasi <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_prestasi" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="4"></textarea>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan Prestasi
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
