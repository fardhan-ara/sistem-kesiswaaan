@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Pengaturan Akun</div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        Email Anda telah diverifikasi! Anda dapat mengakses halaman pengaturan ini.
                    </div>
                    
                    <h5>Informasi Akun</h5>
                    <p><strong>Nama:</strong> {{ auth()->user()->nama }}</p>
                    <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                    <p><strong>Role:</strong> {{ auth()->user()->role }}</p>
                    <p><strong>Email Verified:</strong> 
                        @if(auth()->user()->hasVerifiedEmail())
                            <span class="badge badge-success">Terverifikasi</span>
                        @else
                            <span class="badge badge-warning">Belum Terverifikasi</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection