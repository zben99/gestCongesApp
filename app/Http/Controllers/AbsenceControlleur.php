<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AbsenceControlleur extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $absences = Absence::with('user')->get(); // Récupérer les absences avec les utilisateurs

        return view('absence.index', compact('absences'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all(); // Récupérer tous les utilisateurs pour le formulaire
        return view('absence.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'motif' => 'required|string|max:255',
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after_or_equal:dateDebut',
            'status' => ['required', Rule::in(['en attente', 'approuvé', 'refusé'])],
        ]);

        // Si la validation échoue
        if ($validator->fails()) {
            return redirect()->route('absences.create')
                             ->withErrors($validator)
                             ->withInput();
        }

        // Création de l'absence
        $absence = new Absence();
        $absence->user_id = $request->input('user_id');
        $absence->motif = $request->input('motif');
        $absence->dateDebut = $request->input('dateDebut');
        $absence->dateFin = $request->input('dateFin');
        $absence->status = $request->input('status');
        $absence->save();

        // Redirection après succès
        return redirect()->route('absences.index')->with('success', 'Absence créée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Absence $absence)
    {
        return view('absence.show', compact('absence'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Absence $absence)
    {
        $users = User::all(); // Récupérer tous les utilisateurs pour le formulaire
        return view('absence.edit', compact('absence', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Absence $absence)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'motif' => 'required|string|max:255',
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after_or_equal:dateDebut',
            'status' => ['required', Rule::in(['en attente', 'approuvé', 'refusé'])],
        ]);

        // Si la validation échoue
        if ($validator->fails()) {
            return redirect()->route('absences.edit', $absence)
                             ->withErrors($validator)
                             ->withInput();
        }

        // Mise à jour de l'absence
        $absence->user_id = $request->input('user_id');
        $absence->motif = $request->input('motif');
        $absence->dateDebut = $request->input('dateDebut');
        $absence->dateFin = $request->input('dateFin');
        $absence->status = $request->input('status');
        $absence->save();

        // Redirection après succès
        return redirect()->route('absences.index')->with('success', 'Absence modifiée avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absence $absence)
    {
        if (!$absence) {
            return redirect(route('absences.index'))->with('error', 'Absence non trouvée');
        }

        $absence->delete();

        return redirect(route('absences.index'))->with('success', 'Absence supprimée avec succès');
    }
}
