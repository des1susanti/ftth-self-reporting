<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleCheck
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Memeriksa apakah user sudah login dan memiliki role yang diizinkan
        if (auth()->check() && in_array(auth()->user()->role, $roles)) {
            return $next($request);
        }

        // Jika tidak sesuai, tampilkan error 403
        abort(403, 'Akses Ditolak. Halaman ini khusus Admin atau Manager.');
    }
}