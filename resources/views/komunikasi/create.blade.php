@extends('layouts.app')

@section('title', 'Kirim Pesan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-4">
        <h2><i class="fas fa-paper-plane"></i> Kirim Pesan Baru</h2>
        <a href="{{ route('komunikasi.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('komunikasi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                @if(Auth::user()->role !== 'ortu')
                <div class="mb-3">
                    <label class="form-label">Siswa <span class="text-danger">*</span></label>
                    <select name="siswa_id" class="form-control" required id="siswaSelect">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($siswas as $siswa)
                        <option value="{{ $siswa->id }}">{{ $siswa->nama_siswa }} - {{ $siswa->kelas->nama_kelas ?? '-' }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3" id="penerimaDiv" style="display:none;">
                    <label class="form-label">Kepada Orang Tua <span class="text-danger">*</span></label>
                    <select name="penerima_id" class="form-control" id="penerimaSelect">
                        <option value="">-- Pilih siswa terlebih dahulu --</option>
                    </select>
                </div>
                @else
                <div class="mb-3">
                    <label class="form-label">Kepada <span class="text-danger">*</span></label>
                    <select name="penerima_id" class="form-control" required>
                        <option value="">-- Pilih Penerima --</option>
                        @foreach(\App\Models\User::whereIn('role', ['kesiswaan', 'wali_kelas', 'bk'])->get() as $user)
                        <option value="{{ $user->id }}">{{ $user->nama }} ({{ ucfirst($user->role) }})</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="mb-3">
                    <label class="form-label">Jenis Pesan <span class="text-danger">*</span></label>
                    <select name="jenis" class="form-control" required>
                        <option value="pesan">Pesan Biasa</option>
                        <option value="laporan_pembinaan">Laporan Pembinaan</option>
                        <option value="konsultasi">Konsultasi</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Subjek <span class="text-danger">*</span></label>
                    <input type="text" name="subjek" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Isi Pesan <span class="text-danger">*</span></label>
                    <textarea name="isi_pesan" class="form-control" rows="6" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Lampiran (Opsional)</label>
                    <input type="file" name="lampiran" class="form-control">
                    <small class="text-muted">Max: 5MB</small>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Kirim Pesan
                </button>
            </form>
        </div>
    </div>
</div>

@if(Auth::user()->role !== 'ortu')
<script>
document.getElementById('siswaSelect').addEventListener('change', function() {
    const siswaId = this.value;
    if (siswaId) {
        fetch(`/api/get-ortu-by-siswa/${siswaId}`)
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('penerimaSelect');
                select.innerHTML = '<option value="">-- Pilih Orang Tua --</option>';
                if (data.ortu) {
                    select.innerHTML += `<option value="${data.ortu.id}">${data.ortu.nama}</option>`;
                    document.getElementById('penerimaDiv').style.display = 'block';
                } else {
                    select.innerHTML += '<option value="">Orang tua belum terdaftar</option>';
                    document.getElementById('penerimaDiv').style.display = 'block';
                }
            });
    }
});
</script>
@endif
@endsection
