@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <h4 class="mb-0">Filter Laporan</h4>
    </div>
    <div class="card-body">
        <form id="filterForm">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Kelas</label>
                    <select name="kelas_id" id="kelas_id" class="form-select">
                        <option value="">Semua Kelas</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-secondary btn-block" onclick="resetFilterLaporan()">
                        <i class="fas fa-redo"></i> Reset Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h4 class="mb-0">Export Laporan</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5>Laporan Siswa</h5>
                        <button onclick="exportPDF('siswa')" class="btn btn-danger">
                            <i class="bi bi-file-pdf"></i> Export PDF
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5>Laporan Pelanggaran</h5>
                        <button onclick="exportPDF('pelanggaran')" class="btn btn-danger">
                            <i class="bi bi-file-pdf"></i> Export PDF
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5>Laporan Prestasi</h5>
                        <button onclick="exportPDF('prestasi')" class="btn btn-danger">
                            <i class="bi bi-file-pdf"></i> Export PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function resetFilterLaporan() {
    document.getElementById('kelas_id').value = '';
    document.getElementById('tanggal_mulai').value = '';
    document.getElementById('tanggal_selesai').value = '';
}

function exportPDF(type) {
    const kelasId = document.getElementById('kelas_id').value;
    const tanggalMulai = document.getElementById('tanggal_mulai').value;
    const tanggalSelesai = document.getElementById('tanggal_selesai').value;
    
    let url = `/laporan/${type}/pdf?`;
    if (kelasId) url += `kelas_id=${kelasId}&`;
    if (tanggalMulai) url += `tanggal_mulai=${tanggalMulai}&`;
    if (tanggalSelesai) url += `tanggal_selesai=${tanggalSelesai}`;
    
    window.open(url, '_blank');
}
</script>
@endsection
