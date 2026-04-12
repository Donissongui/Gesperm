<?php

namespace App\Http\Controllers\params;

use App\Http\Controllers\Controller;
use App\Models\Motif;
use Illuminate\Http\Request;

class MotifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Motif::query();

        if ($request->filled('search')) {
            $query->where('libelle_motif', 'like', '%' . $request->search . '%');
        }

        $motifs = $query->paginate(5)->appends($request->query());

        return view('motifs.index', compact('motifs'));
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
            'libelle_motif' => 'required|max:100|unique:motifs,libelle_motif'
        ]);

        Motif::create([
            'libelle_motif' => ucwords(strtolower($request->libelle_motif))
        ]);

        return redirect()->route('parametre.motifs.index')
            ->with('success', 'Motif ajouté avec succès.');
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
            'libelle_motif' => 'required|max:100|unique:motifs,libelle_motif,' . $id . ',id_motif'
        ]);

        Motif::where('id_motif', $id)->update([
            'libelle_motif' => ucwords(strtolower($request->libelle_motif))
        ]);

        return redirect()->back()
            ->with('success', 'Motif modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Motif::where('id_motif', $id)->delete();

        return redirect()->back()
            ->with('success', 'Motif supprimé avec succès.');
    }
}
