@extends('layouts.app')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi Saya')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bell mr-2"></i>
                    Daftar Notifikasi
                </h3>
                <div class="card-tools">
                    @if($unreadCount > 0)
                    <form action="{{ route('notifications.readAll') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            <div class="card-body p-0">
                @forelse($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $typeClass = [
                            'info' => 'bg-info',
                            'success' => 'bg-success',
                            'warning' => 'bg-warning',
                            'danger' => 'bg-danger'
                        ][$data['type']] ?? 'bg-info';
                    @endphp
                    
                    <div class="list-group-item {{ $notification->read_at ? '' : 'bg-light' }}">
                        <div class="d-flex">
                            <div class="mr-3">
                                <span class="badge {{ $typeClass }} p-2">
                                    <i class="fas fa-bell"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">
                                    {{ $data['title'] }}
                                    @if(!$notification->read_at)
                                        <span class="badge badge-primary badge-sm">Baru</span>
                                    @endif
                                </h5>
                                <p class="mb-1">{{ $data['message'] }}</p>
                                <small class="text-muted">
                                    <i class="far fa-clock"></i> 
                                    {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                </small>
                                
                                @if(isset($data['action_url']) && $data['action_url'])
                                    <div class="mt-2">
                                        <a href="{{ $data['action_url'] }}" class="btn btn-sm btn-outline-primary">
                                            {{ $data['action_text'] ?? 'Lihat Detail' }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-3">
                                @if(!$notification->read_at)
                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Tandai Dibaca">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus" 
                                            onclick="return confirm('Hapus notifikasi ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada notifikasi</p>
                    </div>
                @endforelse
            </div>
            
            @if($notifications->hasPages())
            <div class="card-footer">
                {{ $notifications->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
