@extends('layouts.app')

@section('title', 'Dual Role Management')
@section('page-title', 'Dual Role Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar User dengan Dual Role</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.dual-roles.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Assign Dual Role
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Primary Role</th>
                                <th>Additional Roles</th>
                                <th>Approved By</th>
                                <th>Tanggal Approval</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->nama }}</td>
                                <td>
                                    <span class="badge badge-primary">{{ ucfirst($user->role) }}</span>
                                </td>
                                <td>
                                    @if($user->additional_roles)
                                        @foreach($user->additional_roles as $role)
                                        <span class="badge badge-success">{{ ucfirst($role) }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $user->dualRoleApprovedBy->nama ?? '-' }}</td>
                                <td>{{ $user->dual_role_approved_at ? $user->dual_role_approved_at->format('d/m/Y H:i') : '-' }}</td>
                                <td>
                                    <form action="{{ route('admin.dual-roles.destroy', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin cabut dual role ini?')">
                                            <i class="fas fa-times"></i> Cabut
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada user dengan dual role</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection