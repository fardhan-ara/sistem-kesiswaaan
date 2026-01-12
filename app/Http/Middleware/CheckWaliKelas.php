<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckWaliKelas
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->is_wali_kelas) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Anda bukan wali kelas.');
        }

        return $next($request);
    }
}
