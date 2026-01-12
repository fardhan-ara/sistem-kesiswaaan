@extends('layouts.app')

@section('title', 'Input Pelanggaran')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-4">
        <h2><i class="fas fa-ban"></i> Input Pelanggaran Siswa</h2>
        <a href="{{ route('wali-kelas.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('wali-kelas.pelanggaran.store') }}" method="POST">
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
                    <label class="form-label">Jenis Pelanggaran <span class="text-danger">*</span></label>
                    <select name="jenis_pelanggaran_id" class="form-control" required id="jenisPelanggaran">
                        <option value="">-- Pilih Jenis Pelanggaran --</option>
                        @foreach($jenisPelanggarans as $jenis)
                        <option value="{{ $jenis->id }}" data-poin="{{ $jenis->poin }}">
                            {{ $jenis->nama_pelanggaran }} ({{ $jenis->poin }} poin)
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Pelanggaran <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_pelanggaran" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="4"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Pelanggaran
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
