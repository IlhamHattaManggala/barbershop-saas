<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BarberMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // STRICT EXCLUSIVE BARBER ACCESS: Only barber role can access /barber/* routes
        if ($user->role !== 'barber') {
            if ($user->isSuperAdmin()) {
                return redirect()->route('superadmin.dashboard');
            }
            if ($user->role === 'owner') {
                return redirect()->route('owner.dashboard');
            }
            if ($user->role === 'cashier') {
                return redirect()->route('cashier.dashboard');
            }

            abort(403, 'Akses Ditolak! Hanya Barber Specialist yang berhak mengakses area ini.');
        }

        return $next($request);
    }
}
