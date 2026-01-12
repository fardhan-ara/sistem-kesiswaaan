@extends('layouts.app')

@section('title', 'Kirim Notifikasi')
@section('page-title', 'Kirim Notifikasi')

@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="icon fas fa-check"></i> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="icon fas fa-ban"></i> {{ session('error') }}
</div>
@endif

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bell mr-2"></i>
                    Form Kirim Notifikasi
                </h3>
            </div>
            <form action="{{ route('notifications.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Judul Notifikasi <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title') }}" required>
                        @error('title')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Pesan <span class="text-danger">*</span></label>
                        <textarea name="message" rows="4" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
                        @error('message')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Tipe <span class="text-danger">*</span></label>
                        <select name="type" class="form-control" required>
                            <option value="info">Info (Biru)</option>
                            <option value="success">Success (Hijau)</option>
                            <option value="warning">Warning (Kuning)</option>
                            <option value="danger">Danger (Merah)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Kirim Ke <span class="text-danger">*</span></label>
                        <select name="target_type" id="target_type" class="form-control" required>
                            <option value="role">Berdasarkan Role</option>
                            <option value="individual">Pilih User Individu</option>
                        </select>
                    </div>

                    <div class="form-group" id="role_select">
                        <label>Target Role <span class="text-danger">*</span></label>
                        <select name="target_role" class="form-control">
                            <option value="all">Semua User</option>
                            <option value="admin">Admin</option>
                            <option value="kesiswaan">Kesiswaan</option>
                            <option value="kepala_sekolah">Kepala Sekolah</option>
                            <option value="bk">BK</option>
                            <option value="guru">Guru</option>
                            <option value="wali_kelas">Wali Kelas</option>
                            <option value="siswa">Siswa</option>
                            <option value="ortu">Orang Tua</option>
                        </select>
                    </div>

                    <div class="form-group" id="individual_select" style="display:none;">
                        <label>Pilih User <span class="text-danger">*</span></label>
                        <select name="target_users[]" class="form-control" multiple size="10">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->nama }} ({{ $user->email }}) - {{ ucfirst($user->role) }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Tekan Ctrl untuk pilih multiple</small>
                    </div>

                    <div class="form-group">
                        <label>Link Aksi (Opsional)</label>
                        <input type="url" name="action_url" class="form-control" value="{{ old('action_url') }}">
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Kirim Notifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-2"></i>
                    Riwayat Notifikasi Terkirim
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Judul</th>
                                <th>Target</th>
                                <th>Penerima</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($history as $item)
                                <tr>
                                    <td><small>{{ $item->created_at->format('d/m H:i') }}</small></td>
                                    <td>
                                        <strong>{{ Str::limit($item->title, 20) }}</strong><br>
                                        <small class="text-muted">{{ Str::limit($item->message, 30) }}</small>
                                    </td>
                                    <td>
                                        @if($item->target_type === 'role')
                                            <span class="badge badge-info">{{ ucfirst($item->target_value) }}</span>
                                        @else
                                            <span class="badge badge-warning">Individu</span>
                                        @endif
                                    </td>
                                    <td><span class="badge badge-success">{{ $item->recipients_count }}</span></td>
                                    <td>
                                        <form action="{{ route('notifications.sent.destroy', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Hapus riwayat ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Belum ada riwayat</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#target_type').change(function() {
        if ($(this).val() === 'individual') {
            $('#role_select').hide();
            $('#individual_select').show();
        } else {
            $('#role_select').show();
            $('#individual_select').hide();
        }
    });
});
</script>
@endpush
