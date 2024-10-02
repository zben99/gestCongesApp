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
        // Validation des données d'entrée
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duree_max' => 'required|integer|min:1',
            'justificatif_requis' => 'required|boolean',
            'deductible_conges' => 'required|boolean',
            'jours_deductibles_apres' => 'nullable|integer|min:1',
        ]);

        try {
            TypeAbsences::create($validated); // Création du type d'absence
            return redirect()->route('typeAbsences.index')->with('success', 'Type d\'absence créé avec succès.');
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la création du type d'absence: " . $e->getMessage()); // Log l'erreur
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la création du type d\'absence.')->withInput();
        }
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
        try {
            $typeAbsence->delete(); // Suppression du type d'absence
            return redirect()->route('typeAbsences.index')->with('success', 'Type d\'absence supprimé avec succès.');
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la suppression du type d'absence: " . $e->getMessage()); // Log l'erreur
            return redirect()->route('typeAbsences.index')->with('error', 'Impossible de supprimer ce type d\'absence car il est associé à des absences existantes.');
        }
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
