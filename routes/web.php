<?php

use App\Http\Controllers\AvisPermissionController;
use App\Http\Controllers\params\BrigadeController;
use App\Http\Controllers\params\CategorieController;
use App\Http\Controllers\params\GradeController;
use App\Http\Controllers\params\MotifController;
use App\Http\Controllers\params\UtilisateurController;
use App\Http\Controllers\params\ServiceController;
use App\Http\Controllers\params\FonctionController;
use App\Http\Controllers\params\VilleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StagiaireController;
use App\Models\Permission;
use App\Models\Personnel;
use App\Models\Posseder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Page principale
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Routes sécurisées
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    | Dashboard
    */
    Route::get('/dashboard', function () {

        // Si ce n'est pas admin → voir les avis en attente
        if (auth()->check() && in_array(auth()->user()->type, ['CCIT', 'CGS', 'CGCS', 'CGMI', 'CSTAGE', 'DFORMATION'])) {
            return redirect()->route('permissions.enattente');
        }

        if (auth()->check() && in_array(auth()->user()->type, ['SGCS', 'SGS', 'SGMI'])) {
            return redirect()->route('personnels.index');
        }

        $userCount = User::count();
        $militaireCount = Personnel::where('type_personnel', 'militaire')->count();
        $stagiaireCount = Personnel::where('type_personnel', 'stagiaire')->count();
        $permissionCount = Posseder::where('statut', 'en cours')->count();
        $habilitationCount  = Permission::count();

        // Activité récente
        $activites = collect();

        // Derniers utilisateurs créés
        $users = User::latest()->take(3)->get();
        $users->each(function ($u) use ($activites) {
            $activites->push([
                'message' => "Nouvel utilisateur créé : {$u->name}",
                'color' => 'bg-green-500',
                'created_at' => $u->created_at
            ]);
        });

        // Derniers personnels ajoutés
        $personnels = Personnel::latest()->take(3)->get();
        $personnels->each(function ($p) use ($activites) {
            $activites->push([
                'message' => "Personnel ajouté : {$p->nom} ({$p->type_personnel})",
                'color' => 'bg-blue-500',
                'created_at' => $p->created_at
            ]);
        });

        // Dernières permissions en cours
        $permissions = Permission::latest('created_at')->take(3)->get();

        $permissions->each(function ($perm) use ($activites) {
            $activites->push([
                'message' => "Permission {$perm->type_permission} ({$perm->tranche}) créée",
                'color' => 'bg-yellow-500',
                'created_at' => $perm->created_at
            ]);
        });

        // Trier toutes les activités par date décroissante et limiter à 5
        $activites = $activites->sortByDesc('created_at')->take(10);

        return view('dashboard', [
            'user' => $userCount,
            'militaire' => $militaireCount,
            'stagiaire' => $stagiaireCount,
            'permission' => $permissionCount,
            'habilitation' => $habilitationCount,
            'activites' => $activites,
        ]);
    })->name('dashboard');


    /*
    | Profile
    */
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | PERSONNELS
    |--------------------------------------------------------------------------
    */

    Route::get('/personnels', [PersonnelController::class, 'index'])
        ->name('personnels.index');

    Route::get('/personnels/create', [PersonnelController::class, 'create'])
        ->name('personnels.create');

    Route::post('/personnels', [PersonnelController::class, 'store'])
        ->name('personnels.store');

    Route::get('/personnels/{id_personnel}', [PersonnelController::class, 'show'])
        ->name('personnels.show');

    Route::get('/personnels/{id_personnel}/edit', [PersonnelController::class, 'edit'])
        ->name('personnels.edit');

    Route::put('/personnels/{id_personnel}', [PersonnelController::class, 'update'])
        ->name('personnels.update');

    /*
    |--------------------------------------------------------------------------
    | STAGIAIRES
    |--------------------------------------------------------------------------
    */

    Route::get('/stagiaires', [StagiaireController::class, 'index'])
        ->name('stagiaires.index');

    Route::get('/stagiaires/create', [StagiaireController::class, 'create'])
        ->name('stagiaires.create');

    Route::post('/stagiaires/store', [StagiaireController::class, 'store'])
        ->name('stagiaires.store');

    Route::get('/stagiaires/{id_personnel}', [StagiaireController::class, 'show'])
        ->name('stagiaires.show');

    Route::get('/stagiaires/{id_personnel}/edit', [StagiaireController::class, 'edit'])
        ->name('stagiaires.edit');

    Route::put('/stagiaires/{id_personnel}', [StagiaireController::class, 'update'])
        ->name('stagiaires.update');

    /*
    |--------------------------------------------------------------------------
    | PERMISSIONS
    |--------------------------------------------------------------------------
    */

    Route::get('/permissions', [PermissionController::class, 'index'])
        ->name('permissions.index');

    Route::get('/permissions/historique', [PermissionController::class, 'historique'])
        ->name('permissions.historique');

    Route::get('/permissions/encours', [PermissionController::class, 'encours'])
        ->name('permissions.encours');

    Route::get('/permissions/enattente', [PermissionController::class, 'enattente'])
        ->name('permissions.enattente');

    Route::get('/permissions/liste', [PermissionController::class, 'liste'])
        ->name('permissions.liste');

    Route::get('/permissions/detail/{id}', [PermissionController::class, 'detail'])
        ->name('permissions.detail');

    Route::get('/permissions/create', [PermissionController::class, 'create'])
        ->name('permissions.create');

    Route::post('/permissions/store', [PermissionController::class, 'store'])
        ->name('permissions.store');

    Route::get('/permissions/{id_permission}', [PermissionController::class, 'show'])
        ->name('permissions.show');

    Route::get('/permissions/{id_permission}/edit', [PermissionController::class, 'edit'])
        ->name('permissions.edit');

    Route::put('/permissions/{id_permission}', [PermissionController::class, 'update'])
        ->name('permissions.update');

    Route::post('/permissions/arrive', [PermissionController::class, 'arrive'])
        ->name('permissions.arrive');
    /*
    |--------------------------------------------------------------------------
    | AVIS
    |--------------------------------------------------------------------------
    */

    Route::get('/avis', [AvisPermissionController::class, 'index'])->name('avis.index');
    Route::put('/avis/{id}/update', [AvisPermissionController::class, 'update'])->name('avis.update');

    /*
    |--------------------------------------------------------------------------
    | PARAMETRES
    |--------------------------------------------------------------------------
    */

    Route::prefix('parametres')->group(function () {

        Route::get('/', function () {
            return view('parametre');
        })->name('parametre.index');


        /*
        | Grades
        */
        Route::get('/grades', [GradeController::class, 'index'])
            ->name('parametre.grades.index');

        Route::post('/grades', [GradeController::class, 'store'])
            ->name('parametre.grades.store');

        Route::put('/grades/{id_grade}', [GradeController::class, 'update'])
            ->name('parametre.grades.update');

        Route::delete('/grades/{id_grade}', [GradeController::class, 'destroy'])
            ->name('parametre.grades.destroy');

        /*
        | Brigades
        */
        Route::get('/brigades', [BrigadeController::class, 'index'])
            ->name('parametre.brigades.index');

        Route::post('/brigades', [BrigadeController::class, 'store'])
            ->name('parametre.brigades.store');

        Route::put('/brigades/{id_brigade}', [BrigadeController::class, 'update'])
            ->name('parametre.brigades.update');

        Route::delete('/brigades/{id_brigade}', [BrigadeController::class, 'destroy'])
            ->name('parametre.brigades.destroy');

        /*
        | Services
        */
        Route::get('/services', [ServiceController::class, 'index'])
            ->name('parametre.services.index');

        Route::post('/services', [ServiceController::class, 'store'])
            ->name('parametre.services.store');

        Route::put('/services/{id_brigade}', [ServiceController::class, 'update'])
            ->name('parametre.services.update');

        Route::delete('/services/{id_brigade}', [ServiceController::class, 'destroy'])
            ->name('parametre.services  .destroy');

        /*
| Fonctions
*/
        Route::get('/fonctions', [FonctionController::class, 'index'])
            ->name('parametre.fonctions.index');

        Route::post('/fonctions', [FonctionController::class, 'store'])
            ->name('parametre.fonctions.store');

        Route::put('/fonctions/{id_fonction}', [FonctionController::class, 'update'])
            ->name('parametre.fonctions.update');

        Route::delete('/fonctions/{id_fonction}', [FonctionController::class, 'destroy'])
            ->name('parametre.fonctions.destroy');

        /*
        | Categories
        */
        Route::get('/categories', [CategorieController::class, 'index'])
            ->name('parametre.categories.index');

        Route::post('/categories', [CategorieController::class, 'store'])
            ->name('parametre.categories.store');

        Route::put('/categories/{id_categorie}', [CategorieController::class, 'update'])
            ->name('parametre.categories.update');

        Route::delete('/categories/{id_categorie}', [CategorieController::class, 'destroy'])
            ->name('parametre.categories.destroy');

        /*
        | Villes
        */
        Route::get('/villes', [VilleController::class, 'index'])
            ->name('parametre.villes.index');

        Route::post('/villes', [VilleController::class, 'store'])
            ->name('parametre.villes.store');

        Route::put('/villes/{id_ville}', [VilleController::class, 'update'])
            ->name('parametre.villes.update');

        Route::delete('/villes/{id_ville}', [VilleController::class, 'destroy'])
            ->name('parametre.villes.destroy');

        /*
        | Motifs
        */
        Route::get('/motifs', [MotifController::class, 'index'])
            ->name('parametre.motifs.index');

        Route::post('/motifs', [MotifController::class, 'store'])
            ->name('parametre.motifs.store');

        Route::put('/motifs/{id_motif}', [MotifController::class, 'update'])
            ->name('parametre.motifs.update');

        Route::delete('/motifs/{id_motif}', [MotifController::class, 'destroy'])
            ->name('parametre.motifs.destroy');

        /*
        | Utilisateurs
        */
        Route::get('/utilisateurs', [UtilisateurController::class, 'index'])
            ->name('parametre.utilisateurs.index');

        Route::post('/utilisateurs', [UtilisateurController::class, 'store'])
            ->name('parametre.utilisateurs.store');

        Route::get('/utilisateurs/profil/{id}', [UtilisateurController::class, 'show'])
            ->name('parametre.utilisateurs.show');

        Route::put('/utilisateurs/{id}', [UtilisateurController::class, 'update'])
            ->name('parametre.utilisateurs.update');

        Route::delete('/utilisateurs/{id}', [UtilisateurController::class, 'destroy'])
            ->name('parametre.utilisateurs.destroy');
    });
});


/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
