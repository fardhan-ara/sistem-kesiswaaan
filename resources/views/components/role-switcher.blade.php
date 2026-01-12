@if(auth()->check() && count(auth()->user()->getAllRoles()) > 1)
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="roleSwitcher" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-user-cog"></i>
        <span class="d-none d-sm-inline">
            @switch(auth()->user()->getActiveRole())
                @case('admin') Admin @break
                @case('kesiswaan') Kesiswaan @break
                @case('wali_kelas') Wali Kelas @break
                @case('guru') Guru @break
                @case('siswa') Siswa @break
                @case('ortu') Orang Tua @break
                @default {{ ucfirst(auth()->user()->getActiveRole()) }}
            @endswitch
        </span>
    </a>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="roleSwitcher">
        <h6 class="dropdown-header">Beralih Role</h6>
        @foreach(auth()->user()->getAllRoles() as $role)
            @if($role !== auth()->user()->getActiveRole())
            <a class="dropdown-item" href="#" onclick="switchRole('{{ $role }}')">
                <i class="fas fa-{{ $role === 'admin' ? 'crown' : ($role === 'kesiswaan' ? 'users' : ($role === 'wali_kelas' ? 'chalkboard-teacher' : ($role === 'guru' ? 'user-tie' : ($role === 'siswa' ? 'graduation-cap' : 'heart')))) }}"></i>
                @switch($role)
                    @case('admin') Administrator @break
                    @case('kesiswaan') Staff Kesiswaan @break
                    @case('wali_kelas') Wali Kelas @break
                    @case('guru') Guru @break
                    @case('siswa') Siswa @break
                    @case('ortu') Orang Tua @break
                    @default {{ ucfirst($role) }}
                @endswitch
            </a>
            @endif
        @endforeach
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{ route('role.selector') }}">
            <i class="fas fa-cog"></i> Kelola Role
        </a>
    </div>
</li>

<form id="roleSwitchForm" action="{{ route('role.select') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="selected_role" id="switchToRole">
</form>

<script>
function switchRole(role) {
    document.getElementById('switchToRole').value = role;
    document.getElementById('roleSwitchForm').submit();
}
</script>
@endif