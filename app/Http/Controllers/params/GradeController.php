<?php

namespace App\Http\Controllers\params;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Grade::query();

        if ($request->filled('search')) {
            $query->where('libelle_grade', 'like', '%' . $request->search . '%');
        }

        $grades = $query->paginate(5)->appends($request->query());

        return view('grades.index', compact('grades'));
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
            'libelle_grade' => 'required|max:100'
        ]);

        // Vérifier si le grade existe déjà
        $exists = Grade::where('libelle_grade', $request->libelle_grade)->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Ce grade existe déjà.');
        }

        Grade::create([
            'libelle_grade' => $request->libelle_grade
        ]);

        return redirect()->back()
            ->with('success', 'Grade ajouté avec succès.');
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
            'libelle_grade' => 'required|max:100'
        ]);

        // Vérifier si un autre grade a le même libelle
        $exists = Grade::where('libelle_grade', $request->libelle_grade)
            ->where('id_grade', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Ce grade existe déjà.');
        }

        Grade::where('id_grade', $id)->update([
            'libelle_grade' => $request->libelle_grade
        ]);

        return redirect()->back()
            ->with('success', 'Grade modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Grade::where('id_grade', $id)->delete();

        return redirect()->back()
            ->with('success', 'Grade supprimé');
    }
}
