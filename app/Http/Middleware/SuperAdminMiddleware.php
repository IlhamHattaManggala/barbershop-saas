<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        if (! auth()->user()->isSuperAdmin()) {
            abort(403, 'Akses Ditolak! Halaman ini hanya dapat diakses oleh SuperAdmin Platform SaaS.');
        }

        return $next($request);
    }
}
