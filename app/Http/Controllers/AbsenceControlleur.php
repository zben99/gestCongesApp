<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AbsenceControlleur extends Controller
{
    // Afficher la liste des absences
    public function index()
    {
        $user = auth()->user();

        // Si l'utilisateur est un manager, montrer les absences qu'il doit approuver
        if ($user->profil == 'manager') {
            $absences = Absence::where('approved_by', $user->id)->with('user')->get();
        } else {
            // Sinon, montrer seulement ses propres absences
            $absences = Absence::where('UserId', $user->id)->with('user')->get();
        }

        return view('absence.index', compact('absences'));
    }

    // Afficher le formulaire de création
    public function create()
    {
        $users = User::where('profil', 'employés')->get(); // Récupérer uniquement les employés pour le formulaire
        return view('absence.create', compact('users'));
    }

    // Stocker une nouvelle absence
    public function store(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'motif' => 'required|string|max:255',
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after_or_equal:dateDebut',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Récupérer l'utilisateur connecté
        $user = auth()->user();

        // Création de l'absence
        $absence = new Absence();
        $absence->UserId = $user->id; // Associer l'absence à l'utilisateur connecté
        $absence->motif = $request->input('motif');
        $absence->dateDebut = Carbon::parse($request->input('dateDebut'));
        $absence->dateFin = Carbon::parse($request->input('dateFin'));
        $absence->status = 'en attente'; // Définir le statut par défaut
        $absence->commentaire = $request->input('commentaire');

        // Associer l'absence au manager de l'utilisateur connecté
        if ($user->managers->isNotEmpty()) {
            $absence->approved_by = $user->managers->first()->id;
        }

        $absence->save();

        return redirect()->route('absences.index')->with('success', 'Absence créée avec succès');
    }

    // Afficher une absence spécifique
    public function show(Absence $absence)
    {
        return view('absence.show', compact('absence'));
    }

    // Afficher le formulaire d'édition
    public function edit(Absence $absence)
    {
        $users = User::where('profil', 'employé')->get(); // Récupérer uniquement les employés pour le formulaire
        return view('absence.edit', compact('absence', 'users'));
    }

    // Mettre à jour une absence
    public function update(Request $request, Absence $absence)
    {
        $validator = Validator::make($request->all(), [
            'UserId' => 'required|exists:users,id',
            'motif' => 'required|string|max:255',
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after_or_equal:dateDebut',
            'status' => ['required', Rule::in(['en attente', 'approuvé', 'refusé'])],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $absence->UserId = $request->input('UserId');
        $absence->motif = $request->input('motif');
        $absence->dateDebut = Carbon::parse($request->input('dateDebut'));
        $absence->dateFin = Carbon::parse($request->input('dateFin'));
        $absence->commentaire = $request->input('commentaire');
        $absence->status = $request->input('status'); // Statut correctement défini

        $absence->save();

        return redirect()->route('absences.index')->with('success', 'Absence mise à jour avec succès');
    }

    // Supprimer une absence
    public function destroy(Absence $absence)
    {
        if (!$absence) {
            return redirect(route('absences.index'))->with('error', 'Absence non trouvée');
        }

        $absence->delete();

        return redirect(route('absences.index'))->with('success', 'Absence supprimée avec succès');
    }

    public function validateRequest($id)
    {
        $absence = Absence::findOrFail($id);
        $absence->status = 'approuvé';
        $absence->approved_by = auth()->user()->id; // Ajouter l'ID de l'utilisateur qui approuve
        $absence->save();

        return redirect()->route('absences.index')->with('success', 'La demande d\'absence a été validée.');
    }

    public function rejectRequest($id)
    {
        $absence = Absence::findOrFail($id);
        $absence->status = 'refusé'; // Assurez-vous que 'refusé' est bien dans les valeurs acceptées
        $absence->approved_by = auth()->user()->id; // Ajouter l'ID de l'utilisateur qui rejette
        $absence->save();

        return redirect()->route('absences.index')->with('success', 'La demande d\'absence a été rejetée.');
    }
}
