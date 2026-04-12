<?php

namespace App\Http\Controllers\params;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Grade;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $query = Categorie::query();

        // recherche
        if ($request->filled('search')) {
            $query->where('nom_categorie', 'like', '%' . $request->search . '%');
        }

        // pagination
        $categories = $query->orderBy('n_order', 'asc')
            ->paginate(5)
            ->appends($request->query());

        $grades = Grade::all();

        return view('categories.index', compact('categories', 'grades'));
    }

    public function indexx()
    {
        $categories = Categorie::orderBy('n_order', 'asc')->paginate(5); // 10 par page
        $grades = Grade::all();

        return view('categories.index', compact('categories', 'grades'));
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
        $request->validate([
            'nom_categorie' => 'required|max:50',
            'n_order' => 'required|integer',
            'id_grade' => 'required'
        ]);

        // Vérifier si cette catégorie pour ce grade existe déjà
        $exists = Categorie::where('nom_categorie', $request->nom_categorie)
            ->where('id_grade', $request->id_grade)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Cette catégorie pour ce grade existe déjà !');
        }

        Categorie::create([
            'nom_categorie' => $request->nom_categorie,
            'n_order' => $request->n_order,
            'id_grade' => $request->id_grade
        ]);

        return redirect()->back()->with('success', 'Catégorie ajoutée avec succès !');
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'nom_categorie' => 'required|max:50',
            'n_order' => 'required|integer',
            'id_grade' => 'required'
        ]);

        // Vérifier les doublons
        $exists = Categorie::where('nom_categorie', $request->nom_categorie)
            ->where('id_grade', $request->id_grade)
            ->where('id_categorie', '!=', $id) // exclure la catégorie en cours
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Cette catégorie pour ce grade existe déjà !');
        }

        // Mise à jour
        Categorie::where('id_categorie', $id)->update([
            'nom_categorie' => $request->nom_categorie,
            'n_order' => $request->n_order,
            'id_grade' => $request->id_grade
        ]);

        return redirect()->back()->with('success', 'Catégorie mise à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Categorie::where('id_categorie', $id)->delete();

        return redirect()->back()->with('success', 'Catégorie supprimée avec succès !');
    }
}
