@extends('layouts.app')

@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>Sistem</b> Kesiswaan</a>
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Lupa password? Masukkan email Anda untuk menerima link reset</p>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('password.email') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" autocomplete="username" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <a href="{{ route('login') }}">Kembali ke Login</a>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Kirim</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
