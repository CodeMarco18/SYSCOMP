<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        if ($userRole === 'Administrador' || in_array($userRole, $roles)) {
            return $next($request);
        }

        return abort(403, 'No tienes permisos suficientes para acceder a este módulo.');
    }
}
