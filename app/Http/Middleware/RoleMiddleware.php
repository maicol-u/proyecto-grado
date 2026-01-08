<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
       if (!auth()->check()) {
            abort(403, 'No autenticado');
        }

        $userRole = auth()->user()->role;

        // Convertir roles string a enum
        $allowedRoles = array_map(
            fn ($role) => UserRole::from($role),
            $roles
        );

        if (!in_array($userRole, $allowedRoles, true)) {
            abort(403, 'No autorizado');
        }

        return $next($request);
    }
}
