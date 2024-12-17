<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Cek apakah user sudah login dengan memeriksa session
        // Jika belum login, redirect ke halaman login
        if (!session()->has('user')) {
            return redirect('/SignIn-SignUp');
        }

        // Ambil data user dari session untuk cek rolenya
        $user = session()->get('user');

        // Cek apakah role user ada dalam array roles yang diizinkan
        // Jika role user sesuai, lanjutkan request
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Jika role tidak sesuai, redirect ke homepage dengan pesan error
        return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini');
    }
}
