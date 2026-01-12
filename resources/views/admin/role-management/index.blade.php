@extends('layouts.app')

@section('title', 'Role Management')
@section('page-title', 'Role Management')

@section('content')
<div class="row">
    <!-- Assign Dual Role Form -->
    <div class="col-md-4">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Assign Dual Role</h3>
            </div>
            <form action="{{ route('admin.assign-dual-role') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>User</label>
                        <select name="user_id" class="form-control" required>
                            <option value="">-- Pilih User --</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->nama }} ({{ ucfirst($user->role) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Additional Roles</label>
                        @foreach($availableRoles as $role)
                        <div class="form-check">
                            <input type="checkbox" name="additional_roles[]" value="{{ $role }}" class="form-check-input" id="role_{{ $role }}">
                            <label class="form-check-label" for="role_{{ $role }}">{{ ucfirst($role) }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="fas fa-plus"></i> Assign Dual Role
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Dual Role List -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Dual Role Users</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Primary Role</th>
                                <th>Additional Roles</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dualRoleUsers as $user)
                            <tr>
                                <td>{{ $user->nama }}</td>
                                <td><span class="badge badge-primary">{{ ucfirst($user->role) }}</span></td>
                                <td>
                                    @if($user->additional_roles)
                                        @foreach($user->additional_roles as $role)
                                        <span class="badge badge-success">{{ ucfirst($role) }}</span>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.edit-dual-role', $user->id) }}" class="btn btn-warning btn-xs">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.remove-dual-role', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Cabut dual role?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center">Belum ada dual role users</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection