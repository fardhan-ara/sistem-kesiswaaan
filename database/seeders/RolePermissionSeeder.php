<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'view-pelanggaran',
            'create-pelanggaran',
            'verify-pelanggaran',
            'view-prestasi',
            'create-prestasi',
            'verify-prestasi',
            'view-sanksi',
            'manage-sanksi',
            'view-bk',
            'create-bk',
            'view-reports',
            'manage-users',
            'backup-system',
            'view-dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $kesiswaan = Role::create(['name' => 'kesiswaan']);
        $kesiswaan->givePermissionTo([
            'view-pelanggaran', 'create-pelanggaran', 'verify-pelanggaran',
            'view-prestasi', 'create-prestasi', 'verify-prestasi',
            'view-sanksi', 'manage-sanksi',
            'view-bk', 'create-bk',
            'view-reports', 'view-dashboard'
        ]);

        $guru = Role::create(['name' => 'guru']);
        $guru->givePermissionTo([
            'view-pelanggaran', 'create-pelanggaran',
            'view-prestasi', 'create-prestasi',
            'view-sanksi', 'view-bk', 'view-dashboard'
        ]);

        $waliKelas = Role::create(['name' => 'wali_kelas']);
        $waliKelas->givePermissionTo([
            'view-pelanggaran', 'create-pelanggaran',
            'view-prestasi', 'create-prestasi',
            'view-sanksi', 'view-bk', 'create-bk', 'view-dashboard'
        ]);

        $siswa = Role::create(['name' => 'siswa']);
        $siswa->givePermissionTo(['view-dashboard']);

        $ortu = Role::create(['name' => 'ortu']);
        $ortu->givePermissionTo(['view-dashboard']);
    }
}
