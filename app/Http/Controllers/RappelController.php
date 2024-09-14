<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Conges;
use App\Models\Rappel;
use Illuminate\Http\Request;
use App\Services\HolidaysService;

class RappelController extends Controller
{

    protected $holidaysService;

    public function __construct(HolidaysService $holidaysService)
    {
        $this->holidaysService = $holidaysService;
    }


    /**
     * Afficher la liste des rappels pour un congé spécifique.
     */
    public function indexRappels(Conges $conge)
    {
        // Récupération de tous les rappels liés à ce congé
        $rappels = Rappel::where('conge_id', $conge->id)->get();

        // Retourner la vue avec la liste des rappels
        return view('conges.rappels.index', compact('rappels', 'conge'));
    }

    /**
     * Afficher le formulaire de création d'un rappel.
     */
    public function createRappel(Conges $conge)
    {
        return view('conges.rappels.edit', compact('conge'));
    }

    /**
     * Stocker un nouveau rappel dans la base de données.
     */
    public function creerRappel(Request $request, Conges $conge)
    {
        // Validation des données
        $validatedData = $request->validate([
            'dateDebutRappel' => 'required|date',
            'dateFinRappel' => 'nullable|date|after_or_equal:dateDebutRappel',
        ]);

        // Création du rappel
       $rappel= Rappel::create([
            'conge_id' => $conge->id,
            'dateDebutRappel' => Carbon::parse($validatedData['dateDebutRappel']),
            'dateFinRappel' => isset($validatedData['dateFinRappel']) ? Carbon::parse($validatedData['dateFinRappel']) : Carbon::parse($validatedData['dateDebutRappel']),
        ]);

        $days = $this->calculateDays($rappel->dateDebutRappel, $rappel->dateFinRappel);
        $conge->user->decrement('pris', $days);


        return redirect()->route('rappels.index', ['conge' => $conge->id])
                         ->with('success', 'Rappel créé avec succès.');
    }

    /**
     * Afficher le formulaire d'édition d'un rappel.
     */
    public function editRappel(Rappel $rappel)
    {
        $conge = Conges::findOrFail($rappel->conge_id);
        return view('conges.rappels.edit', compact('rappel', 'conge'));
    }

    /**
     * Mettre à jour un rappel existant.
     */
    public function mettreAJourRappel(Request $request, Rappel $rappel)
    {
        // Validation des données
        $validatedData = $request->validate([
            'dateDebutRappel' => 'required|date',
            'dateFinRappel' => 'nullable|date|after_or_equal:dateDebutRappel',
        ]);

        // Mise à jour du rappel
        $rappel->update([
            'dateDebutRappel' => Carbon::parse($validatedData['dateDebutRappel']),
            'dateFinRappel' => isset($validatedData['dateFinRappel']) ? Carbon::parse($validatedData['dateFinRappel']) : null,
        ]);

        return redirect()->route('rappels.index', ['conge' => $rappel->conge_id])
                         ->with('success', 'Rappel mis à jour avec succès.');
    }

    /**
     * Supprimer un rappel de la base de données.
     */
    public function supprimerRappel(Rappel $rappel)
    {
       // restitution de jours déduit
        $days = $this->calculateDays($rappel->dateDebutRappel, $rappel->dateFinRappel);
       $rappel->conge->user->increment('pris', $days);


        $rappel->delete();

        return redirect()->route('rappels.index', ['conge' => $rappel->conge_id])
                         ->with('success', 'Rappel supprimé avec succès.');
    }

    protected function calculateDays($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        return $this->holidaysService->countWorkingDays($start, $end);
    }
}
