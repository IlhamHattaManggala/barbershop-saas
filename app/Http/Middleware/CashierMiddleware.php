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

        // STRICT CASHIER PROTECTION: Only cashier role (and owner if acting as cashier) can access /cashier/* routes
        if ($user->role !== 'cashier' && $user->role !== 'owner' && ! $user->isSuperAdmin()) {
            if ($user->role === 'barber') {
                return redirect()->route('barber.dashboard');
            }

            abort(403, 'Akses Ditolak! Hanya Kasir POS yang berhak mengakses area ini.');
        }

        return $next($request);
    }
}
