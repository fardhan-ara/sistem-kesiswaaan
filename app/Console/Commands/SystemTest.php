<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SystemTest extends Command
{
    protected $signature = 'system:test';
    protected $description = 'Run comprehensive system tests';

    public function handle()
    {
        $this->info('🧪 Running Comprehensive System Tests...');
        $this->newLine();
        
        $passed = 0;
        $failed = 0;
        
        // Test 1: Database Connection
        $this->info('1️⃣ Testing Database Connection...');
        try {
            \DB::connection()->getPdo();
            $this->info('   ✓ Database connected');
            $passed++;
        } catch (\Exception $e) {
            $this->error('   ✗ Database connection failed: ' . $e->getMessage());
            $failed++;
        }
        
        // Test 2: All Models Load
        $this->info('2️⃣ Testing Models...');
        $models = [
            'User', 'Guru', 'Siswa', 'Kelas', 'TahunAjaran',
            'Pelanggaran', 'Prestasi', 'Sanksi', 'BimbinganKonseling',
            'JenisPelanggaran', 'JenisPrestasi', 'BiodataOrtu'
        ];
        
        foreach ($models as $model) {
            try {
                $class = "App\\Models\\{$model}";
                $class::count();
                $passed++;
            } catch (\Exception $e) {
                $this->error("   ✗ Model {$model}: " . $e->getMessage());
                $failed++;
            }
        }
        $this->info("   ✓ {$passed} models loaded");
        
        // Test 3: Relationships
        $this->info('3️⃣ Testing Relationships...');
        try {
            \App\Models\Pelanggaran::with(['siswa', 'guruPencatat', 'jenisPelanggaran'])->first();
            \App\Models\Prestasi::with(['siswa', 'guruPencatat', 'jenisPrestasi'])->first();
            \App\Models\Siswa::with(['user', 'kelas', 'tahunAjaran'])->first();
            \App\Models\Guru::with(['user', 'kelas'])->first();
            $this->info('   ✓ All relationships working');
            $passed++;
        } catch (\Exception $e) {
            $this->error('   ✗ Relationship error: ' . $e->getMessage());
            $failed++;
        }
        
        // Test 4: Routes
        $this->info('4️⃣ Testing Critical Routes...');
        $routes = ['login', 'dashboard', 'siswa.index', 'users.index'];
        foreach ($routes as $routeName) {
            try {
                route($routeName);
                $passed++;
            } catch (\Exception $e) {
                $this->error("   ✗ Route {$routeName}: " . $e->getMessage());
                $failed++;
            }
        }
        $this->info("   ✓ Routes registered");
        
        // Test 5: Middleware
        $this->info('5️⃣ Testing Middleware...');
        $middlewares = ['auth', 'role', 'wali_kelas'];
        foreach ($middlewares as $m) {
            if (array_key_exists($m, app('router')->getMiddleware())) {
                $passed++;
            } else {
                $this->error("   ✗ Middleware {$m} not registered");
                $failed++;
            }
        }
        $this->info('   ✓ Middleware registered');
        
        // Test 6: Config
        $this->info('6️⃣ Testing Configuration...');
        $configs = ['app.name', 'database.default', 'session.driver'];
        foreach ($configs as $c) {
            if (config($c)) {
                $passed++;
            } else {
                $this->error("   ✗ Config {$c} missing");
                $failed++;
            }
        }
        $this->info('   ✓ Configuration valid');
        
        // Summary
        $this->newLine();
        $total = $passed + $failed;
        $percentage = $total > 0 ? round(($passed / $total) * 100, 2) : 0;
        
        $this->info("📊 Test Results:");
        $this->table(
            ['Status', 'Count', 'Percentage'],
            [
                ['Passed', $passed, $percentage . '%'],
                ['Failed', $failed, (100 - $percentage) . '%'],
                ['Total', $total, '100%']
            ]
        );
        
        if ($failed === 0) {
            $this->info('✅ All tests passed!');
            return 0;
        } else {
            $this->error("❌ {$failed} test(s) failed!");
            return 1;
        }
    }
}
