Route::get('/test-backup', function() {
    $disk = Storage::disk('local');
    
    $backupPath = 'private/sistem-kesiswaan';
    
    $exists = $disk->exists($backupPath);
    $files = $disk->exists($backupPath) ? $disk->files($backupPath) : [];
    
    $backups = collect($files)
        ->filter(fn($f) => str_ends_with($f, '.zip'))
        ->map(function ($file) use ($disk) {
            return [
                'name' => basename($file),
                'path' => $file,
                'size' => $disk->size($file),
                'date' => date('d/m/Y H:i:s', $disk->lastModified($file)),
            ];
        })->values();
    
    return response()->json([
        'backup_path' => $backupPath,
        'exists' => $exists,
        'files_count' => count($files),
        'files' => $files,
        'backups' => $backups
    ]);
})->middleware('auth');
