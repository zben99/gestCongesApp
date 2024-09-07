<?php

namespace App\Http\Controllers;
use App\Models\TypeAbsences;
use Illuminate\Http\Request;

class TypeAbsencesController extends Controller
{
    /**
     * Affiche une liste des types d'absences.
     */
    public function index()
    {
        $typeAbsences = TypeAbsences::all();
        return view('typeAbsences.index', compact('typeAbsences'));
    }

    /**
     * Affiche le formulaire pour créer un nouveau type d'absence.
     */
    public function create()
    {
        return view('typeAbsences.edit');
    }

    /**
     * Enregistre un nouveau type d'absence dans la base de données.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duree_max' => 'required|integer|min:1',
            'justificatif_requis' => 'required|boolean',
            'deductible_conges' => 'required|boolean',
            'jours_deductibles_apres' => 'nullable|integer|min:1',
        ]);

        TypeAbsences::create($request->all());

        return redirect()->route('typeAbsences.index')->with('success', 'Type d\'absence créé avec succès.');
    }



    /**
     * Affiche le formulaire pour modifier un type d'absence existant.
     */
    public function edit(TypeAbsences $absence)
    {
        $typeAbsence= $absence;
        return view('typeAbsences.edit', compact('typeAbsence'));
    }

    /**
     * Met à jour un type d'absence spécifique dans la base de données.
     */
    public function update(Request $request, TypeAbsences $absence)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duree_max' => 'required|integer|min:1',
            'justificatif_requis' => 'required|boolean',
            'deductible_conges' => 'required|boolean',
            'jours_deductibles_apres' => 'nullable|integer|min:1',
        ]);

        $absence->update($request->all());

        return redirect()->route('typeAbsences.index')->with('success', 'Type d\'absence mis à jour avec succès.');
    }

    /**
     * Supprime un type d'absence spécifique de la base de données.
     */
    public function destroy(TypeAbsences $typeAbsence)
    {
        // Le modèle `$typeAbsence` est déjà injecté par Laravel via le Route Model Binding
        // Aucune nécessité de le rechercher à nouveau avec `find`
    
        // Assurez-vous que les absences associées seront supprimées si les contraintes de cascade sont configurées
        $typeAbsence->delete();
    
        return redirect()->route('typeAbsences.index')->with('success', 'Type d\'absence supprimé avec succès.');
    }

        /**
     * Affiche les détails d'un type d'absence spécifique.
     */
    public function show($id)
    {
        $typeAbsence = TypeAbsences::findOrFail($id);

        return view('typeAbsences.show', compact('typeAbsence'));
    }
    
}
