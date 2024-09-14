<?php

namespace App\Http\Controllers;

use App\Models\Conges;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Departement;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CongesProchain;
use App\Exports\CongesExporttous;


class RapportCongesController extends Controller
{
    // Vue principale avec les boutons pour les rapports
    public function index()
    {
        
        return view('rapports.index');
    }


 // Rapport des personnes actuellement en congé
 public function enCours(Request $request)
 {
     $dateNow = Carbon::now();
 
     // Récupérer tous les départements pour l'affichage dans le filtre
     $departements = Departement::all();
 
     // Récupérer l'ID du département sélectionné
     $departmentId = $request->input('department_id');
     
     // Début de la requête
     $query = Conges::where('dateDebut', '<=', $dateNow)
         ->where('dateFin', '>=', $dateNow)
         ->with('employe.departement'); // Charger la relation employe et departement
 
     // Si un département est sélectionné, ajouter le filtre
     if ($departmentId) {
         $query->whereHas('employe', function ($q) use ($departmentId) {
             $q->where('departementId', $departmentId); // Assurez-vous que le champ de la clé étrangère est correct
         });
     }
     // Exécuter la requête pour récupérer les congés en cours
     $congesEnCours = $query->get();
 
     // Calculer le nombre de personnes actuellement en congé
     $nombreCongesEnCours = $congesEnCours->count();
 
     return view('rapports.enCours', compact('congesEnCours', 'departements', 'departmentId', 'nombreCongesEnCours'));
 }
 
    // Rapport des personnes partant en congé le mois prochain
    public function moisProchain(Request $request)
    {
        $dateNow = Carbon::now();
        $startOfNextMonth = $dateNow->copy()->addMonth()->startOfMonth();
        $endOfNextMonth = $dateNow->copy()->addMonth()->endOfMonth();
        
        // Récupérer tous les départements pour l'affichage dans le filtre
        $departements = Departement::all();
        
        // Récupérer l'ID du département sélectionné
        $departmentId = $request->input('department_id');
    
        // Début de la requête
        $query = Conges::whereBetween('dateDebut', [$startOfNextMonth, $endOfNextMonth])
            ->orWhereBetween('dateFin', [$startOfNextMonth, $endOfNextMonth])
            ->with('employe.departement'); // Charger la relation employe et departement
    
        // Si un département est sélectionné, ajouter le filtre
        if ($departmentId) {
            $query->whereHas('employe', function ($q) use ($departmentId) {
                $q->where('departementId', $departmentId); // Assurez-vous que le champ de la clé étrangère est correct
            });
        }
    
        // Exécuter la requête pour récupérer les congés
        $congesMoisProchain = $query->get();
    
        // Calculer le nombre total de personnes partant en congé
        $nombreConges = $congesMoisProchain->count();
    
        return view('rapports.moisProchain', compact('congesMoisProchain', 'departements', 'departmentId', 'nombreConges'));
    }
    
    
    // Rapport des congés en attente de validation
    public function enAttente()
    {
        $congesEnAttente = Conges::where('status', 'en attente')
            ->orWhere('status', 'en attente RH')
            ->get();

        return view('rapports.enAttente', compact('congesEnAttente'));
    }

    // Rapport des départements avec plus de congés
    public function departements()
    {
        $departementsAvecConge = User::with('conges')
            ->get()
            ->groupBy('department')
            ->map(function ($users, $department) {
                return $users->flatMap->conges->count();
            })
            ->sortDesc();

        return view('rapports.departements', compact('departementsAvecConge'));
    }


public function export(Request $request)
{
    $departmentId = $request->input('department_id');
    
    $dateNow = Carbon::now();
    $startOfNextMonth = $dateNow->copy()->addMonth()->startOfMonth();
    $endOfNextMonth = $dateNow->copy()->addMonth()->endOfMonth();

    $query = Conges::whereBetween('dateDebut', [$startOfNextMonth, $endOfNextMonth])
        ->orWhereBetween('dateFin', [$startOfNextMonth, $endOfNextMonth])
        ->with('employe.departement');

    if ($departmentId) {
        $query->whereHas('employe', function ($q) use ($departmentId) {
            $q->where('departementId', $departmentId);
        });
    }

    $congesMoisProchain = $query->get();

    return Excel::download(new CongesProchain($congesMoisProchain), 'conges_mois_prochain.xlsx');
}

public function tousConges(Request $request)
{
    $departements = Departement::all();

    // Récupérer l'année filtrée
    $year = $request->input('year');
    $departmentId = $request->input('department_id');
    $status = $request->input('status'); // Nouveau filtre pour le status

    // Récupérer les années disponibles dans les données des congés
    $years = Conges::selectRaw('YEAR(dateDebut) as year')
                    ->distinct()
                    ->orderBy('year', 'desc')
                    ->pluck('year');

    // Début de la requête pour tous les congés
    $query = Conges::with('employe.departement');

    // Si une année est sélectionnée, filtrer par année
    if ($year) {
        $query->whereYear('dateDebut', $year);
    }

    // Si un département est sélectionné, ajouter le filtre
    if ($departmentId) {
        $query->whereHas('employe', function ($q) use ($departmentId) {
            $q->where('departementId', $departmentId);
        });
    }

    // Si un status est sélectionné, ajouter le filtre
    if ($status) {
        $query->where('status', $status);
    }

    // Utiliser la pagination au lieu de get(), par exemple 10 résultats par page
    $congesTous = $query->paginate(10);
    $nombreCongesTotal = $congesTous->total(); // Nombre total de congés paginés

    return view('rapports.tousConges', compact('congesTous', 'departements', 'departmentId', 'years', 'year', 'status', 'nombreCongesTotal'));
}


   
public function exporttous(Request $request)
{
    $departmentId = $request->input('departmentId');
    $year = $request->input('year');
    $status = $request->input('status'); // Ajouter la récupération du statut

    // Obtenir la date et l'heure actuelles
    $dateTime = now()->format('Y-m-d_H-i-s'); // Format: 2024-09-14_15-30-00

    // Créer le nom du fichier
    $fileName = "tousconges_{$dateTime}.xlsx";

    // Retourner le fichier avec le nom dynamique
    return Excel::download(new CongesExporttous($departmentId, $year, $status), $fileName);
}



}
