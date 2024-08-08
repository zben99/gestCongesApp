<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check() || !$request->user()->hasRole($role)) {
            // Si l'utilisateur n'est pas authentifié ou n'a pas le rôle requis, rediriger
            return redirect('/home')->with('error', 'Vous n\'avez pas accès à cette section.');
        }

        return $next($request);
    }
}
