<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            Log::warning('RoleMiddleware: User not authenticated');
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = Auth::user();
        
        Log::info('RoleMiddleware check', [
            'user' => $user->nama,
            'user_role' => $user->role,
            'is_wali_kelas' => $user->is_wali_kelas,
            'required_roles' => $roles,
            'url' => $request->url()
        ]);
        
        // Check role utama atau wali_kelas
        $hasAccess = in_array($user->role, $roles) || 
                     ($user->is_wali_kelas && in_array('wali_kelas', $roles));
        
        if (!$hasAccess) {
            Log::warning('RoleMiddleware: Access denied', [
                'user' => $user->nama,
                'user_role' => $user->role,
                'is_wali_kelas' => $user->is_wali_kelas,
                'required_roles' => $roles
            ]);
            
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}