@extends('layouts.app')

@section('title', 'Tambah Prestasi')
@section('page-title', 'Tambah Prestasi')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-plus mr-1"></i> Form Tambah Prestasi</h3>
    </div>
    <form action="{{ route('prestasi.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>Siswa <span class="text-danger">*</span></label>
                <select name="siswa_id" id="siswa_id" class="form-control select2 @error('siswa_id') is-invalid @enderror" required>
                    <option value="">Pilih Siswa</option>
                    @foreach($siswas as $siswa)
                        <option value="{{ $siswa->id }}" {{ old('siswa_id') == $siswa->id ? 'selected' : '' }}>
                            {{ $siswa->nis }} - {{ $siswa->nama_siswa }} ({{ $siswa->kelas->nama ?? '-' }})
                        </option>
                    @endforeach
                </select>
                @error('siswa_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Guru Pencatat <span class="text-danger">*</span></label>
                <select name="guru_pencatat" id="guru_pencatat" class="form-control select2 @error('guru_pencatat') is-invalid @enderror" required>
                    <option value="">Pilih Guru</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}" {{ old('guru_pencatat') == $guru->id ? 'selected' : '' }}>
                            {{ $guru->nama_guru }}
                        </option>
                    @endforeach
                </select>
                @error('guru_pencatat')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Kategori Prestasi <span class="text-danger">*</span></label>
                <select name="kategori" id="kategori" class="form-control @error('kategori') is-invalid @enderror" required>
                    <option value="">Pilih Kategori</option>
                    <option value="akademik" {{ old('kategori') === 'akademik' ? 'selected' : '' }}>Akademik</option>
                    <option value="non_akademik" {{ old('kategori') === 'non_akademik' ? 'selected' : '' }}>Non-Akademik</option>
                </select>
                @error('kategori')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Jenis Prestasi <span class="text-danger">*</span></label>
                <select name="jenis_prestasi_id" id="jenis_prestasi_id" class="form-control select2 @error('jenis_prestasi_id') is-invalid @enderror" required>
                    <option value="">Pilih Jenis Prestasi</option>
                    @foreach($jenisPrestasis as $jenis)
                        <option value="{{ $jenis->id }}" data-poin="{{ $jenis->poin }}" data-kategori="{{ $jenis->kategori }}" {{ old('jenis_prestasi_id') == $jenis->id ? 'selected' : '' }}>
                            {{ $jenis->nama_prestasi }} (Poin: {{ $jenis->poin }})
                        </option>
                    @endforeach
                </select>
                @error('jenis_prestasi_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Poin</label>
                <input type="number" name="poin" id="poin" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3">{{ old('keterangan') }}</textarea>
                @error('keterangan')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            <a href="{{ route('prestasi.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
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

    $('#kategori').change(function() {
        var kategori = $(this).val();
        $('#jenis_prestasi_id option').each(function() {
            var optKategori = $(this).data('kategori');
            if (kategori === '' || optKategori === kategori) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        $('#jenis_prestasi_id').val('').trigger('change');
        $('#poin').val('');
    });

    $('#jenis_prestasi_id').change(function() {
        var poin = $(this).find(':selected').data('poin');
        $('#poin').val(poin || '');
    });
});
</script>
@endpush
