<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        $lifetime = config('session.lifetime') * 60;

        /*
    |---------------------------------------------------
    | Vérifier session ACTIVE seulement
    |---------------------------------------------------
    */

        $sessionExists = DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('last_activity', '>', time() - $lifetime)
            ->exists();

        if ($sessionExists) {

            Auth::logout();

            return back()->withErrors([
                'login' => 'Ce compte est déjà connecté sur un autre appareil.'
            ]);
        }

        /*
    |---------------------------------------------------
    | Associer session utilisateur
    |---------------------------------------------------
    */

        DB::table('sessions')
            ->where('id', session()->getId())
            ->update([
                'user_id' => $user->id
            ]);

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        DB::table('sessions')
            ->where('id', session()->getId())
            ->delete();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
