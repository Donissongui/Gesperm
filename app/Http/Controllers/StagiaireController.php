<?php

namespace App\Http\Controllers;

use App\Models\Brigade;
use App\Models\Grade;
use App\Models\Personnel;
use Illuminate\Http\Request;

class StagiaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Personnel::with(['grade', 'brigade'])
            ->where('type_personnel', 'stagiaire');

        /* ======================
       SEARCH TEXT
    ====================== */
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        /* ======================
       FILTRE BRIGADE
    ====================== */
        if ($request->filled('brigade')) {
            $query->where('id_brigade', $request->brigade);
        }

        $personnels = $query->paginate(10)->withQueryString();

        /* Liste brigades pour combo */
        $brigades = Brigade::orderBy('nom_brigade')->get();

        return view('stagiaires.index', compact('personnels', 'brigades'));
    }

    public function indexx(Request $request)
    {
        $query = Personnel::with(['grade', 'brigade'])
            ->where('type_personnel', 'stagiaire'); // filtre uniquement les stagiaires

        if ($request->filled('search')) { // 👈 mieux que juste $request->search
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        $personnels = $query->paginate(10)->withQueryString(); // 👈 garde la query dans la pagination

        return view('stagiaires.index', compact('personnels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $grades = Grade::whereHas('categories', function ($q) {
            $q->where('n_order', 3);
        })->get();
        $brigades = Brigade::all();

        return view('stagiaires.create', compact('grades', 'brigades'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ✅ Validation
        $request->validate([
            'matricule'       => 'required|string|max:50|unique:personnels,matricule',
            'nom'             => 'required|string|max:100',
            'prenom'          => 'required|string|max:100',
            'type_personnel'  => 'required|in:militaire,stagiaire',
            'id_grade'        => 'required|exists:grades,id_grade',
            'id_brigade' => 'nullable|exists:brigades,id_brigade',
        ], [
            'matricule.unique' => 'Ce matricule existe déjà. Veuillez en saisir un autre.'
        ]);

        // ✅ Création
        Personnel::create([
            'matricule'      => $request->matricule,
            'nom'    => strtoupper($request->nom),
            'prenom' => strtoupper($request->prenom),
            'type_personnel' => $request->type_personnel,
            'id_grade'       => $request->id_grade,
            'id_brigade' => $request->input('id_brigade', null),
        ]);

        // ✅ Redirection
        return redirect()
            ->route('stagiaires.index')
            ->with('success', 'Personnel ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id_personnel)
    {
        $personnel = Personnel::with(['grade', 'brigade'])
            ->where('id_personnel', $id_personnel)
            ->firstOrFail();

        return view('stagiaires.show', compact('personnel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $personnel = Personnel::findOrFail($id);

        $grades = Grade::all();
        $brigades = Brigade::all();

        return view('stagiaires.edit', compact('personnel', 'grades', 'brigades'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_personnel)
    {
        $personnel = Personnel::findOrFail($id_personnel);

        // ✅ Validation
        $request->validate([
            'matricule'       => 'required|string|max:50|unique:personnels,matricule,' . $id_personnel . ',id_personnel',
            'nom'            => 'required|string|max:100',
            'prenom'         => 'required|string|max:100',
            'type_personnel'  => 'required|in:militaire,stagiaire',
            'id_grade'       => 'required|exists:grades,id_grade',
            'id_brigade' => 'nullable|exists:brigades,id_brigade',
        ], [
            'matricule.unique' => 'Ce matricule existe déjà. Veuillez en saisir un autre.'
        ]);

        // ✅ Mise à jour
        $personnel->update([
            'matricule'       => strtoupper($request->matricule),
            'nom'    => strtoupper($request->nom),
            'prenom' => strtoupper($request->prenom),
            'type_personnel' => $request->type_personnel,
            'id_grade'       => $request->id_grade,
            'id_brigade' => $request->input('id_brigade', null),
        ]);

        return redirect()
            ->route('stagiaires.index')
            ->with('success', 'Stagiaire mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
