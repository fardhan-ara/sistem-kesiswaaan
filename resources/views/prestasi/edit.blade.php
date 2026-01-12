@extends('layouts.app')

@section('title', 'Edit Prestasi')
@section('page-title', 'Edit Prestasi')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Edit Data Prestasi</h3>
    </div>
    <form action="{{ route('prestasi.update', $prestasi->id) }}" method="POST" id="formPrestasi">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> <strong>Info:</strong> Siswa ini memiliki total <strong>{{ $totalPoin }}</strong> poin prestasi
            </div>

            <div class="form-group">
                <label>Siswa</label>
                <input type="text" class="form-control form-control-sm" value="{{ $prestasi->siswa->nama_siswa ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
                <label>Guru Pencatat</label>
                <input type="text" class="form-control form-control-sm" value="{{ $prestasi->guru->nama_guru ?? 'N/A' }}" readonly>
            </div>

            <div class="form-group">
                <label>Jenis Prestasi Saat Ini</label>
                <input type="text" class="form-control form-control-sm" value="[{{ $prestasi->poin }} poin] {{ $prestasi->jenisPrestasi->nama_prestasi ?? 'N/A' }}" readonly>
            </div>

            <hr>
            <h5><i class="fas fa-plus-circle"></i> Tambah Prestasi Baru</h5>
            <p class="text-muted">Jika siswa meraih prestasi tambahan, pilih di bawah ini. Poin akan terakumulasi.</p>

            <div id="prestasiList">
                <!-- Prestasi tambahan akan ditambahkan di sini -->
            </div>

            <button type="button" class="btn btn-sm btn-success" id="btnTambahPrestasi">
                <i class="fas fa-plus"></i> Tambah Prestasi
            </button>

            <div class="form-group mt-3">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3">{{ $prestasi->keterangan }}</textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary" id="btnSubmit"><i class="fas fa-save"></i> Simpan</button>
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
                        <label>Tingkat</label>
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
let prestasiIndex = 0;

$(document).ready(function() {
    $('#btnTambahPrestasi').on('click', function() {
        $('#modalPrestasi').modal('show');
    });
    
    function filterPrestasi() {
        const tingkat = $('#filterTingkat').val().toLowerCase();
        const search = $('#searchPrestasi').val().toLowerCase();
        
        $('#listPrestasi option').each(function() {
            const optTingkat = $(this).data('tingkat').toLowerCase();
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
            addPrestasiItem(selected.val(), selected.data('text'), selected.data('poin'));
            $('#modalPrestasi').modal('hide');
            $('#filterTingkat').val('');
            $('#searchPrestasi').val('');
            filterPrestasi();
        } else {
            Swal.fire('Perhatian', 'Pilih jenis prestasi terlebih dahulu', 'warning');
        }
    });
    
    $('#listPrestasi').on('dblclick', function() {
        $('#btnPilihPrestasi').click();
    });
    
    $('#formPrestasi').on('submit', function(e) {
        $('#btnSubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
    });
});

function addPrestasiItem(id, text, poin) {
    const html = `
        <div class="card card-outline card-success mb-2" id="item-${prestasiIndex}">
            <div class="card-body p-2">
                <div class="row">
                    <div class="col-md-10">
                        <strong>${text}</strong>
                        <input type="hidden" name="prestasi_tambahan[]" value="${id}">
                    </div>
                    <div class="col-md-2 text-right">
                        <button type="button" class="btn btn-danger btn-xs" onclick="removePrestasiItem(${prestasiIndex})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    $('#prestasiList').append(html);
    prestasiIndex++;
}

function removePrestasiItem(index) {
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
