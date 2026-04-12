<?php

namespace App\Http\Controllers\params;

use App\Http\Controllers\Controller;
use App\Models\Fonction;
use Illuminate\Http\Request;

class FonctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Fonction::query();

        // Recherche
        if ($request->filled('search')) {
            $query->where('nom_fonction', 'like', '%' . $request->search . '%');
        }

        // Pagination
        $fonctions = $query->orderBy('nom_fonction', 'asc')
            ->paginate(5)
            ->appends($request->query());

        return view('fonctions.index', compact('fonctions'));
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
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'nom_fonction' => 'required|string|max:255|unique:fonctions,nom_fonction'
        ]);

        // Enregistrement
        Fonction::create([
            'nom_fonction' => strtoupper($request->nom_fonction),
        ]);

        // Redirection
        return redirect()
            ->back()
            ->with('success', 'Fonction ajoutée avec succès.');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validation
        $request->validate([
            'nom_fonction' => 'required|string|max:255|unique:fonctions,nom_fonction,' . $id . ',id_fonction'
        ]);

        // Récupérer la fonction
        $fonction = Fonction::findOrFail($id);

        // Mise à jour
        $fonction->update([
            'nom_fonction' => strtoupper($request->nom_fonction),
        ]);

        // Redirection
        return redirect()
            ->back()
            ->with('success', 'Fonction modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Récupérer la fonction
        $fonction = Fonction::findOrFail($id);

        // Supprimer
        $fonction->delete();

        // Redirection
        return redirect()
            ->back()
            ->with('success', 'Fonction supprimée avec succès.');
    }
}
