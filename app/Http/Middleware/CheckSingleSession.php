<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckSingleSession
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        $currentSession = session()->getId();

        $lifetime = config('session.lifetime') * 60;

        /*
        |----------------------------------------
        | Vérifier autre session active
        |----------------------------------------
        */

        $otherSession = DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('id', '!=', $currentSession)
            ->where('last_activity', '>', time() - $lifetime)
            ->exists();

        if ($otherSession) {

            Auth::logout();

            return redirect('/login')
                ->withErrors([
                    'login' => 'Ce compte est déjà connecté ailleurs.'
                ]);
        }

        return $next($request);
    }
}
