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
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duree_max' => 'required|integer|min:1',
            'justificatif_requis' => 'required|boolean',
        ]);

        try {
            TypeConges::create($validated);
            return redirect()->route('typeConges.index')->with('success', 'Type de congé ajouté avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'ajout du type de congé.')->withInput();
        }
    }



    public function edit(TypeConges $conge)
    {

       $typeConge=$conge;
        return view('typeConges.edit', compact('typeConge'));
    }


    public function update(Request $request, TypeConges $conge)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duree_max' => 'required|integer|min:1',
            'justificatif_requis' => 'required|boolean',
        ]);

        try {
            $conge->update($validated);
            return redirect()->route('typeConges.index')->with('success', 'Type de congé mis à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour du type de congé.')->withInput();
        }
    }

    public function destroy(TypeConges $conge)
    {
        try {
            $conge->delete();
            return redirect()->route('typeConges.index')->with('success', 'Type de congé supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la suppression du type de congé.');
        }
    }
}
