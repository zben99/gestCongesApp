<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class DepartementController extends Controller
{
    public function index()
    {
        $query = Departement::query();
        $departements = Departement::all();
        $departements = $query->paginate(5); // Pagination avec 5 absences par page
        return view('departements.index', compact('departements'));
    }

    public function create()
    {
        return view('departements.edit');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_departement' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

         // Si la validation échoue
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
         }
         $departements = new Departement();
         $departements->name_departement = $request->input('name_departement');
         $departements->description = $request->input('description');
         $departements->save();

         // Redirection après succès
        return redirect()->route('departements.index')->with('success', 'Département ajouté avec succès.');
    }

    public function edit(Departement $departement)
    {
        return view('departements.edit', compact('departement'));
    }

    public function update(Request $request, Departement $departement)
    {
        $request->validate([
            'name_departement' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $departement->update($request->all());

        return redirect()->route('departements.index')->with('success', 'Département mis à jour avec succès.');
    }

    public function destroy(Departement $departement)
    {
        $departement->delete();

        return redirect()->route('departements.index')->with('success', 'Département supprimé avec succès.');
    }
}
