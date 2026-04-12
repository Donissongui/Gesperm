<?php

namespace App\Http\Controllers\params;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Service::query();

        if ($request->filled('search')) {
            $query->where('nom_service', 'like', '%' . $request->search . '%');
        }

        $services = $query->paginate(5)->appends($request->query());

        return view('service_personnels.index', compact('services'));
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
            'nom_service' => 'required|string|max:50|unique:services,nom_service'
        ]);

        Service::create([
            'nom_service' => ucwords(strtolower($request->nom_service))
        ]);

        return redirect()->back()
            ->with('success', 'Service ajouté avec succès.');
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
    public function update(Request $request, $id_service)
    {
        $request->validate([
            'nom_service' => 'required|string|max:50|unique:services,nom_service,' . $id_service . ',id_service'
        ]);

        Service::where('id_service', $id_service)->update([
            'nom_service' => ucwords(strtolower($request->nom_service))
        ]);

        return redirect()->back()
            ->with('success', 'Service modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_service)
    {
        Service::where('id_service', $id_service)->delete();

        return redirect()->back();
    }
}
