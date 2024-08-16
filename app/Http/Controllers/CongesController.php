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


        $conge = new Conges($request->all());
        $conge->status = 'en attente'; // Le congé est en attente de validation
        $conge->save();


        return redirect()->route('conges.index')->with('success', 'Demande de congé soumise avec succès.');
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


public function destroy(Conges $conge)
    {
        $conge->delete();

        return redirect()->route('conges.index')->with('success', 'Congé supprimé avec succès.');
    }

}
