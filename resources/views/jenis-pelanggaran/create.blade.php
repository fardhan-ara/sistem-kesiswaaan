@extends('layouts.app')

@section('title', 'Tambah Jenis Pelanggaran')
@section('page-title', 'Tambah Jenis Pelanggaran')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-plus mr-1"></i> Form Tambah Jenis Pelanggaran</h3>
    </div>
    <form action="{{ route('jenis-pelanggaran.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>Kategori Pelanggaran <span class="text-danger">*</span></label>
                <select name="kategori" class="form-control @error('kategori') is-invalid @enderror" required>
                    <option value="">Pilih Kategori</option>
                    <option value="ketertiban" {{ old('kategori') == 'ketertiban' ? 'selected' : '' }}>Ketertiban</option>
                    <option value="kehadiran" {{ old('kategori') == 'kehadiran' ? 'selected' : '' }}>Kehadiran</option>
                    <option value="pakaian" {{ old('kategori') == 'pakaian' ? 'selected' : '' }}>Pakaian & Penampilan</option>
                    <option value="sikap" {{ old('kategori') == 'sikap' ? 'selected' : '' }}>Sikap & Etika</option>
                    <option value="akademik" {{ old('kategori') == 'akademik' ? 'selected' : '' }}>Akademik</option>
                    <option value="fasilitas" {{ old('kategori') == 'fasilitas' ? 'selected' : '' }}>Fasilitas</option>
                    <option value="kriminal" {{ old('kategori') == 'kriminal' ? 'selected' : '' }}>Kriminal</option>
                </select>
                @error('kategori')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            
            <div class="form-group">
                <label>Nama Pelanggaran <span class="text-danger">*</span></label>
                <input type="text" name="nama_pelanggaran" class="form-control @error('nama_pelanggaran') is-invalid @enderror" value="{{ old('nama_pelanggaran') }}" placeholder="Contoh: Terlambat masuk kelas" required>
                @error('nama_pelanggaran')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Bobot Poin <span class="text-danger">*</span></label>
                <input type="number" name="poin" class="form-control @error('poin') is-invalid @enderror" value="{{ old('poin') }}" min="1" max="100" placeholder="1-100" required>
                @error('poin')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Sanksi Rekomendasi</label>
                <textarea name="sanksi_rekomendasi" class="form-control @error('sanksi_rekomendasi') is-invalid @enderror" rows="3" placeholder="Contoh: Teguran lisan, Panggilan orang tua">{{ old('sanksi_rekomendasi') }}</textarea>
                @error('sanksi_rekomendasi')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
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
