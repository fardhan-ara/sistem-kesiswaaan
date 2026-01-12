@extends('layouts.app')

@section('title', 'Edit Pelanggaran')
@section('page-title', 'Edit Pelanggaran')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Edit Data Pelanggaran</h3>
    </div>
    <form action="{{ route('pelanggaran.update', $pelanggaran->id) }}" method="POST" id="formPelanggaran">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> <strong>Info:</strong> Siswa ini memiliki total <strong>{{ $totalPoin }}</strong> poin pelanggaran
            </div>

            <div class="form-group">
                <label>Siswa</label>
                <input type="text" class="form-control form-control-sm" value="{{ $pelanggaran->siswa->nama_siswa ?? 'N/A' }}" readonly>
                <input type="hidden" name="siswa_id" value="{{ $pelanggaran->siswa_id }}">
            </div>

            <div class="form-group">
                <label>Guru Pencatat</label>
                <input type="text" class="form-control form-control-sm" value="{{ $pelanggaran->guru->nama_guru ?? 'N/A' }}" readonly>
                <input type="hidden" name="guru_pencatat" value="{{ $pelanggaran->guru_pencatat }}">
            </div>

            <div class="form-group">
                <label>Jenis Pelanggaran Saat Ini</label>
                <input type="text" class="form-control form-control-sm" value="[{{ $pelanggaran->poin }} poin] {{ $pelanggaran->jenisPelanggaran->nama_pelanggaran ?? 'N/A' }}" readonly>
            </div>

            <hr>
            <h5><i class="fas fa-plus-circle"></i> Tambah Pelanggaran Baru</h5>
            <p class="text-muted">Jika siswa melakukan pelanggaran tambahan, pilih di bawah ini. Poin akan terakumulasi.</p>

            <div id="pelanggaranList">
                <!-- Pelanggaran tambahan akan ditambahkan di sini -->
            </div>

            <button type="button" class="btn btn-sm btn-success" id="btnTambahPelanggaran">
                <i class="fas fa-plus"></i> Tambah Pelanggaran
            </button>

            <div class="form-group mt-3">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3">{{ $pelanggaran->keterangan }}</textarea>
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
                        <select id="filterKelompok" class="form-control form-control-sm">
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
                            <option value="{{ $jenis->id }}" data-kelompok="{{ $jenis->kategori }}" data-nama="{{ strtolower($jenis->nama_pelanggaran) }}" data-poin="{{ $jenis->poin }}" data-text="[{{ $jenis->poin }} poin] {{ $jenis->nama_pelanggaran }}">
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
let pelanggaranIndex = 0;

$(document).ready(function() {
    // Tambah pelanggaran baru
    $('#btnTambahPelanggaran').on('click', function() {
        $('#modalPelanggaran').modal('show');
    });
    
    // Filter pelanggaran
    function filterPelanggaran() {
        const kelompok = $('#filterKelompok').val().toLowerCase();
        const search = $('#searchPelanggaran').val().toLowerCase();
        
        $('#listPelanggaran option').each(function() {
            const optKelompok = $(this).data('kelompok').toLowerCase();
            const optNama = $(this).data('nama');
            
            const matchKelompok = !kelompok || optKelompok === kelompok;
            const matchSearch = !search || optNama.includes(search);
            
            $(this).toggle(matchKelompok && matchSearch);
        });
    }
    
    $('#filterKelompok').on('change', filterPelanggaran);
    $('#searchPelanggaran').on('keyup', filterPelanggaran);
    
    // Pilih pelanggaran
    $('#btnPilihPelanggaran').on('click', function() {
        const selected = $('#listPelanggaran option:selected');
        if (selected.val()) {
            addPelanggaranItem(selected.val(), selected.data('text'), selected.data('poin'));
            $('#modalPelanggaran').modal('hide');
            $('#filterKelompok').val('');
            $('#searchPelanggaran').val('');
            filterPelanggaran();
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
        $('#btnSubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
    });
});

function addPelanggaranItem(id, text, poin) {
    const html = `
        <div class="card card-outline card-primary mb-2" id="item-${pelanggaranIndex}">
            <div class="card-body p-2">
                <div class="row">
                    <div class="col-md-10">
                        <strong>${text}</strong>
                        <input type="hidden" name="pelanggaran_tambahan[]" value="${id}">
                    </div>
                    <div class="col-md-2 text-right">
                        <button type="button" class="btn btn-danger btn-xs" onclick="removePelanggaranItem(${pelanggaranIndex})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    $('#pelanggaranList').append(html);
    pelanggaranIndex++;
}

function removePelanggaranItem(index) {
    $('#item-' + index).remove();
}

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
