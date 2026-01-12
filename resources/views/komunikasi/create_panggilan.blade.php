@extends('layouts.app')

@section('title', 'Buat Panggilan Orang Tua')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-4">
        <h2><i class="fas fa-bell"></i> Buat Panggilan Orang Tua</h2>
        <a href="{{ route('komunikasi.panggilan') }}" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('komunikasi.store-panggilan') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Siswa <span class="text-danger">*</span></label>
                    <select name="siswa_id" class="form-control" required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($siswas as $siswa)
                        <option value="{{ $siswa->id }}">{{ $siswa->nama_siswa }} - {{ $siswa->kelas->nama_kelas ?? '-' }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Terkait Pelanggaran (Opsional)</label>
                    <select name="pelanggaran_id" class="form-control" id="pelanggaranSelect">
                        <option value="">-- Tidak terkait pelanggaran --</option>
                    </select>
                    <small class="text-muted">Pilih siswa terlebih dahulu</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Judul Panggilan <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control" required placeholder="Contoh: Panggilan Orang Tua - Pembinaan Kedisiplinan">
                </div>

                <div class="mb-3">
                    <label class="form-label">Keterangan <span class="text-danger">*</span></label>
                    <textarea name="keterangan" class="form-control" rows="4" required placeholder="Jelaskan tujuan panggilan orang tua..."></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal & Waktu <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="tanggal_panggilan" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tempat <span class="text-danger">*</span></label>
                        <input type="text" name="tempat" class="form-control" required placeholder="Contoh: Ruang BK">
                    </div>
                </div>

                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-bell"></i> Buat Panggilan
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelector('select[name="siswa_id"]').addEventListener('change', function() {
    const siswaId = this.value;
    if (siswaId) {
        fetch(`/api/get-pelanggaran-by-siswa/${siswaId}`)
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('pelanggaranSelect');
                select.innerHTML = '<option value="">-- Tidak terkait pelanggaran --</option>';
                data.pelanggarans.forEach(p => {
                    select.innerHTML += `<option value="${p.id}">${p.jenis_pelanggaran} - ${p.tanggal}</option>`;
                });
            });
    }
});
</script>
@endsection
