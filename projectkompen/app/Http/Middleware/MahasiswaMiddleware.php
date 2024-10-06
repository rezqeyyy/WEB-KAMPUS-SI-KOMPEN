<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;
class MahasiswaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('pmahasiswa')->check() ||
            Auth::guard('pmahasiswa')->user()->role != 'Mahasiswa') {
            return redirect()->route('loginmhsw')->withErrors('Silahkan Login Dahulu... #1');
        }

        if (Auth::guard('pmahasiswa')->check()) {
            $user = Auth::guard('pmahasiswa')->user();
            View::share('nama_user', $user->nama_user);
            View::share('role', $user->role);
        }

        return $next($request);
    }
}
