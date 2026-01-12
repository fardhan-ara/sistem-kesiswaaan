@extends('layouts.app')

@section('title', 'Edit Jenis Pelanggaran')
@section('page-title', 'Edit Jenis Pelanggaran')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Form Edit Jenis Pelanggaran</h3>
    </div>
    <form action="{{ route('jenis-pelanggaran.update', $jenisPelanggaran) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label>Kategori Pelanggaran <span class="text-danger">*</span></label>
                <select name="kategori" class="form-control @error('kategori') is-invalid @enderror" required>
                    <option value="">Pilih Kategori</option>
                    <option value="ketertiban" {{ old('kategori', $jenisPelanggaran->kategori) == 'ketertiban' ? 'selected' : '' }}>Ketertiban</option>
                    <option value="kehadiran" {{ old('kategori', $jenisPelanggaran->kategori) == 'kehadiran' ? 'selected' : '' }}>Kehadiran</option>
                    <option value="pakaian" {{ old('kategori', $jenisPelanggaran->kategori) == 'pakaian' ? 'selected' : '' }}>Pakaian & Penampilan</option>
                    <option value="sikap" {{ old('kategori', $jenisPelanggaran->kategori) == 'sikap' ? 'selected' : '' }}>Sikap & Etika</option>
                    <option value="akademik" {{ old('kategori', $jenisPelanggaran->kategori) == 'akademik' ? 'selected' : '' }}>Akademik</option>
                    <option value="fasilitas" {{ old('kategori', $jenisPelanggaran->kategori) == 'fasilitas' ? 'selected' : '' }}>Fasilitas</option>
                    <option value="kriminal" {{ old('kategori', $jenisPelanggaran->kategori) == 'kriminal' ? 'selected' : '' }}>Kriminal</option>
                </select>
                @error('kategori')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            
            <div class="form-group">
                <label>Jenis Pelanggaran <span class="text-danger">*</span></label>
                <input type="text" name="nama_pelanggaran" id="nama_pelanggaran" class="form-control @error('nama_pelanggaran') is-invalid @enderror" value="{{ old('nama_pelanggaran', $jenisPelanggaran->nama_pelanggaran) }}" required>
                @error('nama_pelanggaran')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Bobot Poin <span class="text-danger">*</span></label>
                <input type="number" name="poin" id="poin" class="form-control @error('poin') is-invalid @enderror" value="{{ old('poin', $jenisPelanggaran->poin) }}" min="1" max="100" required>
                @error('poin')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Sanksi Rekomendasi</label>
                <textarea name="sanksi_rekomendasi" class="form-control @error('sanksi_rekomendasi') is-invalid @enderror" rows="3">{{ old('sanksi_rekomendasi', $jenisPelanggaran->sanksi_rekomendasi) }}</textarea>
                @error('sanksi_rekomendasi')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
            <a href="{{ route('jenis-pelanggaran.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
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
@endpush
