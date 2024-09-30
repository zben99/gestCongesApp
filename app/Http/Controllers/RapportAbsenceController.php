<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Departement;
use App\Exports\AbsencesExport;
use Maatwebsite\Excel\Facades\Excel;


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

    // Rapport de toutes les absences avec filtres par année, département et statut
    public function toutesAbsences(Request $request)
    {
        try {
            // Récupérer les types d'absence distincts
            $absenceTypes = Absence::select('status')->distinct()->pluck('status');
    
            // Récupérer tous les départements
            $departements = Departement::all();
            
            // Récupérer les années disponibles
            $years = Absence::selectRaw('YEAR(dateDebut) as year')->distinct()->pluck('year');
    
            // Récupérer les paramètres de filtrage
            $annee = $request->input('year');
            $departmentId = $request->input('department_id');
            $status = $request->input('status');
    
            // Valider l'année
            if ($annee && !is_numeric($annee)) {
                throw new \Exception('L\'année doit être un nombre valide.');
            }
    
            // Requête de base
            $query = Absence::with('user.departement');
    
            // Filtrage par année
            if ($annee) {
                $query->whereYear('dateDebut', $annee);
            }
    
            // Filtrage par département
            if ($departmentId) {
                if (!Departement::find($departmentId)) {
                    throw new \Exception('Le département sélectionné n\'existe pas.');
                }
                $query->whereHas('user', function ($q) use ($departmentId) {
                    $q->where('departementId', $departmentId);
                });
            }
    
            // Filtrage par statut
            if ($status) {
                $query->where('status', $status);
            }
    
            // Pagination
            $absences = $query->paginate(10);
    
            // Calculer le nombre total d'absences
            $nombreAbsencesTotal = $absences->total();
    
            return view('rapportsAbsences.toutesAbsences', compact('absences', 'departements', 'years', 'annee', 'departmentId', 'status', 'nombreAbsencesTotal', 'absenceTypes'));
    
        } catch (\Exception $e) {
            // Gestion des erreurs
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
    
    
    public function exportAbsences(Request $request)
    {
        try {
            $departmentId = $request->input('departmentId');
            $year = $request->input('year');
            $status = $request->input('status');
    
            // Validation des paramètres
            if ($year && !is_numeric($year)) {
                throw new \Exception('L\'année doit être un nombre valide.');
            }
    
            if ($departmentId && !Departement::find($departmentId)) {
                throw new \Exception('Le département sélectionné n\'existe pas.');
            }
    
            // Créer le nom du fichier
            $fileName = "absences_{$year}.xlsx"; // Vous pouvez personnaliser le nom du fichier
    
            return Excel::download(new AbsencesExport($departmentId, $year, $status), $fileName);
            
        } catch (\Exception $e) {
            // Gestion des erreurs
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
    


}
