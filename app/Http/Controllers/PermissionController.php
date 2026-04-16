<?php

namespace App\Http\Controllers;

use App\Models\AvisPermission;
use App\Models\Motif;
use App\Models\Permission;
use App\Models\Personnel;
use App\Models\Posseder;
use App\Models\Ville;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('permissions.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->type;

        if (!in_array($type, ['militaire', 'stagiaire'])) {
            abort(404);
        }

        $query = Personnel::where('type_personnel', $type);

        if (auth()->user()->type != 'admin') {
            $query->where('id_service', auth()->user()->personnel->id_service);
        }

        $personnels = $query->orderBy('nom')->get();

        // $personnels = Personnel::where('type_personnel', $type)
        //     ->orderBy('nom')
        //     ->get();

        $motifs = Motif::all(); // charger tous les motifs
        $villes = Ville::all();

        return view('permissions.create', compact('type', 'personnels', 'motifs', 'villes'));
    }

    public function liste(Request $request)
    {
        $type = $request->type;

        if (!in_array($type, ['militaire', 'stagiaire'])) {
            abort(404);
        }

        $query = Permission::where('type_permission', $type)
            ->with('posseders.motif', 'posseders.personnel.service');

        if (auth()->user()->type != 'admin') {

            $query->whereHas('posseders.personnel', function ($q) {
                $q->where('id_service', auth()->user()->personnel->id_service);
            });
        }

        // 🔎 Recherche par motif
        if ($request->filled('motif')) {
            $query->whereHas('posseders', function ($q) use ($request) {
                $q->where('id_motif', $request->motif);
            });
        }

        // 🔎 Recherche par date
        if ($request->filled('date_debut') || $request->filled('date_fin')) {
            $query->whereHas('posseders', function ($q) use ($request) {

                // 🔹 Cas date_debut ET date_fin → intersection
                if ($request->filled('date_debut') && $request->filled('date_fin')) {
                    $q->whereDate('date_début', '<=', $request->date_fin)
                        ->where(function ($q2) use ($request) {
                            $q2->whereDate('date_fin', '>=', $request->date_debut)
                                ->orWhereNull('date_fin');
                        });
                }
                // 🔹 Cas seulement date_debut
                elseif ($request->filled('date_debut')) {
                    $q->whereDate('date_début', '>=', $request->date_debut);
                }
                // 🔹 Cas seulement date_fin
                elseif ($request->filled('date_fin')) {
                    $q->where(function ($q2) use ($request) {
                        $q2->whereDate('date_fin', '<=', $request->date_fin)
                            ->orWhereNull('date_fin');
                    });
                }
            });
        }

        $permissions = $query
            ->orderByDesc('created_at')
            ->paginate(10)
            ->appends($request->query());

        $motifs = Motif::all();

        return view('permissions.liste', compact('type', 'permissions', 'motifs'));
    }

    public function listeOld(Request $request)
    {
        $type = $request->type;

        if (!in_array($type, ['militaire', 'stagiaire'])) {
            abort(404);
        }

        $permissions = Permission::where('type_permission', $type)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('permissions.liste', compact('type', 'permissions'));
    }

    public function detail($id)
    {
        $posseders = Posseder::where('id_permission', $id)->get();


        return view('permissions.detail', compact('posseders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $type = $request->type_permission;

        $request->validate([
            'type_permission' => 'required|string',
            'date_debut'      => 'required|date',
            'date_fin'        => 'nullable|date|after_or_equal:date_debut',
            'personnels'      => 'required|array|min:1',
            'id_motif'        => 'nullable|exists:motifs,id_motif',
            'id_ville'        => 'nullable|exists:villes,id_ville',
        ]);

        DB::beginTransaction();

        try {
            // 1️⃣ Création de la permission unique
            $permission = Permission::create([
                'type_permission' => $request->type_permission,
                'tranche'         => 'standard',
            ]);

            $motif       = $request->id_motif;
            $ville       = $request->id_ville;
            $date_debut  = $request->date_debut;
            $date_fin    = $request->date_fin;

            // 2️⃣ Boucle sur chaque personnel sélectionné
            foreach ($request->personnels as $personnel_id) {
                $personnel_id = (int) $personnel_id;
                if ($personnel_id <= 0) continue;

                // Vérification anti-doublon
                if (Posseder::where('id_personnel', $personnel_id)
                    ->where('date_début', $date_debut)
                    ->exists()
                ) {
                    continue;
                }

                // Création du Posseder
                Posseder::create([
                    'id_personnel'  => $personnel_id,
                    'id_permission' => $permission->id_permission,
                    'date_début'    => $date_debut,
                    'date_fin'      => $date_fin,
                    'id_motif'      => $motif,
                    'id_ville'      => $ville,
                    'statut'        => 'en attente',
                    'arrive'        => 0,
                ]);
            }

            // 3️⃣ Création du premier avis global pour cette permission
            AvisPermission::create([
                'id_permission' => $permission->id_permission,
                'avis'          => 'en attente', // ou valeur par défaut
                'id_personnel'  => null,        // avis global, pas lié à un personnel
                'ordre'         => 1,
            ]);

            DB::commit();

            return redirect()->route('permissions.liste', ['type' => $type])
                ->with('success', 'Permission créée avec succès pour ' . count($request->personnels) . ' personnel(s).');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur création permission: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_permission)
    {
        // Récupérer la permission avec les personnels associés
        $permission = Permission::findOrFail($id_permission);
        $posseders = Posseder::where('id_permission', $id_permission)->get();

        // Charger toutes les villes et tous les motifs
        $villes = Ville::all();
        $motifs = Motif::all();

        return view('permissions.edit', compact('permission', 'villes', 'motifs', 'posseders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'personnels' => 'required|array',
            'personnels.*.destination' => 'nullable|exists:villes,id_ville',
            'personnels.*.motif' => 'nullable|exists:motifs,id_motif',
        ]);

        $permission = Permission::findOrFail($id);

        foreach ($request->personnels as $possederId => $data) {

            $posseder = Posseder::where('id_posseder', $possederId)
                ->where('id_permission', $permission->id_permission)
                ->first();

            if ($posseder) {
                $posseder->update([
                    'id_ville' => $data['destination'] ?? null,
                    'id_motif' => $data['motif'] ?? null,
                ]);
            }
        }

        return redirect()->route('permissions.liste', [
            'type' => $permission->type_permission
        ])->with('success', 'Destinations et motifs mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function historique()
    {
        $query = Posseder::with(['personnel', 'permission'])
            ->where('statut', 'terminée')   // <- filtre sur le statut
            ->orderByDesc('date_début');

        if (auth()->user()->type != 'admin') {
            $query->whereHas('personnel', function ($q) {
                $q->where('id_service', auth()->user()->personnel->id_service);
            });
        }

        $permissions = $query->get();

        return view('permissions.historique', compact('permissions'));
    }

    public function encours()
    {
        // $permissions = Posseder::with(['personnel', 'permission'])
        //     ->whereIn('statut', ['en cours', 'expirée'])
        //     ->orderByDesc('date_début')
        //     ->get();

        $query = Posseder::with(['personnel', 'permission'])
            ->whereIn('statut', ['en cours', 'expirée'])
            ->orderByDesc('date_début');

        if (auth()->user()->type != 'admin') {
            $query->whereHas('personnel', function ($q) {
                $q->where('id_service', auth()->user()->personnel->id_service);
            });
        }

        $permissions = $query->get();

        $villes = Ville::all();

        return view('permissions.encours', compact('permissions', 'villes'));
    }

    public function enattente()
    {
        $user = auth()->user();

        // Détermine l'ordre attendu pour l'utilisateur
        $ordre = match ($user->type) {
            // 'CSTAGE', 'CGCS', 'GMI' => 1,
            'CSTAGE'=> 1,
            'DFORMATION' => 2,
            'CGS' => 3,
            'CCIT' => 4,
            default => null,
        };


        if ($ordre) {
            // On récupère les avisPermissions correspondant à cet ordre et en attente
            $avisPermissions = AvisPermission::with(['permission', 'permission.posseders', 'permission.posseders.personnel'])
                ->where('ordre', $ordre)
                ->where(function ($q) {
                    $q->whereNull('avis')
                        ->orWhere('avis', 'en attente');
                })
                ->orderByDesc('created_at')
                ->get();
        } else {
            // Pour admin ou autres, tout récupérer
            $avisPermissions = AvisPermission::with(['permission', 'permission.posseders', 'permission.posseders.personnel'])
                ->orderByDesc('created_at')
                ->get();
        }

        return view('permissions.enattente', compact('avisPermissions'));
    }

    public function enattentee()
    {
        $user = auth()->user();

        // On définit l'ordre attendu en fonction du type d'avis
        $ordre = match ($user->type) {
            'avis1' => 1,
            'avis2' => 2,
            'avis3' => 3,
            default => null, // pour admin, manager, user classique
        };

        if ($ordre) {
            // On récupère uniquement les permissions où l'avis correspondant est attendu
            $permissions = Permission::whereHas('avisPermissions', function ($q) use ($ordre) {
                $q->where('ordre', $ordre);
            })
                ->with(['avisPermissions', 'posseders', 'posseders.personnel'])
                ->orderBy('created_at', 'desc') // du plus récent au plus ancien
                ->get();
        } else {
            // Pour admin, manager ou user classique, tout afficher
            $permissions = Permission::with(['avisPermissions', 'posseders', 'posseders.personnel'])->get();
        }

        return view('permissions.enattente', compact('permissions'));
    }

    public function arrive(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun id reçu.'
            ], 400);
        }

        try {
            // Mettre à jour uniquement les lignes cochées
            $updated = Posseder::whereIn('id_posseder', $ids)
                ->update([
                    'statut' => 'terminée',
                    'arrive' => 1
                ]);

            return response()->json([
                'success' => true,
                'updated' => $updated
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
