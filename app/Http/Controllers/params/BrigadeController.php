<?php

namespace App\Http\Controllers\params;

use App\Http\Controllers\Controller;
use App\Models\Brigade;
use Illuminate\Http\Request;

class BrigadeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Brigade::query();

        if ($request->filled('search')) {
            $query->where('nom_brigade', 'like', '%' . $request->search . '%');
        }

        $brigades = $query->paginate(5)->appends($request->query());

        return view('brigades.index', compact('brigades'));
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
            'nom_brigade' => 'required|string|max:50'
        ]);

        // Vérifier si la brigade existe déjà
        $exists = Brigade::where('nom_brigade', $request->nom_brigade)->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Cette brigade existe déjà.');
        }

        Brigade::create([
            'nom_brigade' => $request->nom_brigade
        ]);

        return redirect()->back()
            ->with('success', 'Brigade ajoutée avec succès.');
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
    public function update(Request $request, $id_brigade)
    {
        $request->validate([
            'nom_brigade' => 'required|string|max:50'
        ]);

        // Vérifier si une autre brigade a déjà ce nom
        $exists = Brigade::where('nom_brigade', $request->nom_brigade)
            ->where('id_brigade', '!=', $id_brigade)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Cette brigade existe déjà.');
        }

        Brigade::where('id_brigade', $id_brigade)->update([
            'nom_brigade' => $request->nom_brigade
        ]);

        return redirect()->back()
            ->with('success', 'Brigade modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_brigade)
    {
        Brigade::where('id_brigade', $id_brigade)->delete();

        return redirect()->back();
    }
}
