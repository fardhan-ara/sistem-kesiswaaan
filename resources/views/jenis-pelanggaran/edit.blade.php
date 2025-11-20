@extends('layouts.app')

@section('title', 'Edit Jenis Pelanggaran')
@section('page-title', 'Edit Jenis Pelanggaran')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

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
                <select name="kelompok" id="kelompok" class="form-control select2 @error('kelompok') is-invalid @enderror" required>
                    <option value="">Pilih Kategori Pelanggaran</option>
                    <option value="A. KETERTIBAN" {{ old('kelompok', $jenisPelanggaran->kelompok) === 'A. KETERTIBAN' ? 'selected' : '' }}>A. KETERTIBAN</option>
                    <option value="B. PAKAIAN" {{ old('kelompok', $jenisPelanggaran->kelompok) === 'B. PAKAIAN' ? 'selected' : '' }}>B. PAKAIAN</option>
                    <option value="C. RAMBUT" {{ old('kelompok', $jenisPelanggaran->kelompok) === 'C. RAMBUT' ? 'selected' : '' }}>C. RAMBUT</option>
                    <option value="D. BUKU, MAJALAH ATAU KASET TERLARANG" {{ old('kelompok', $jenisPelanggaran->kelompok) === 'D. BUKU, MAJALAH ATAU KASET TERLARANG' ? 'selected' : '' }}>D. BUKU, MAJALAH ATAU KASET TERLARANG</option>
                    <option value="E. BENKATA" {{ old('kelompok', $jenisPelanggaran->kelompok) === 'E. BENKATA' ? 'selected' : '' }}>E. BENKATA</option>
                    <option value="F. OBAT/MINUMAN TERLARANG" {{ old('kelompok', $jenisPelanggaran->kelompok) === 'F. OBAT/MINUMAN TERLARANG' ? 'selected' : '' }}>F. OBAT/MINUMAN TERLARANG</option>
                    <option value="G. PERKELAHIAN" {{ old('kelompok', $jenisPelanggaran->kelompok) === 'G. PERKELAHIAN' ? 'selected' : '' }}>G. PERKELAHIAN</option>
                    <option value="H. PELANGGARAN TERHADAP KEPALA SEKOLAH, GURU DAN KARYAWAN" {{ old('kelompok', $jenisPelanggaran->kelompok) === 'H. PELANGGARAN TERHADAP KEPALA SEKOLAH, GURU DAN KARYAWAN' ? 'selected' : '' }}>H. PELANGGARAN TERHADAP KEPALA SEKOLAH, GURU DAN KARYAWAN</option>
                    <option value="I. KERAJINAN" {{ old('kelompok', $jenisPelanggaran->kelompok) === 'I. KERAJINAN' ? 'selected' : '' }}>I. KERAJINAN</option>
                    <option value="J. KEHADIRAN" {{ old('kelompok', $jenisPelanggaran->kelompok) === 'J. KEHADIRAN' ? 'selected' : '' }}>J. KEHADIRAN</option>
                </select>
                @error('kelompok')<span class="invalid-feedback">{{ $message }}</span>@enderror
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
                <label>Kategori Tingkat</label>
                <input type="text" name="kategori" id="kategori" class="form-control" value="{{ old('kategori', $jenisPelanggaran->kategori) }}" readonly>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    function updateKategori() {
        const poin = parseInt($('#poin').val());
        if (poin) {
            let kategori = '';
            if (poin >= 1 && poin <= 15) kategori = 'ringan';
            else if (poin >= 16 && poin <= 30) kategori = 'sedang';
            else if (poin >= 31 && poin <= 75) kategori = 'berat';
            else if (poin >= 76) kategori = 'sangat_berat';
            $('#kategori').val(kategori);
        }
    }

    $('#poin').on('input', updateKategori);
    updateKategori();
});
</script>
@endpush
