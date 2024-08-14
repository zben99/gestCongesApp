<?php

namespace App\Http\Controllers;

use App\Models\Poste;
use Illuminate\Http\Request;

class PosteController extends Controller
{
    public function index()
    {
        $query = Poste::query();
        $postes = Poste::all();
        $postes = $query->paginate(2); // Pagination avec 5 absences par page
      
        return view('postes.index', compact('postes'));
    }

    public function create()
    {
        return view('postes.edit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_poste' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Poste::create($request->all());

        return redirect()->route('postes.index')->with('success', 'Poste ajouté avec succès.');
    }

    public function edit(Poste $poste)
    {
        return view('postes.edit', compact('poste'));
    }

    public function update(Request $request, Poste $poste)
    {
        $request->validate([
            'name_poste' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $poste->update($request->all());

        return redirect()->route('postes.index')->with('success', 'Poste mis à jour avec succès.');
    }

    public function destroy(Poste $poste)
    {
        $poste->delete();

        return redirect()->route('postes.index')->with('success', 'Poste supprimé avec succès.');
    }
}
