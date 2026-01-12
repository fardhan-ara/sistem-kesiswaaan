@extends('layouts.app')

@section('title', 'Assign Dual Role')
@section('page-title', 'Assign Dual Role')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Assignment Dual Role</h3>
            </div>
            <form action="{{ route('admin.dual-roles.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="user_id">Pilih User</label>
                        <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                            <option value="">-- Pilih User --</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->nama }} ({{ ucfirst($user->role) }}) - {{ $user->email }}
                            </option>
                            @endforeach
                        </select>
                        @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Additional Roles</label>
                        <div class="row">
                            @foreach($availableRoles as $role)
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" name="additional_roles[]" value="{{ $role }}" 
                                           id="role_{{ $role }}" class="form-check-input"
                                           {{ in_array($role, old('additional_roles', [])) ? 'checked' : '' }}>
                                    <label for="role_{{ $role }}" class="form-check-label">
                                        {{ ucfirst($role) }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('additional_roles')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Assign Dual Role
                    </button>
                    <a href="{{ route('admin.dual-roles.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Informasi Dual Role</h3>
            </div>
            <div class="card-body">
                <p><strong>Catatan:</strong></p>
                <ul>
                    <li>Dual role memberikan akses tambahan kepada user</li>
                    <li>User tetap memiliki primary role utama</li>
                    <li>Additional role tidak boleh sama dengan primary role</li>
                    <li>Hanya admin yang bisa memberikan dual role</li>
                    <li>Semua assignment dual role akan di-track</li>
                </ul>
                
                <p><strong>Contoh:</strong></p>
                <p>Guru (primary) + Kesiswaan (additional) = Akses guru + kesiswaan</p>
            </div>
        </div>
    </div>
</div>
@endsection