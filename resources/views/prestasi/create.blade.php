@extends('layouts.app')

@section('title', 'Tambah Prestasi')
@section('page-title', 'Tambah Prestasi')

@section('content')
<div class="card">
    <div class="card-header bg-primary">
        <h3 class="card-title"><i class="fas fa-plus mr-1"></i> Form Tambah Prestasi Siswa</h3>
    </div>
    <form action="{{ route('prestasi.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h5><i class="icon fas fa-ban"></i> Terdapat kesalahan!</h5>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Siswa <span class="text-danger">*</span></label>
                        <select name="siswa_id" class="form-control select2 @error('siswa_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($siswas as $siswa)
                                <option value="{{ $siswa->id }}" {{ old('siswa_id') == $siswa->id ? 'selected' : '' }}>
                                    {{ $siswa->nis }} - {{ $siswa->nama_siswa }} ({{ $siswa->kelas->nama_kelas ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        @error('siswa_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        <small class="form-text text-muted">Pilih siswa yang berprestasi</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Guru Pencatat <span class="text-danger">*</span></label>
                        <select name="guru_pencatat" class="form-control select2 @error('guru_pencatat') is-invalid @enderror" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}" {{ old('guru_pencatat') == $guru->id ? 'selected' : '' }}>
                                    {{ $guru->nama_guru }} - {{ $guru->bidang_studi ?? 'Umum' }}
                                </option>
                            @endforeach
                        </select>
                        @error('guru_pencatat')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        <small class="form-text text-muted">Guru yang mencatat prestasi</small>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Jenis Prestasi <span class="text-danger">*</span></label>
                <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalPrestasi">
                    <i class="fas fa-search"></i> Pilih Jenis Prestasi
                </button>
                <input type="hidden" name="jenis_prestasi_id" id="selectedPrestasiId" value="{{ old('jenis_prestasi_id') }}" required>
                <div id="selectedPrestasiText" class="mt-2"></div>
                @error('jenis_prestasi_id')<span class="text-danger">{{ $message }}</span>@enderror
                <small class="form-text text-muted">Klik tombol untuk memilih jenis prestasi</small>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tanggal Prestasi</label>
                        <input type="date" name="tanggal_prestasi" class="form-control @error('tanggal_prestasi') is-invalid @enderror" 
                               value="{{ old('tanggal_prestasi', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}">
                        @error('tanggal_prestasi')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        <small class="form-text text-muted">Tanggal prestasi diraih</small>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="4" 
                          placeholder="Contoh: Juara 1 Lomba Matematika tingkat Kota, mengalahkan 50 peserta dari berbagai sekolah...">{{ old('keterangan') }}</textarea>
                @error('keterangan')<span class="invalid-feedback">{{ $message }}</span>@enderror
                <small class="form-text text-muted">Deskripsi detail prestasi (maksimal 1000 karakter)</small>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Prestasi</button>
            <a href="{{ route('prestasi.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </form>
</div>

<!-- Modal Pilih Prestasi -->
<div class="modal fade" id="modalPrestasi" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title"><i class="fas fa-trophy"></i> Pilih Jenis Prestasi</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Filter Tingkat</label>
                        <select id="filterTingkat" class="form-control form-control-sm">
                            <option value="">Semua Tingkat</option>
                            @foreach($tingkats as $tingkat)
                                <option value="{{ $tingkat }}">{{ ucfirst($tingkat) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Filter Kategori</label>
                        <select id="filterKategori" class="form-control form-control-sm">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoriPenampilans as $kategori)
                                <option value="{{ $kategori }}">{{ ucfirst($kategori) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Cari Prestasi</label>
                        <input type="text" id="searchPrestasi" class="form-control form-control-sm" placeholder="Ketik untuk mencari...">
                    </div>
                </div>
                <div class="form-group">
                    <label><strong>Daftar Jenis Prestasi</strong> <small class="text-muted">(Double-click untuk memilih)</small></label>
                    <select id="listPrestasi" class="form-control form-control-sm" size="15" style="cursor: pointer; font-family: monospace;">
                        @foreach($jenisPrestasis as $jenis)
                            <option value="{{ $jenis->id }}" 
                                    data-tingkat="{{ $jenis->tingkat }}" 
                                    data-kategori="{{ $jenis->kategori_penampilan }}" 
                                    data-nama="{{ strtolower($jenis->nama_prestasi) }}" 
                                    data-poin="{{ $jenis->poin_reward }}" 
                                    data-text="[+{{ $jenis->poin_reward }} poin] {{ $jenis->nama_prestasi }} ({{ ucfirst($jenis->tingkat) }})">
                                [+{{ str_pad($jenis->poin_reward, 3, ' ', STR_PAD_LEFT) }} poin] {{ $jenis->nama_prestasi }} - {{ ucfirst($jenis->tingkat) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> <strong>Tips:</strong> Gunakan filter untuk mempermudah pencarian. Double-click pada prestasi untuk memilih.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Batal</button>
                <button type="button" class="btn btn-primary" id="btnPilihPrestasi"><i class="fas fa-check"></i> Pilih Prestasi</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });
    
    // Filter prestasi function
    function filterPrestasi() {
        const tingkat = $('#filterTingkat').val().toLowerCase();
        const kategori = $('#filterKategori').val().toLowerCase();
        const search = $('#searchPrestasi').val().toLowerCase();
        
        let visibleCount = 0;
        $('#listPrestasi option').each(function() {
            const optTingkat = $(this).data('tingkat');
            const optKategori = $(this).data('kategori');
            const optNama = $(this).data('nama');
            
            const matchTingkat = !tingkat || optTingkat === tingkat;
            const matchKategori = !kategori || optKategori === kategori;
            const matchSearch = !search || optNama.includes(search);
            
            const show = matchTingkat && matchKategori && matchSearch;
            $(this).toggle(show);
            if (show) visibleCount++;
        });
        
        if (visibleCount === 0) {
            if (!$('#noResults').length) {
                $('#listPrestasi').after('<p id="noResults" class="text-danger">Tidak ada prestasi yang sesuai filter</p>');
            }
        } else {
            $('#noResults').remove();
        }
    }
    
    // Event listeners for filters
    $('#filterTingkat, #filterKategori').on('change', filterPrestasi);
    $('#searchPrestasi').on('keyup', filterPrestasi);
    
    // Select prestasi button
    $('#btnPilihPrestasi').on('click', function() {
        const selected = $('#listPrestasi option:selected');
        if (selected.val()) {
            $('#selectedPrestasiId').val(selected.val());
            $('#selectedPrestasiText').html(
                '<div class="alert alert-success"><i class="fas fa-check-circle"></i> <strong>' + 
                selected.data('text') + '</strong></div>'
            );
            $('#modalPrestasi').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Prestasi Dipilih!',
                text: selected.data('text'),
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Pilih jenis prestasi terlebih dahulu'
            });
        }
    });
    
    // Double-click to select
    $('#listPrestasi').on('dblclick', function() {
        $('#btnPilihPrestasi').click();
    });
    
    // Show selected prestasi on page load (if editing)
    @if(old('jenis_prestasi_id'))
        const oldId = '{{ old('jenis_prestasi_id') }}';
        const oldOption = $('#listPrestasi option[value="' + oldId + '"]');
        if (oldOption.length) {
            $('#selectedPrestasiText').html(
                '<div class="alert alert-success"><i class="fas fa-check-circle"></i> <strong>' + 
                oldOption.data('text') + '</strong></div>'
            );
        }
    @endif
});
</script>
@endpush