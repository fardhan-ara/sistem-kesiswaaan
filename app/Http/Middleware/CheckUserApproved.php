<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Admin tidak perlu approval
        if ($user && $user->role === 'admin') {
            return $next($request);
        }
        
        // Untuk role lain, cek status approval
        if ($user && in_array($user->role, ['siswa', 'ortu']) && $user->status !== 'approved') {
            // Redirect ke dashboard dengan pesan
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akun Anda menunggu persetujuan admin.'], 403);
            }
            
            return redirect()->route('dashboard');
        }
        
        return $next($request);
    }
}
