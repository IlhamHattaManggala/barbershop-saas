<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CashierMiddleware
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

        // STRICT EXCLUSIVE CASHIER ACCESS: Only cashier role can access /cashier/* routes
        if ($user->role !== 'cashier') {
            if ($user->isSuperAdmin()) {
                return redirect()->route('superadmin.dashboard');
            }
            if ($user->role === 'owner') {
                return redirect()->route('owner.dashboard');
            }
            if ($user->role === 'barber') {
                return redirect()->route('barber.dashboard');
            }

            abort(403, 'Akses Ditolak! Hanya Kasir POS yang berhak mengakses area ini.');
        }

        return $next($request);
    }
}
