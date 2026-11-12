<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan pengguna sudah login.
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Normalisasi role pengguna.
        $userRole = strtolower(trim((string) $user->role));

        // Normalisasi role yang diizinkan.
        $allowedRoles = array_map(
            fn ($role) => strtolower(trim($role)),
            $roles
        );

        // Izinkan pengguna jika rolenya sesuai.
        if (in_array($userRole, $allowedRoles, true)) {
            return $next($request);
        }

        // Admin yang sedang menggunakan Mode Petugas
        // diperbolehkan mengakses halaman khusus Petugas.
        if (
            $userRole === 'admin' &&
            filter_var(session('mode_petugas', false), FILTER_VALIDATE_BOOLEAN) &&
            in_array('petugas', $allowedRoles, true)
        ) {
            return $next($request);
        }

        // Tolak akses jika role tidak sesuai.
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}
