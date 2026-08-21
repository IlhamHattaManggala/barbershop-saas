<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OwnerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // STRICT EXCLUSIVE OWNER ACCESS: Only owner role can access /owner/* routes
        if ($user->role !== 'owner') {
            if ($user->isSuperAdmin()) {
                return redirect()->route('superadmin.dashboard');
            }
            if ($user->role === 'cashier') {
                return redirect()->route('cashier.dashboard');
            }
            if ($user->role === 'barber') {
                return redirect()->route('barber.dashboard');
            }

            abort(403, 'Akses Ditolak! Hanya Owner Barbershop yang berhak mengakses area ini.');
        }

        return $next($request);
    }
}
