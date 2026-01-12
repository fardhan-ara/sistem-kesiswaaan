<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class FixUserRole extends Command
{
    protected $signature = 'user:fix-role {email} {role}';
    protected $description = 'Fix user role for accessing siswa module';

    public function handle()
    {
        $email = $this->argument('email');
        $role = $this->argument('role');

        $validRoles = ['admin', 'kesiswaan', 'guru', 'wali_kelas', 'siswa', 'ortu', 'verifikator'];

        if (!in_array($role, $validRoles)) {
            $this->error("Role tidak valid! Role yang tersedia: " . implode(', ', $validRoles));
            return 1;
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User dengan email '{$email}' tidak ditemukan!");
            return 1;
        }

        $oldRole = $user->role;
        $user->role = $role;
        $user->save();

        $this->info("✓ Role user berhasil diubah!");
        $this->table(
            ['Field', 'Old Value', 'New Value'],
            [
                ['Nama', $user->nama, $user->nama],
                ['Email', $user->email, $user->email],
                ['Role', $oldRole, $role],
            ]
        );

        if (in_array($role, ['admin', 'kesiswaan'])) {
            $this->info('✓ User sekarang BISA mengakses Data Master Siswa!');
        } else {
            $this->warn('⚠ User TIDAK BISA mengakses Data Master Siswa dengan role ini!');
        }

        return 0;
    }
}
