<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\TahunAjaran;

class IsHomeroomTeacher
{
    public function handle(Request $request, Closure $next, $kelasId = null)
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Jika kelasId diberikan sebagai parameter middleware
        if ($kelasId) {
            if (!$user->isHomeroomTeacher($kelasId)) {
                abort(403, 'Anda bukan wali kelas untuk kelas ini.');
            }
        } else {
            // Cek dari route parameter atau request
            $kelasId = $request->route('kelas_id') ?? $request->route('kelas') ?? $request->input('kelas_id');
            
            if ($kelasId && !$user->isHomeroomTeacher($kelasId)) {
                abort(403, 'Anda bukan wali kelas untuk kelas ini.');
            } elseif (!$kelasId && !$user->isHomeroomTeacher()) {
                abort(403, 'Anda bukan wali kelas.');
            }
        }

        return $next($request);
    }
}