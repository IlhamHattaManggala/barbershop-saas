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

        // STRICT OWNER PROTECTION: Only owner role & SuperAdmin can access /owner/* management routes
        if ($user->role !== 'owner' && ! $user->isSuperAdmin()) {
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
