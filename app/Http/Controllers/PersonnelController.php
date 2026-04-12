<?php

namespace App\Http\Controllers;

use App\Models\Fonction;
use App\Models\Grade;
use App\Models\Personnel;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PersonnelController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $query = Personnel::with(['grade', 'service'])
            ->join('categories', 'categories.id_grade', '=', 'personnels.id_grade')
            ->where('type_personnel', 'militaire');

        // 🔎 Recherche globale
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('personnels.nom', 'like', "%{$search}%")
                    ->orWhere('personnels.prenom', 'like', "%{$search}%")
                    ->orWhere('personnels.matricule', 'like', "%{$search}%")
                    ->orWhereHas('service', function ($q2) use ($search) {
                        $q2->where('nom_service', 'like', "%{$search}%");
                    });
            });
        }

        // 👮 Si ce n'est pas admin → filtrer automatiquement par son service
        if (Auth::user()->type != 'admin') {

            $query->where('personnels.id_service', Auth::user()->personnel->id_service);
        }
        // 👨‍💼 Si admin → utiliser le filtre du select
        elseif ($request->filled('service')) {

            $query->where('personnels.id_service', $request->service);
        }

        // ✅ Ordre par catégorie de grade
        $query->orderBy('categories.n_order');

        $personnels = $query->select('personnels.*')
            ->paginate(10)
            ->withQueryString();

        // Liste services combo
        $services = Service::orderBy('nom_service')->get();

        return view('personnels.index', compact('personnels', 'services'));
    }

    public function indexOld(Request $request)
    {
        $query = Personnel::with(['grade', 'service'])
            ->join('categories', 'categories.id_grade', '=', 'personnels.id_grade')
            ->where('type_personnel', 'militaire');

        // 🔎 Recherche globale
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('personnels.nom', 'like', "%{$search}%")
                    ->orWhere('personnels.prenom', 'like', "%{$search}%")
                    ->orWhere('personnels.matricule', 'like', "%{$search}%")
                    ->orWhereHas('service', function ($q2) use ($search) {
                        $q2->where('nom_service', 'like', "%{$search}%");
                    });
            });
        }

        // 🎯 Filtre service
        if ($request->filled('service')) {
            $query->where('personnels.id_service', $request->service);
        }

        // ✅ Ordre par catégorie de grade
        $query->orderBy('categories.n_order');

        $personnels = $query->select('personnels.*')
            ->paginate(10)
            ->withQueryString();

        // Liste services combo
        $services = Service::orderBy('nom_service')->get();


        return view('personnels.index', compact('personnels', 'services'));
    }

    public function indexx(Request $request)
    {
        $query = Personnel::with(['grade', 'service'])
            ->where('type_personnel', 'militaire');

        // 🔎 Recherche globale
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('matricule', 'like', "%{$search}%")
                    ->orWhereHas('service', function ($q2) use ($search) {
                        $q2->where('nom_service', 'like', "%{$search}%");
                    });
            });
        }

        // 🎯 Filtre service
        if ($request->filled('service')) {
            $query->where('id_service', $request->service);
        }

        $personnels = $query->paginate(10)->withQueryString();

        // Liste services combo
        $services = Service::orderBy('nom_service')->get();

        return view('personnels.index', compact('personnels', 'services'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $grades = Grade::whereHas('categories') // toutes les catégories liées
            ->with(['categories' => function ($q) {
                $q->orderBy('n_order', 'asc'); // tri par n_order
            }])
            ->get();
        $services = Service::all();
        $fonctions = Fonction::all();

        return view('personnels.create', compact('grades', 'services', 'fonctions'));
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
            'id_service' => 'nullable|exists:services,id_service',
            'id_fonction'     => 'nullable|exists:fonctions,id_fonction', // si tu veux gérer la fonction
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
            'id_service' => $request->input('id_service', null),
            'id_fonction'    => $request->input('id_fonction', null),
        ]);

        // ✅ Redirection
        return redirect()
            ->route('personnels.index')
            ->with('success', 'Personnel ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id_personnel)
    {
        $personnel = Personnel::with(['grade', 'service'])
            ->where('id_personnel', $id_personnel)
            ->firstOrFail();

        return view('personnels.show', compact('personnel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $personnel = Personnel::findOrFail($id);

        $grades = Grade::all();
        $services = Service::all();
        $fonctions = Fonction::all();

        return view('personnels.edit', compact('personnel', 'grades', 'services', 'fonctions'));
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
            'nom'             => 'required|string|max:100',
            'prenom'          => 'required|string|max:100',
            'type_personnel'  => 'required|in:militaire,stagiaire',
            'id_grade'        => 'required|exists:grades,id_grade',
            'id_service'      => 'nullable|exists:services,id_service',
            'id_fonction'     => 'nullable|exists:fonctions,id_fonction', // si tu veux gérer la fonction
        ], [
            'matricule.unique' => 'Ce matricule existe déjà. Veuillez en saisir un autre.'
        ]);

        // ✅ Mise à jour
        $personnel->update([
            'matricule'       => strtoupper($request->matricule),
            'nom'             => strtoupper($request->nom),
            'prenom'          => strtoupper($request->prenom),
            'type_personnel'  => $request->type_personnel,
            'id_grade'        => $request->id_grade,
            'id_service'      => $request->input('id_service', null),
            'id_fonction'    => $request->input('id_fonction', null),
        ]);

        return redirect()
            ->route('personnels.index')
            ->with('success', 'Personnel mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_personnel)
    {
        $personnel = Personnel::findOrFail($id_personnel);

        $personnel->delete();

        return redirect()
            ->route('personnels.index')
            ->with('success', 'Personnel supprimé avec succès');
    }
}
