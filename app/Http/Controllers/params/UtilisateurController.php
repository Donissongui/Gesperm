<?php

namespace App\Http\Controllers\params;

use App\Http\Controllers\Controller;
use App\Models\Personnel;
use App\Models\User;
use Illuminate\Http\Request;


class UtilisateurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('login', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $users = $query->paginate(10);
        $personnels = Personnel::where('type_personnel', 'militaire')->get();
        return view('utilisateurs.index', compact('users', 'personnels'));
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
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'login'         => 'required|string|max:255|unique:users,login',
            'email'         => 'required|email|max:255|unique:users,email',
            'password'      => 'required|min:6',
            'type' => 'required|in:admin,manager,CCIT,CGS,CGCS,CGMI,CSTAGE,DFORMATION,SGCS,SGS,SGMI',
            'id_personnel'  => 'required|exists:personnels,id_personnel', // <-- validation du personnel
        ]);

        User::create([
            'name'         => ucwords(strtolower($validated['name'])),
            'login'        => ucfirst(strtolower($validated['login'])),
            'email'        => strtolower($validated['email']),
            'type'         => $validated['type'],
            'password'     => bcrypt($validated['password']),
            'id_personnel' => $validated['id_personnel'], // <-- liaison avec le personnel
        ]);


        return redirect()
            ->route('parametre.utilisateurs.index')
            ->with('success', 'Utilisateur ajouté avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('utilisateurs.show', compact('user'));
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
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name'         => 'required',
            'login'        => "required|unique:users,login,$id",
            'email'        => "required|email|unique:users,email,$id",
            'password'     => 'nullable|min:6',
            'type' => 'required|in:admin,manager,CCIT,CGS,CGCS,CGMI,CSTAGE,DFORMATION,SGCS,SGS,SGMI',
            'id_personnel' => 'nullable|exists:personnels,id_personnel', // <-- ajouter si on lie un personnel
        ]);

        // Normalisation
        $data['name']  = ucwords(strtolower($data['name']));
        $data['login'] = ucfirst(strtolower($data['login']));
        $data['email'] = strtolower($data['email']);

        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        } else {
            unset($data['password']);
        }

        // Si aucun personnel choisi, on met à null
        $data['id_personnel'] = $data['id_personnel'] ?? null;

        $user->update($data);

        return back()->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        User::destroy($id);

        return back();
    }
}
