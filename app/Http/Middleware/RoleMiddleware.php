<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect('/login')
                ->with('filament_warning', 'Faça login para continuar.');
        }

        if (! auth()->user()->hasRole('admin')) {
            return redirect('/')
                ->with('filament_warning', 'Sem acesso para esta página.');
        }

        return $next($request);
    }
}
