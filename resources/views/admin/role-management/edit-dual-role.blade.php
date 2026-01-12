@extends('layouts.app')

@section('title', 'Edit Dual Role')
@section('page-title', 'Edit Dual Role')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Edit Dual Role - {{ $user->nama }}</h3>
            </div>
            <form action="{{ route('admin.update-dual-role', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label>User</label>
                        <input type="text" class="form-control" value="{{ $user->nama }} ({{ ucfirst($user->role) }})" readonly>
                    </div>
                    <div class="form-group">
                        <label>Additional Roles</label>
                        @foreach($availableRoles as $role)
                        <div class="form-check">
                            <input type="checkbox" name="additional_roles[]" value="{{ $role }}" 
                                   class="form-check-input" id="role_{{ $role }}"
                                   {{ in_array($role, $user->additional_roles ?? []) ? 'checked' : '' }}>
                            <label class="form-check-label" for="role_{{ $role }}">{{ ucfirst($role) }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="{{ route('admin.role-management') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection