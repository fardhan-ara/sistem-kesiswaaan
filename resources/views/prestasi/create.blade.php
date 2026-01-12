@extends('layouts.app')

@section('title', 'Tambah Prestasi')
@section('page-title', 'Tambah Prestasi')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-plus mr-1"></i> Form Tambah Prestasi</h3>
    </div>
    <form action="/prestasi-store-test" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>Siswa <span class="text-danger">*</span></label>
                <select name="siswa_id" class="form-control form-control-sm @error('siswa_id') is-invalid @enderror" required>
                    <option value="">Pilih Siswa</option>
                    @foreach($siswas as $siswa)
                        <option value="{{ $siswa->id }}">{{ $siswa->nis }} - {{ $siswa->nama_siswa }} ({{ $siswa->kelas->nama_kelas ?? '-' }})</option>
                    @endforeach
                </select>
                @error('siswa_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Guru Pencatat <span class="text-danger">*</span></label>
                <select name="guru_pencatat" class="form-control form-control-sm @error('guru_pencatat') is-invalid @enderror" required>
                    <option value="">Pilih Guru</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}">{{ $guru->nama_guru }}</option>
                    @endforeach
                </select>
                @error('guru_pencatat')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Jenis Prestasi <span class="text-danger">*</span></label>
                <button type="button" class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#modalPrestasi">
                    <i class="fas fa-search"></i> Pilih Jenis Prestasi
                </button>
                <input type="hidden" name="jenis_prestasi_id" id="selectedPrestasiId" required>
                <div id="selectedPrestasiText" class="mt-2"></div>
            </div>

            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            <a href="{{ route('prestasi.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </form>
</div>

<!-- Modal Pilih Prestasi -->
<div class="modal fade" id="modalPrestasi" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Jenis Prestasi</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Tingkat Prestasi</label>
                        <select id="filterTingkat" class="form-control form-control-sm">
                            <option value="">Semua Tingkat</option>
                            @foreach($tingkats as $tingkat)
                                <option value="{{ $tingkat }}">{{ ucfirst($tingkat) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Cari Prestasi</label>
                        <input type="text" id="searchPrestasi" class="form-control form-control-sm" placeholder="Ketik untuk mencari...">
                    </div>
                </div>
                <div class="form-group">
                    <label>Pilih Jenis Prestasi</label>
                    <select id="listPrestasi" class="form-control form-control-sm" size="15" style="cursor: pointer;">
                        @foreach($jenisPrestasis as $jenis)
                            <option value="{{ $jenis->id }}" data-tingkat="{{ $jenis->tingkat }}" data-nama="{{ strtolower($jenis->nama_prestasi) }}" data-poin="{{ $jenis->poin_reward }}" data-text="[{{ $jenis->poin_reward }} poin] {{ $jenis->nama_prestasi }}">
                                [{{ $jenis->poin_reward }} poin] {{ $jenis->nama_prestasi }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnPilihPrestasi">Pilih</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    function filterPrestasi() {
        const tingkat = $('#filterTingkat').val().toLowerCase();
        const search = $('#searchPrestasi').val().toLowerCase();
        
        $('#listPrestasi option').each(function() {
            const optTingkat = $(this).data('tingkat');
            const optNama = $(this).data('nama');
            
            const matchTingkat = !tingkat || optTingkat === tingkat;
            const matchSearch = !search || optNama.includes(search);
            
            $(this).toggle(matchTingkat && matchSearch);
        });
    }
    
    $('#filterTingkat').on('change', filterPrestasi);
    $('#searchPrestasi').on('keyup', filterPrestasi);
    
    $('#btnPilihPrestasi').on('click', function() {
        const selected = $('#listPrestasi option:selected');
        if (selected.val()) {
            $('#selectedPrestasiId').val(selected.val());
            $('#selectedPrestasiText').html('<div class="alert alert-success"><strong>' + selected.data('text') + '</strong></div>');
            $('#modalPrestasi').modal('hide');
        } else {
            Swal.fire('Perhatian', 'Pilih jenis prestasi terlebih dahulu', 'warning');
        }
    });
    
    $('#listPrestasi').on('dblclick', function() {
        $('#btnPilihPrestasi').click();
    });
});
</script>
@endpush
