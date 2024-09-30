<?php

namespace App\Http\Controllers;

use App\Models\Poste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PosteController extends Controller
{
    public function index()
    {
        $query = Poste::query();
        $postes = Poste::all();
        $postes = $query->paginate(5); // Pagination avec 5 absences par page

        return view('postes.index', compact('postes'));
    }

    public function create()
    {
        return view('postes.edit');
    }

 public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_poste' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Si la validation échoue
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $poste = new Poste();
            $poste->name_poste = $request->input('name_poste');
            $poste->description = $request->input('description');
            $poste->save();

            return redirect()->route('postes.index')->with('success', 'Poste ajouté avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'ajout du poste.')->withInput();
        }
    }

    public function edit(Poste $poste)
    {
        return view('postes.edit', compact('poste'));
    }

    public function update(Request $request, Poste $poste)
    {
        $validator = Validator::make($request->all(), [
            'name_poste' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Si la validation échoue
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $poste->update($request->all());
            return redirect()->route('postes.index')->with('success', 'Poste mis à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour du poste.')->withInput();
        }
    }

    public function destroy(Poste $poste)
    {
        try {
            // Vérifier si le poste est associé à un utilisateur
            if ($poste->employes()->count() > 0) {
                return redirect()->route('postes.index')->with('error', 'Le poste ne peut pas être supprimé car il est associé à des utilisateurs.');
            }

            $poste->delete();
            return redirect()->route('postes.index')->with('success', 'Poste supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la suppression du poste.');
        }
        
    }
}
