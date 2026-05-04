<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Composer pour toutes les vues utilisant le layout principal
        View::composer('layouts.admin', function ($view) {
            $today = Carbon::today();

            // Mettre à jour automatiquement le statut des permissions expirées
            DB::table('posseders')
                ->whereDate('date_fin', '<', $today)   // date de fin dépassée
                ->where('arrive', 0)                   // non encore arrivée
                ->where('statut', 'en cours')          // seulement les permissions en cours
                ->update(['statut' => 'expirée']);

            // Récupérer uniquement les permissions expirées
            $idService = auth()->user()->personnel->id_service ?? null;
            $userType = auth()->user()->type;

            $expiredPermissions = DB::table('posseders')
                ->join('permissions', 'permissions.id_permission', '=', 'posseders.id_permission')
                ->join('personnels', 'personnels.id_personnel', '=', 'posseders.id_personnel')
                ->where('posseders.statut', 'expirée')
                ->when($userType !== 'admin', function ($query) use ($idService) {
                    return $query->where('personnels.id_service', $idService);
                })
                ->select(
                    'permissions.id_permission',
                    'permissions.type_permission',
                    'personnels.nom',
                    'personnels.prenom',
                    'posseders.date_fin',
                    'posseders.statut'
                )
                ->orderBy('posseders.date_fin', 'desc')
                ->get();

            $view->with('expiredPermissions', $expiredPermissions);
        });
    }
}
