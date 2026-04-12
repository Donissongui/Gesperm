<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSingleSession
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        $sessionId = session()->getId();

        /*
        |--------------------------------------------------------------------------
        | Vérifier la double connexion
        |--------------------------------------------------------------------------
        */
        if ($user->session_id && $user->session_id !== $sessionId) {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login')
                ->withErrors('Ce compte est déjà connecté sur un autre appareil.');
        }

        return $next($request);
    }
}
