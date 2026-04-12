<?php

namespace App\Http\Controllers\params;

use App\Http\Controllers\Controller;
use App\Models\Ville;
use Illuminate\Http\Request;

class VilleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Ville::query();

        if ($request->filled('search')) {
            $query->where('nom_ville', 'like', '%' . $request->search . '%');
        }

        $villes = $query->paginate(5)->appends($request->query());

        return view('villes.index', compact('villes'));
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
            'nom_ville' => 'required|max:100|unique:villes,nom_ville'
        ]);

        Ville::create([
            'nom_ville' => ucwords(strtolower($request->nom_ville))
        ]);

        return redirect()->route('parametre.villes.index')
            ->with('success', 'Ville ajoutée avec succès.');
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
            'nom_ville' => 'required|max:100|unique:villes,nom_ville,' . $id . ',id_ville'
        ]);

        Ville::where('id_ville', $id)->update([
            'nom_ville' => ucwords(strtolower($request->nom_ville))
        ]);

        return redirect()->back()
            ->with('success', 'Ville modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Ville::where('id_ville', $id)->delete();

        return redirect()->back()
            ->with('success', 'Ville supprimée avec succès.');
    }
}
