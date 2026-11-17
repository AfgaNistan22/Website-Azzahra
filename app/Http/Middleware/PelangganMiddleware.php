<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PelangganMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if (!$request->user()->isPelanggan()) {
            abort(403, 'Halaman ini khusus pelanggan.');
        }

        return $next($request);
    }
}
