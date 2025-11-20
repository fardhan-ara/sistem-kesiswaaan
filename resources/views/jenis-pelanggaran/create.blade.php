@extends('layouts.app')

@section('title', 'Tambah Jenis Pelanggaran')
@section('page-title', 'Tambah Jenis Pelanggaran')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

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
                <select name="kelompok" id="kelompok" class="form-control select2 @error('kelompok') is-invalid @enderror" required>
                    <option value="">Pilih Kategori Pelanggaran</option>
                    <option value="A. KETERTIBAN" {{ old('kelompok') === 'A. KETERTIBAN' ? 'selected' : '' }}>A. KETERTIBAN</option>
                    <option value="B. PAKAIAN" {{ old('kelompok') === 'B. PAKAIAN' ? 'selected' : '' }}>B. PAKAIAN</option>
                    <option value="C. RAMBUT" {{ old('kelompok') === 'C. RAMBUT' ? 'selected' : '' }}>C. RAMBUT</option>
                    <option value="D. BUKU, MAJALAH ATAU KASET TERLARANG" {{ old('kelompok') === 'D. BUKU, MAJALAH ATAU KASET TERLARANG' ? 'selected' : '' }}>D. BUKU, MAJALAH ATAU KASET TERLARANG</option>
                    <option value="E. BENKATA" {{ old('kelompok') === 'E. BENKATA' ? 'selected' : '' }}>E. BENKATA</option>
                    <option value="F. OBAT/MINUMAN TERLARANG" {{ old('kelompok') === 'F. OBAT/MINUMAN TERLARANG' ? 'selected' : '' }}>F. OBAT/MINUMAN TERLARANG</option>
                    <option value="G. PERKELAHIAN" {{ old('kelompok') === 'G. PERKELAHIAN' ? 'selected' : '' }}>G. PERKELAHIAN</option>
                    <option value="H. PELANGGARAN TERHADAP KEPALA SEKOLAH, GURU DAN KARYAWAN" {{ old('kelompok') === 'H. PELANGGARAN TERHADAP KEPALA SEKOLAH, GURU DAN KARYAWAN' ? 'selected' : '' }}>H. PELANGGARAN TERHADAP KEPALA SEKOLAH, GURU DAN KARYAWAN</option>
                    <option value="I. KERAJINAN" {{ old('kelompok') === 'I. KERAJINAN' ? 'selected' : '' }}>I. KERAJINAN</option>
                    <option value="J. KEHADIRAN" {{ old('kelompok') === 'J. KEHADIRAN' ? 'selected' : '' }}>J. KEHADIRAN</option>
                </select>
                @error('kelompok')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Jenis Pelanggaran <span class="text-danger">*</span></label>
                <select name="nama_pelanggaran" id="nama_pelanggaran" class="form-control select2 @error('nama_pelanggaran') is-invalid @enderror" required disabled>
                    <option value="">Pilih Kategori Pelanggaran Terlebih Dahulu</option>
                </select>
                @error('nama_pelanggaran')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Bobot Poin <span class="text-danger">*</span></label>
                <input type="number" name="poin" id="poin" class="form-control @error('poin') is-invalid @enderror" value="{{ old('poin') }}" readonly required>
                @error('poin')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Kategori Tingkat</label>
                <input type="text" name="kategori" id="kategori" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label>Sanksi Rekomendasi</label>
                <textarea name="sanksi_rekomendasi" class="form-control @error('sanksi_rekomendasi') is-invalid @enderror" rows="3">{{ old('sanksi_rekomendasi') }}</textarea>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
const pelanggaranData = {
    'A. KETERTIBAN': [
        {nama: 'Membuat kerbau/kegaduhan dalam kelas pada saat berlangsung/mengganggu pelajaran', poin: 10},
        {nama: 'Tidak mengikuti kegiatan belajar (membolos)', poin: 10},
        {nama: 'Siswa keluar kelas saat proses belajar mengajar berlangsung tanpa izin', poin: 6}
    ],
    'B. PAKAIAN': [
        {nama: 'Membawa seragam tidak rapi (baju tidak dimasukkan)', poin: 5},
        {nama: 'Siswa putri memakai seragam yang ketat atau rok pendek', poin: 5},
        {nama: 'Salah memakai baju, rok atau celana', poin: 5},
        {nama: 'Salah atau tidak memakai ikat pinggang', poin: 5},
        {nama: 'Tidak memakai kaus kaki', poin: 5},
        {nama: 'Salah/tidak memakai kaos dalam', poin: 5},
        {nama: 'Siswa putri memakai perhiasan perempuan', poin: 5},
        {nama: 'Siswa putri memakai perhiasan atau aksesoris (kalung)', poin: 8}
    ],
    'C. RAMBUT': [
        {nama: 'Dicukur/rambut-warna (putra-putri)', poin: 15}
    ],
    'D. BUKU, MAJALAH ATAU KASET TERLARANG': [
        {nama: 'Membawa buku majalah kaset terlarang atau HP berisi gambar dan film porno', poin: 25},
        {nama: 'Menyebarkan belikan buku, majalah atau kaset terlarang', poin: 75}
    ],
    'E. BENKATA': [
        {nama: 'Membawa senjata tajam tanpa izin', poin: 40},
        {nama: 'Membawa senjata tajam dengan izin sekolah', poin: 40},
        {nama: 'Menggunakan senjata tajam untuk mengancam', poin: 75},
        {nama: 'Menggunakan senjata tajam', poin: 75}
    ],
    'F. OBAT/MINUMAN TERLARANG': [
        {nama: 'Membawa obat terlarang/minuman terlarang', poin: 75},
        {nama: 'Menggunakan obat/minuman terlarang di dalam lingkungan sekolah', poin: 100},
        {nama: 'Menggunakan obat/minuman terlarang di dalam/di luar sekolah', poin: 100}
    ],
    'G. PERKELAHIAN': [
        {nama: 'Perkelahian dan siswa di dalam sekolah (Intern)', poin: 75},
        {nama: 'Perkelahian dan sekolah lain', poin: 25},
        {nama: 'Perkelahian dan sekolah lain (berat)', poin: 75}
    ],
    'H. PELANGGARAN TERHADAP KEPALA SEKOLAH, GURU DAN KARYAWAN': [
        {nama: 'Disertai ancaman', poin: 75},
        {nama: 'Disertai pemukulan', poin: 100}
    ],
    'I. KERAJINAN': [
        {nama: 'Satu kali terlambat', poin: 2},
        {nama: 'Dua kali terlambat', poin: 3},
        {nama: 'Tiga kali dan seterusnya terlambat', poin: 5},
        {nama: 'Terlambat masuk karena izin', poin: 3},
        {nama: 'Terlambat masuk karena tidak izin guru', poin: 2},
        {nama: 'Terlambat masuk karena alasan yang tidak dapat dipertanggungjawabkan dan tidak kembali', poin: 5},
        {nama: 'Siswa tidak masuk sekolah tanpa izin atau tidak kembali', poin: 10},
        {nama: 'Pulang tanpa izin', poin: 10}
    ],
    'J. KEHADIRAN': [
        {nama: 'Sakit tanpa keterangan (surat)', poin: 2}
    ]
};

$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    $('#kelompok').change(function() {
        const kelompok = $(this).val();
        const $namaPelanggaran = $('#nama_pelanggaran');
        
        $namaPelanggaran.empty().append('<option value="">Pilih Jenis Pelanggaran</option>');
        
        if (kelompok && pelanggaranData[kelompok]) {
            pelanggaranData[kelompok].forEach(item => {
                $namaPelanggaran.append(`<option value="${item.nama}" data-poin="${item.poin}">${item.nama} - ${item.poin} poin</option>`);
            });
            $namaPelanggaran.prop('disabled', false);
        } else {
            $namaPelanggaran.prop('disabled', true);
        }
        
        $('#poin').val('');
        $('#kategori').val('');
    });

    $('#nama_pelanggaran').change(function() {
        const poin = $(this).find(':selected').data('poin');
        $('#poin').val(poin || '');
        
        if (poin) {
            let kategori = '';
            if (poin >= 1 && poin <= 15) kategori = 'Ringan (1-15 poin)';
            else if (poin >= 16 && poin <= 30) kategori = 'Sedang (16-30 poin)';
            else if (poin >= 31 && poin <= 75) kategori = 'Berat (31-75 poin)';
            else if (poin >= 76) kategori = 'Sangat Berat (76-100 poin)';
            $('#kategori').val(kategori);
        }
    });
});
</script>
@endpush
