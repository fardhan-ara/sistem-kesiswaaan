@extends('layouts.app')

@section('title', 'Tambah Pelanggaran')
@section('page-title', 'Tambah Pelanggaran')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-plus mr-1"></i> Form Tambah Pelanggaran</h3>
    </div>
    <form action="/pelanggaran" method="POST" id="formPelanggaran">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>Siswa <span class="text-danger">*</span></label>
                <select name="siswa_id" id="siswa_id" class="form-control form-control-sm @error('siswa_id') is-invalid @enderror" required>
                    <option value="">Pilih Siswa</option>
                    @foreach($siswas as $siswa)
                        <option value="{{ $siswa->id }}">
                            {{ $siswa->nis }} - {{ $siswa->nama_siswa }} ({{ $siswa->kelas->nama ?? '-' }})
                        </option>
                    @endforeach
                </select>
                @error('siswa_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Guru Pencatat <span class="text-danger">*</span></label>
                <select name="guru_pencatat" id="guru_pencatat" class="form-control form-control-sm @error('guru_pencatat') is-invalid @enderror" required>
                    <option value="">Pilih Guru</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}">
                            {{ $guru->nama_guru }}
                        </option>
                    @endforeach
                </select>
                @error('guru_pencatat')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Jenis Pelanggaran <span class="text-danger">*</span></label>
                <input type="text" id="selectedPelanggaranText" class="form-control form-control-sm" readonly placeholder="Klik untuk memilih pelanggaran" style="cursor: pointer; background: white;">
                <input type="hidden" name="jenis_pelanggaran_id" id="jenis_pelanggaran_id" required>
                @error('jenis_pelanggaran_id')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Tanggal Pelanggaran <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_pelanggaran" class="form-control form-control-sm @error('tanggal_pelanggaran') is-invalid @enderror" value="{{ old('tanggal_pelanggaran', date('Y-m-d')) }}" required>
                @error('tanggal_pelanggaran')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary" id="btnSubmit"><i class="fas fa-save"></i> Simpan</button>
            <a href="/pelanggaran" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </form>
</div>

<!-- Modal Pilih Pelanggaran -->
<div class="modal fade" id="modalPelanggaran" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Jenis Pelanggaran</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Kategori Pelanggaran</label>
                        <select id="filterKategori" class="form-control form-control-sm">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori }}">{{ ucfirst($kategori) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Cari Pelanggaran</label>
                        <input type="text" id="searchPelanggaran" class="form-control form-control-sm" placeholder="Ketik untuk mencari...">
                    </div>
                </div>
                <div class="form-group">
                    <label>Pilih Jenis Pelanggaran</label>
                    <select id="listPelanggaran" class="form-control form-control-sm" size="15" style="cursor: pointer;">
                        @foreach($jenisPelanggarans as $jenis)
                            <option value="{{ $jenis->id }}" data-kategori="{{ $jenis->kategori }}" data-nama="{{ strtolower($jenis->nama_pelanggaran) }}" data-poin="{{ $jenis->poin }}" data-text="[{{ $jenis->poin }} poin] {{ $jenis->nama_pelanggaran }}">
                                [{{ $jenis->poin }} poin] {{ $jenis->nama_pelanggaran }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnPilihPelanggaran">Pilih</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Buka modal saat klik input
    $('#selectedPelanggaranText').on('click', function() {
        $('#modalPelanggaran').modal('show');
    });
    
    // Filter pelanggaran
    function filterPelanggaran() {
        const kategori = $('#filterKategori').val().toLowerCase();
        const search = $('#searchPelanggaran').val().toLowerCase();
        
        $('#listPelanggaran option').each(function() {
            const optKategori = $(this).data('kategori').toLowerCase();
            const optNama = $(this).data('nama');
            
            const matchKategori = !kategori || optKategori === kategori;
            const matchSearch = !search || optNama.includes(search);
            
            $(this).toggle(matchKategori && matchSearch);
        });
    }
    
    $('#filterKategori').on('change', filterPelanggaran);
    $('#searchPelanggaran').on('keyup', filterPelanggaran);
    
    // Pilih pelanggaran
    $('#btnPilihPelanggaran').on('click', function() {
        const selected = $('#listPelanggaran option:selected');
        if (selected.val()) {
            $('#jenis_pelanggaran_id').val(selected.val());
            $('#selectedPelanggaranText').val(selected.data('text'));
            $('#modalPelanggaran').modal('hide');
        } else {
            Swal.fire('Perhatian', 'Pilih jenis pelanggaran terlebih dahulu', 'warning');
        }
    });
    
    // Double click untuk pilih
    $('#listPelanggaran').on('dblclick', function() {
        $('#btnPilihPelanggaran').click();
    });
    
    // Submit form
    $('#formPelanggaran').on('submit', function(e) {
        if(!$('#siswa_id').val() || !$('#guru_pencatat').val() || !$('#jenis_pelanggaran_id').val()) {
            e.preventDefault();
            Swal.fire('Error', 'Harap isi semua field yang wajib!', 'error');
            return false;
        }
        $('#btnSubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
    });
});

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
