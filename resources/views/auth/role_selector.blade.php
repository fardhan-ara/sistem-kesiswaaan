<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pilih Role - SIKAP</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <style>
        body { margin: 0; font-family: 'Source Sans Pro', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .role-selector-container { background: white; border-radius: 15px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); padding: 40px; max-width: 500px; width: 90%; animation: slideUp 0.6s ease; }
        .role-selector-container h2 { text-align: center; margin-bottom: 30px; color: #333; font-weight: 600; }
        .role-card { border: 2px solid #e9ecef; border-radius: 10px; padding: 20px; margin-bottom: 15px; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; }
        .role-card:hover { border-color: #667eea; background: #f8f9ff; transform: translateY(-2px); }
        .role-card.selected { border-color: #667eea; background: #667eea; color: white; }
        .role-icon { font-size: 24px; margin-right: 15px; width: 40px; text-align: center; }
        .role-info h4 { margin: 0; font-size: 18px; font-weight: 600; }
        .role-info p { margin: 5px 0 0 0; font-size: 14px; opacity: 0.8; }
        .btn-continue { background: linear-gradient(45deg, #667eea, #764ba2); border: none; padding: 12px 30px; font-size: 16px; border-radius: 8px; color: white; width: 100%; margin-top: 20px; transition: all 0.3s; }
        .btn-continue:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4); }
        .btn-continue:disabled { opacity: 0.6; cursor: not-allowed; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>
    <div class="role-selector-container">
        <h2><i class="fas fa-user-cog"></i> Pilih Role Anda</h2>
        <p class="text-center text-muted mb-4">Anda memiliki beberapa role. Silakan pilih role yang ingin digunakan untuk sesi ini.</p>
        
        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        <form action="{{ route('role.select') }}" method="post" id="roleForm">
            @csrf
            <input type="hidden" name="selected_role" id="selectedRole">
            
            @foreach($allRoles as $role)
            <div class="role-card" data-role="{{ $role }}">
                <div class="role-icon">
                    @switch($role)
                        @case('admin')
                            <i class="fas fa-crown text-warning"></i>
                            @break
                        @case('kesiswaan')
                            <i class="fas fa-users text-primary"></i>
                            @break
                        @case('wali_kelas')
                            <i class="fas fa-chalkboard-teacher text-success"></i>
                            @break
                        @case('guru')
                            <i class="fas fa-user-tie text-info"></i>
                            @break
                        @case('siswa')
                            <i class="fas fa-graduation-cap text-secondary"></i>
                            @break
                        @case('ortu')
                            <i class="fas fa-heart text-danger"></i>
                            @break
                        @default
                            <i class="fas fa-user"></i>
                    @endswitch
                </div>
                <div class="role-info">
                    <h4>
                        @switch($role)
                            @case('admin') Administrator @break
                            @case('kesiswaan') Staff Kesiswaan @break
                            @case('wali_kelas') Wali Kelas @break
                            @case('guru') Guru @break
                            @case('siswa') Siswa @break
                            @case('ortu') Orang Tua @break
                            @default {{ ucfirst($role) }}
                        @endswitch
                    </h4>
                    <p>
                        @switch($role)
                            @case('admin') Akses penuh ke semua fitur sistem @break
                            @case('kesiswaan') Mengelola data siswa dan verifikasi @break
                            @case('wali_kelas') Mengelola kelas dan komunikasi ortu @break
                            @case('guru') Mencatat pelanggaran dan prestasi @break
                            @case('siswa') Melihat data pribadi dan riwayat @break
                            @case('ortu') Memantau perkembangan anak @break
                            @default Akses sesuai role
                        @endswitch
                    </p>
                </div>
            </div>
            @endforeach
            
            <button type="submit" class="btn btn-continue" id="continueBtn" disabled>
                <i class="fas fa-arrow-right"></i> Lanjutkan
            </button>
        </form>
        
        <div class="text-center mt-3">
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-muted">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleCards = document.querySelectorAll('.role-card');
            const selectedRoleInput = document.getElementById('selectedRole');
            const continueBtn = document.getElementById('continueBtn');
            
            roleCards.forEach(card => {
                card.addEventListener('click', function() {
                    // Remove selected class from all cards
                    roleCards.forEach(c => c.classList.remove('selected'));
                    
                    // Add selected class to clicked card
                    this.classList.add('selected');
                    
                    // Set selected role value
                    const role = this.dataset.role;
                    selectedRoleInput.value = role;
                    
                    // Enable continue button
                    continueBtn.disabled = false;
                });
            });
        });
    </script>
</body>
</html>