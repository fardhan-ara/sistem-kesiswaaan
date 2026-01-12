<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\CustomVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = ['nama', 'email', 'password', 'role', 'no_telp', 'foto', 'allow_dual_role', 'additional_roles', 'dual_role_approved_by', 'dual_role_approved_at', 'is_wali_kelas', 'kelas_id', 'metadata', 'status', 'verified_by', 'verified_at', 'rejection_reason', 'nama_anak', 'nis_anak', 'last_login_at', 'last_activity_at'];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'email_verified_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'last_login_at' => 'datetime',
            'last_activity_at' => 'datetime',
            'verified_at' => 'datetime',
            'dual_role_approved_at' => 'datetime',
            'additional_roles' => 'array'
        ];
    }

    public function guru(): HasOne
    {
        return $this->hasOne(Guru::class, 'users_id');
    }

    public function siswa(): HasOne
    {
        return $this->hasOne(Siswa::class, 'users_id');
    }

    public function isGuru(): bool
    {
        return in_array($this->role, ['guru', 'wali_kelas']);
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKesiswaan(): bool
    {
        return $this->role === 'kesiswaan';
    }

    public function sendEmailVerificationNotification()
    {
        \Log::info('Sending verification email to: ' . $this->email);
        
        try {
            $this->notify(new CustomVerifyEmail);
            \Log::info('Verification email sent successfully to: ' . $this->email);
        } catch (\Exception $e) {
            \Log::error('Failed to send verification email: ' . $e->getMessage());
            throw $e;
        }
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function biodataOrtu()
    {
        return $this->hasOne(BiodataOrtu::class);
    }

    public function currentHomeroomClass()
    {
        $guru = $this->guru;
        if (!$guru) return null;
        
        return Kelas::where('wali_kelas_id', $guru->id)
            ->with(['tahunAjaran', 'siswas'])
            ->first();
    }

    public function isHomeroomTeacher(): bool
    {
        $guru = $this->guru;
        if (!$guru) return false;
        
        return Kelas::where('wali_kelas_id', $guru->id)->exists();
    }

    public function hasRole($roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];
        return in_array($this->role, $roles);
    }
    
    public function getAllRoles(): array
    {
        return [$this->role];
    }
    
    public function dualRoleApprovedBy()
    {
        return $this->belongsTo(User::class, 'dual_role_approved_by');
    }
    
    public function hasApprovedDualRole(): bool
    {
        return $this->allow_dual_role && $this->additional_roles && $this->dual_role_approved_at;
    }
    
    public function getPrimaryRoleAttribute(): string
    {
        return $this->role;
    }
    
    public function getActiveRolesAttribute(): array
    {
        return $this->getAllRoles();
    }
    
    public function getActiveRole(): string
    {
        return $this->role;
    }
    
    public function hasActiveRole($roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];
        return in_array($this->role, $roles);
    }
}
