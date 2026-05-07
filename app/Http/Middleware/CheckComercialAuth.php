<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckComercialAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! session()->has('comercial_id')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'No autenticado'], 401);
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}
