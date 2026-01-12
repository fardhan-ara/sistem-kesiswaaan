<!-- Tambahkan di navbar layouts/app.blade.php -->

<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        @if(Auth::user()->unreadNotifications->count() > 0)
            <span class="badge badge-danger navbar-badge">
                {{ Auth::user()->unreadNotifications->count() }}
            </span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">
            {{ Auth::user()->unreadNotifications->count() }} Notifikasi Baru
        </span>
        
        <div class="dropdown-divider"></div>
        
        @forelse(Auth::user()->unreadNotifications->take(5) as $notification)
            @php $data = $notification->data; @endphp
            <div class="dropdown-item" style="cursor: pointer;" onclick="document.getElementById('mark-read-{{ $notification->id }}').submit();">
                <i class="fas fa-bell mr-2"></i> {{ $data['title'] }}
                <span class="float-right text-muted text-sm">
                    {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                </span>
            </div>
            <form id="mark-read-{{ $notification->id }}" action="{{ route('notifications.read', $notification->id) }}" method="POST" class="d-none">
                @csrf
            </form>
            <div class="dropdown-divider"></div>
        @empty
            <span class="dropdown-item text-muted">Tidak ada notifikasi baru</span>
            <div class="dropdown-divider"></div>
        @endforelse
        
        <a href="{{ route('notifications.index') }}" class="dropdown-item dropdown-footer">
            Lihat Semua Notifikasi
        </a>
    </div>
</li>
