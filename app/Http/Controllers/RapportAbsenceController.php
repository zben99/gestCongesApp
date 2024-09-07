<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Departement;

class RapportAbsenceController extends Controller
{
    // Vue principale avec les options de rapports
    public function index()
    {
        return view('rapportsAbsences.index');
    }

    // Rapport des absences en cours
    public function enCours(Request $request)
    {
        $dateNow = Carbon::now();

        // Récupérer tous les départements pour filtrer
        $departements = Departement::all();

        // Filtrer par département si sélectionné
        $departmentId = $request->input('department_id');

        // Requête de base pour les absences en cours
        $query = Absence::where('dateDebut', '<=', $dateNow)
            ->where('dateFin', '>=', $dateNow)
            ->with('user.departement');

        // Ajouter un filtre par département si applicable
        if ($departmentId) {
            $query->whereHas('user', function ($q) use ($departmentId) {
                $q->where('departementId', $departmentId);
            });
        }

        $absencesEnCours = $query->get();
        $nombreAbsencesEnCours = $absencesEnCours->count();

        return view('rapportsAbsences.enCours', compact('absencesEnCours', 'departements', 'departmentId', 'nombreAbsencesEnCours'));
    }

    // Rapport des absences en attente de validation
    public function enAttente()
    {
        $absencesEnAttente = Absence::where('status', 'en attente')
            ->get();

        return view('rapportsAbsences.enAttente', compact('absencesEnAttente'));
    }

    // Rapport des départements avec plus d'absences
    public function departements()
    {
        $departementsAvecAbsence = User::with('absences')
            ->get()
            ->groupBy('departement.name_departement')
            ->map(function ($users) {
                return $users->flatMap->absences->count();
            })
            ->sortDesc();

        return view('rapportsAbsences.departements', compact('departementsAvecAbsence'));
    }

    // Rapport des absences pour le mois prochain
    public function moisProchain()
    {
        $dateNow = Carbon::now();
        $startOfNextMonth = $dateNow->copy()->addMonth()->startOfMonth();
        $endOfNextMonth = $dateNow->copy()->addMonth()->endOfMonth();
        
        $absencesMoisProchain = Absence::whereBetween('dateDebut', [$startOfNextMonth, $endOfNextMonth])
            ->orWhereBetween('dateFin', [$startOfNextMonth, $endOfNextMonth])
            ->get();

        return view('rapportsAbsences.moisProchain', compact('absencesMoisProchain'));
    }
}
