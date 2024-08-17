<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Conges;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CongesController extends Controller
{
    public function index()
    {
        $user = auth()->user();



        if ($user->profil == 'manager') {

            $conges = Conges::whereIn('UserId', $user->employees->pluck('id')->push($user->id))->get();

            return view('conges.index', compact('conges'));

        }


        if ($user->profil == 'responsables RH') {

            $conges = Conges::all();

            return view('conges.index', compact('conges'));

        }


        //dd($user->id);
        $conges = Conges::where('UserId', $user->id)->get();
        return view('conges.index', compact('conges'));

    }

    public function create()
    {
        $users = User::all();
        return view('conges.edit', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'userId' => 'required|exists:users,id',
            'typeConges' => 'required',
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after_or_equal:dateDebut',
            'commentaire' => 'nullable',
        ]);

        $user = User::findOrFail($request->userId);

        $days = (new \DateTime(now()))->diff(new \DateTime($user->initialization_date))->days + 1;
        $nbreConge = ($days * 2.5) / 30;
        $congeRestant = floor($nbreConge + $user->initial - $user->pris);

        $days1 = (new \DateTime($request->dateFin))->diff(new \DateTime($request->dateDebut))->days + 1;

        if ($days1 > $congeRestant) {
            return redirect()->route('conges.index')->with('error', 'Impossible de créer une demande avec ce nombre de jours.');
        }

        try {
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
        return view('conges.edit', compact('conge','users'));
    }

    public function update(Request $request, Conges $conge)
    {
        $request->validate([
            'typeConges' => 'required',
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after_or_equal:dateDebut',
            'commentaire' => 'nullable',
        ]);


        $user = User::findOrFail($conge->userId);

        $days = $this->calculateDays( $user->initialization_date, now());
        $nbreConge = ($days * 2.5) / 30;
        $congeRestant = floor($nbreConge + $user->initial - $user->pris);


        $days1 = $this->calculateDays($request->dateDebut, $request->dateFin);

        if ($days1 > $congeRestant) {
            return redirect()->route('conges.index')->with('error', 'Impossible de créer une demande avec ce nombre de jours.');
        }

        $conge->typeConges = $request->typeConges;
        $conge->dateDebut = $request->dateDebut;
        $conge->dateFin = $request->dateFin;
        $conge->commentaire = $request->commentaire;
        $conge->save();


        return redirect()->route('conges.index')->with('success', 'Demande de congé modifier avec succès.');
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

        $days = $this->calculateDays($conge->dateDebut, $conge->dateFin);
        $conge->employe->pris += $days;
        $conge->employe->save();
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
