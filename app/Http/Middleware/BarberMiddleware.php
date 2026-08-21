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

        // STRICT BARBER PROTECTION: Only barber role (and owner) can access /barber/* workstation routes
        if ($user->role !== 'barber' && $user->role !== 'owner' && ! $user->isSuperAdmin()) {
            if ($user->role === 'cashier') {
                return redirect()->route('cashier.dashboard');
            }

            abort(403, 'Akses Ditolak! Hanya Barber Specialist yang berhak mengakses workstation ini.');
        }

        return $next($request);
    }
}
