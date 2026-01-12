// Tambahkan ke routes/web.php

Route::middleware(['auth'])->group(function () {
    
    // Notifikasi untuk semua user
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    
    // Admin only - kirim notifikasi
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
        Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    });
    
    // API endpoints
    Route::get('/api/notifications/unread-count', [NotificationController::class, 'getUnreadCount']);
    Route::get('/api/notifications/latest', [NotificationController::class, 'getLatest']);
});
