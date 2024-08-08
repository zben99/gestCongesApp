<?php

namespace App\Http\Controllers;

use App\Models\Conges;
use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CongesController extends Controller
{
    public function index()
    {
        $conges = Conges::all();
        return view('conges.index', compact('conges'));
    }

    public function create()
    {
        $employes = Employe::all();
        return view('conges.create', compact('employes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employeId' => 'required|exists:employes,id',
            'typeConges' => 'required',
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after_or_equal:dateDebut',
            'commentaire' => 'nullable',
        ]);

        $conge = new Conges($request->all());
        $conge->status = 'en attente'; // Le congé est en attente de validation
        $conge->save();

        // Envoyer une alerte par email si nécessaire
        $employe = Employe::find($request->employeId);
        if ($employe->reste >= 30) {
            Mail::to($employe->email)->send(new \App\Mail\CongesAlert($employe));
        }

        return redirect()->route('conges.index')->with('success', 'Demande de congé soumise avec succès.');
    }

    public function approve($id)
    {
        $conge = Conges::findOrFail($id);
        $conge->status = 'approuvé';
        $conge->save();

        // Déduire les jours de congé restants
        $employe = Employe::find($conge->employeId);
        $days = (new \DateTime($conge->dateFin))->diff(new \DateTime($conge->dateDebut))->days + 1;
        $employe->reste -= $days;
        $employe->save();

        return redirect()->route('conges.index')->with('success', 'Demande de congé approuvée.');
    }

    public function show($id)
{
    $conge = Conges::findOrFail($id);
    return view('conges.show', compact('conge'));
}

public function approveByManager($id)
{
    $conge = Conges::findOrFail($id);
    $user = auth()->user();

    if ($user->profil !== 'manager') {
        return redirect()->back()->with('error', 'Accès non autorisé.');
    }

    if ($conge->status === 'en attente') {
        $conge->status = 'en attente RH';
        $conge->approved_by_manager = $user->id;
        $conge->save();
    }

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
        $conge->save();
    } elseif ($conge->status === 'en attente') {
        $conge->status = 'refusé';
        $conge->save();
    }

    return redirect()->route('conges.index')->with('success', 'Demande traitée par le responsable RH.');
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


}
