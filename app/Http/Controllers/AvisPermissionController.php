<?php

namespace App\Http\Controllers;

use App\Models\AvisPermission;
use App\Models\Posseder;
use Illuminate\Http\Request;

class AvisPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $avis = AvisPermission::with([
            'permission',
            'personnel',
            'permission.posseders.personnel'
        ])
            ->where('id_personnel', auth()->user()->id_personnel)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('avis.index', compact('avis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'avis' => 'required|in:favorable,défavorable'
        ]);

        $avis = AvisPermission::findOrFail($id);

        $avis->avis = $request->avis;
        $avis->id_personnel = auth()->user()->personnel->id_personnel;
        $avis->save();

        // récupérer la fonction de la personne connectée
        $fonction = auth()->user()->personnel->fonction->nom_fonction;

        // Si c'est le CHEF D'UNITE on ne crée pas d'avis suivant
        if ($fonction == "COMMANDANT CIT") {
            // mise à jour du statut dans la table posseder
            $statut = $avis->avis == 'favorable' ? 'en cours' : 'refusée';

            // mise à jour du statut dans la table posseder
            Posseder::where('id_permission', $avis->id_permission)
                ->update(['statut' => $statut]);

            return redirect()->back()->with('success', 'Avis final enregistré.');
        }

        // sinon créer l'avis suivant
        if ($request->avis == 'favorable' || $request->avis == 'défavorable') {

            $ordreSuivant = $avis->ordre + 1;

            $avisSuivant = AvisPermission::where('id_permission', $avis->id_permission)
                ->where('ordre', $ordreSuivant)
                ->first();

            if (!$avisSuivant) {
                AvisPermission::create([
                    'id_permission' => $avis->id_permission,
                    'avis' => 'en attente',
                    'ordre' => $ordreSuivant
                ]);
            }
        }

        return redirect()->back()->with('success', 'Avis enregistré avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
