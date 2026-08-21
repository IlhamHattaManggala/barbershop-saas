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

        $user = auth()->user();

        // STRICT EXCLUSIVE SUPERADMIN ACCESS: Only superadmin role can access /superadmin/* routes
        if (! $user->isSuperAdmin()) {
            if ($user->role === 'owner') {
                return redirect()->route('owner.dashboard');
            }
            if ($user->role === 'cashier') {
                return redirect()->route('cashier.dashboard');
            }
            if ($user->role === 'barber') {
                return redirect()->route('barber.dashboard');
            }

            abort(403, 'Akses Ditolak! Halaman ini hanya dapat diakses oleh SuperAdmin Platform SaaS.');
        }

        return $next($request);
    }
}
