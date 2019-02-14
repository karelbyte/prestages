<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class InactiveUsersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // verifica si el usuario autenticado esta activo
        if (!$request->user()->isActive()) {
            // elimina la session de ese usuario activos
            Auth::logout();

            return redirect()->route('auth.login')->with([
                'message.login.error' => 'Su cuenta esta inactiva, consulte al administrador.'
            ]);
        }

        return $next($request);

    }
}
