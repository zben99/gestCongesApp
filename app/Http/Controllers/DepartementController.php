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

        try {
            $departement = new Departement();
            $departement->name_departement = $request->input('name_departement');
            $departement->description = $request->input('description');
            $departement->save();

            // Redirection après succès
            return redirect()->route('departements.index')->with('success', 'Département ajouté avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'ajout du département.')->withInput();
        }
    }

    public function edit(Departement $departement)
    {
        return view('departements.edit', compact('departement'));
    }

    public function update(Request $request, Departement $departement)
    {
        $validator = Validator::make($request->all(), [
            'name_departement' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Si la validation échoue
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $departement->update($request->all());
            return redirect()->route('departements.index')->with('success', 'Département mis à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour du département.')->withInput();
        }
    }

    public function destroy(Departement $departement)
    {
        try {
            // Vérifier si le département est associé à un utilisateur
            if ($departement->employes()->count() > 0) {
                return redirect()->route('departements.index')->with('error', 'Le département ne peut pas être supprimé car il est associé à des utilisateurs.');
            }

            $departement->delete();
            return redirect()->route('departements.index')->with('success', 'Département supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la suppression du département.');
        }
    }
}
