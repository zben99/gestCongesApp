<?php

namespace App\Http\Controllers;

use App\Models\TypeConges;
use Illuminate\Http\Request;

class TypeCongesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $typeConges = TypeConges::all();
        return view('typeConges.index', compact('typeConges'));
    }

    public function create()
    {
        return view('typeConges.edit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duree_max' => 'required|integer|min:1',
            'justificatif_requis' => 'required|boolean',
        ]);

        TypeConges::create([
            'nom' => $request->nom,
            'description' => $request->description,
            'duree_max' => $request->duree_max,
            'justificatif_requis' => $request->justificatif_requis,
        ]);

        return redirect()->route('typeConges.index')->with('success', 'Type de congé ajouté avec succès.');
    }




    public function edit(TypeConges $conge)
    {

       $typeConge=$conge;
        return view('typeConges.edit', compact('typeConge'));
    }


    public function update(Request $request, TypeConges $conge)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duree_max' => 'required|integer|min:1',
            'justificatif_requis' => 'required|boolean',
        ]);

        $conge->update([
            'nom' => $request->nom,
            'description' => $request->description,
            'duree_max' => $request->duree_max,
            'justificatif_requis' => $request->justificatif_requis,
        ]);

        return redirect()->route('typeConges.index')->with('success', 'Type de congé mis à jour avec succès.');
    }

    public function destroy(TypeConges $conge)
    {
        $conge->delete();

        return redirect()->route('typeConges.index')->with('success', 'Type de congé supprimé avec succès.');
    }
}
