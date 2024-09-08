<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Conges;
use App\Models\TypeConges;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CongesController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Conges::query();
    
        // Ajout des filtres de recherche
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        // Gestion des profils
        if ($user->profil == 'manager') {
            $conges = $query->whereIn('UserId', $user->employees->pluck('id')->push($user->id))
                            ->paginate(7);
        } elseif ($user->profil == 'responsables RH') {
            $conges = $query->paginate(7);
        } else {
            $conges = $query->where('UserId', $user->id)->paginate(5);
        }
    
        return view('conges.index', compact('conges'));
    }
    

    public function create()
    {
        $users = User::all();
        $typeConges = TypeConges::all();
        return view('conges.edit', compact('users', 'typeConges'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'userId' => 'required|exists:users,id',
            'type_conge_id' => 'required|exists:type_conges,id',
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after_or_equal:dateDebut',
            'commentaire' => 'nullable',
        ]);

        $user = User::findOrFail($request->userId);
        $typeConge = TypeConges::findOrFail($request->type_conge_id);

        // Calcul du nombre de jours demandés
        $days1 = (new \DateTime($request->dateFin))->diff(new \DateTime($request->dateDebut))->days + 1;

        // Vérification de la durée maximale pour le type de congé
        if ($days1 > $typeConge->duree_max) {
            return redirect()->route('conges.index')->with('error', 'La durée demandée dépasse la durée maximale autorisée pour ce type de congé.');
        }

        // Calcul des jours de congé disponibles
        $days = (new \DateTime(now()))->diff(new \DateTime($user->initialization_date))->days + 1;
        $nbreConge = ($days * 2.5) / 30;
        $congeRestant = floor($nbreConge + $user->initial - $user->pris);

        // Vérification si le nombre de jours demandés dépasse les congés restants
        if ($days1 > $congeRestant) {
            return redirect()->route('conges.index')->with('error', 'Impossible de créer une demande avec ce nombre de jours.');
        }

        try {
            // Création de la demande de congé
            $conge = new Conges($request->all());
            $conge->status = 'en attente';
            $conge->save();

            return redirect()->route('conges.index')->with('success', 'Demande de congé soumise avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('conges.index')->with('error', 'Une erreur est survenue lors de la soumission de la demande.');
        }
    }






    public function edit(Conges $conge)
    {
        $users = User::all();
        $typeConges = TypeConges::all();
        return view('conges.edit', compact('conge','users','typeConges'));
    }

    public function update(Request $request, Conges $conge)
    {
        $request->validate([
            'type_conge_id' => 'required|exists:type_conges,id',
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after_or_equal:dateDebut',
            'commentaire' => 'nullable',
        ]);

        $user = User::findOrFail($conge->UserId);
        $typeConge = TypeConges::findOrFail($request->type_conge_id);

        // Calcul du nombre de jours demandés
        $days1 = $this->calculateDays($request->dateDebut, $request->dateFin);

        // Vérification de la durée maximale pour le type de congé
        if ($days1 > $typeConge->duree_max) {
            return redirect()->route('conges.index')->with('error', 'La durée demandée dépasse la durée maximale autorisée pour ce type de congé.');
        }

        // Calcul des jours de congé disponibles
        $days = $this->calculateDays($user->initialization_date, now());
        $nbreConge = ($days * 2.5) / 30;
        $congeRestant = floor($nbreConge + $user->initial - $user->pris);

        // Vérification si le nombre de jours demandés dépasse les congés restants
        if ($days1 > $congeRestant) {
            return redirect()->route('conges.index')->with('error', 'Impossible de modifier la demande avec ce nombre de jours.');
        }

        // Mise à jour des informations du congé
        $conge->type_conge_id = $request->type_conge_id;
        $conge->dateDebut = $request->dateDebut;
        $conge->dateFin = $request->dateFin;
        $conge->commentaire = $request->commentaire;
        $conge->save();

        return redirect()->route('conges.index')->with('success', 'Demande de congé modifiée avec succès.');
    }




    public function show($conge)
{
    $conge = Conges::findOrFail($conge);
    return view('conges.show', compact('conge'));
}

public function approveByManager($conge)
{
    $conge = Conges::findOrFail($conge);
    $user = auth()->user();

    if ($user->profil !== 'manager') {
        return redirect()->back()->with('error', 'Accès non autorisé.');
    }


        $conge->status = 'en attente RH';
        $conge->approved_by_manager = $user->id;
        $conge->save();


    return redirect()->route('conges.index')->with('success', 'Demande approuvée par le manager.');
}
public function approveByRh($id)
{
    $conge = Conges::findOrFail($id);
    $user = auth()->user();

    if ($user->profil !== 'responsables RH') {
        return redirect()->back()->with('error', 'Accès non autorisé.');
    }

    if ($conge->status === 'en attente RH') {
        $conge->status = 'approuvé';
        $conge->approved_by_rh = $user->id;
        if ($conge->typeConge->nom=='Congé annuel') {
            $days = $this->calculateDays($conge->dateDebut, $conge->dateFin);
            $conge->employe->pris += $days;
            $conge->employe->save();
        }


        $conge->save();

    } elseif ($conge->status === 'en attente') {
        $conge->status = 'refusé';
        $conge->save();
    } else {
        return redirect()->back()->with('error', 'Le statut de la demande est invalide.');
    }

    return redirect()->route('conges.index')->with('success', 'Demande traitée par le responsable RH.');
}

private function calculateDays($dateDebut, $dateFin)
{
    return (new \DateTime($dateFin))->diff(new \DateTime($dateDebut))->days + 1;
}




public function reject($id)
{
    $conge = Conges::findOrFail($id);
    $user = auth()->user();

    if ($user->profil !== 'manager' && $user->profil !== 'responsables RH') {
        return redirect()->back()->with('error', 'Accès non autorisé.');
    }

    if ($conge->status === 'en attente' || $conge->status === 'en attente RH') {
        $conge->status = 'refusé';
        $conge->save();
    }

    return redirect()->route('conges.index')->with('success', 'Demande refusée.');
}


public function destroy(Conges $conge)
    {
        $conge->delete();

        return redirect()->route('conges.index')->with('success', 'Congé supprimé avec succès.');
    }

}
