<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Seeder ini dinonaktifkan karena package Spatie Permission tidak digunakan
        // Role management menggunakan field 'role' di tabel users
    }
}
