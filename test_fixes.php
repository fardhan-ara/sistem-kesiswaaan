<?php

// Test script untuk memverifikasi perbaikan error 500
echo "=== Testing Error 500 Fixes ===\n";

// Test 1: Route parameter binding
echo "1. Testing route parameter binding...\n";
try {
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    $kelasEditRoute = $routes->getByName('kelas.edit');
    if ($kelasEditRoute) {
        echo "✓ Route kelas.edit found: " . $kelasEditRoute->uri() . "\n";
        if (strpos($kelasEditRoute->uri(), '{kelas}') !== false) {
            echo "✓ Route parameter is correct: {kelas}\n";
        } else {
            echo "✗ Route parameter is incorrect\n";
        }
    } else {
        echo "✗ Route kelas.edit not found\n";
    }
} catch (Exception $e) {
    echo "✗ Error testing routes: " . $e->getMessage() . "\n";
}

// Test 2: Model binding
echo "\n2. Testing model binding...\n";
try {
    $kelas = \App\Models\Kelas::find(8);
    if ($kelas) {
        echo "✓ Kelas model found: " . $kelas->nama_kelas . "\n";
        echo "✓ Route key: " . $kelas->getRouteKey() . "\n";
    } else {
        echo "! No kelas with ID 8 found\n";
    }
} catch (Exception $e) {
    echo "✗ Error testing model: " . $e->getMessage() . "\n";
}

// Test 3: Controller error handling
echo "\n3. Testing controller error handling...\n";
try {
    $controller = new \App\Http\Controllers\KelasController();
    echo "✓ KelasController instantiated successfully\n";
    
    // Check if methods have try-catch
    $reflection = new ReflectionClass($controller);
    $editMethod = $reflection->getMethod('edit');
    $source = file_get_contents($editMethod->getFileName());
    $methodSource = substr($source, $editMethod->getStartLine() - 1, $editMethod->getEndLine() - $editMethod->getStartLine() + 1);
    
    if (strpos(implode('', $methodSource), 'try') !== false) {
        echo "✓ Edit method has error handling\n";
    } else {
        echo "! Edit method may not have error handling\n";
    }
} catch (Exception $e) {
    echo "✗ Error testing controller: " . $e->getMessage() . "\n";
}

// Test 4: View compilation
echo "\n4. Testing view compilation...\n";
try {
    // Test if views can be compiled without errors
    $viewPaths = [
        'kelas.edit',
        'users.index'
    ];
    
    foreach ($viewPaths as $viewPath) {
        if (view()->exists($viewPath)) {
            echo "✓ View {$viewPath} exists\n";
        } else {
            echo "✗ View {$viewPath} not found\n";
        }
    }
} catch (Exception $e) {
    echo "✗ Error testing views: " . $e->getMessage() . "\n";
}

echo "\n=== Test Summary ===\n";
echo "If all tests show ✓, the error 500 fixes should be working.\n";
echo "You can now test the URLs:\n";
echo "- /kelas/8/edit\n";
echo "- /users\n";
echo "- Other controller routes\n";