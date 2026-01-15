@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body text-center py-5">
                @if(isset($error))
                    <i class="fas fa-exclamation-triangle fa-5x text-danger mb-4"></i>
                    <h3>Terjadi Kesalahan</h3>
                    <p class="text-muted">{{ $error }}</p>
                @else
                    <i class="fas fa-home fa-5x text-primary mb-4"></i>
                    <h3>Selamat Datang di SIKAP</h3>
                    <p class="text-muted">Sistem Informasi Kesiswaan dan Prestasi</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection