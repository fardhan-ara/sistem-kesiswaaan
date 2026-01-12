@extends('layouts.app')

@section('title', 'Mata Pelajaran Saya')
@section('page-title', 'Mata Pelajaran yang Saya Ajar')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-book mr-1"></i> Pilih Mata Pelajaran</h3>
    </div>
    <form action="{{ route('guru.mata-pelajaran.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Pilih mata pelajaran yang Anda ajar. Anda bisa memilih lebih dari satu.
            </div>
            
            <div class="form-group">
                <label>Bidang Studi Utama</label>
                <input type="text" class="form-control" value="{{ $guru->bidang_studi }}" readonly>
            </div>
            
            <div class="form-group">
                <label>Mata Pelajaran yang Diajar <span class="text-danger">*</span></label>
                <div class="row">
                    @foreach($mataPelajaranList as $mapel)
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" name="mata_pelajaran[]" value="{{ $mapel }}" 
                                class="form-check-input" id="mapel_{{ $loop->index }}"
                                {{ $guru->mata_pelajaran && in_array($mapel, $guru->mata_pelajaran) ? 'checked' : '' }}>
                            <label class="form-check-label" for="mapel_{{ $loop->index }}">
                                {{ $mapel }}
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            @if($guru->mata_pelajaran && count($guru->mata_pelajaran) > 0)
            <div class="alert alert-success">
                <strong>Mata Pelajaran Terpilih:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($guru->mata_pelajaran as $mapel)
                    <li>{{ $mapel }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>
@endsection
