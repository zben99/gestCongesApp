<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {
        // Calculer les statistiques des absences
        $totalDemandesAbsence = Absence::count();
        $totalAbsencesValides = Absence::where('status', 'approuvé')->count();
        $totalAbsencesEnAttente = Absence::where('status', 'en attente')->count();
        $totalAbsencesEnAttenteDepuis3Jours = Absence::where('status', 'en attente')
                                                     ->where('created_at', '<', now()->subDays(3))
                                                     ->count();
        dd($totalDemandesAbsence, $totalAbsencesValides, $totalAbsencesEnAttente, $totalAbsencesEnAttenteDepuis3Jours);

        // Passer les données à la vue
        return view('dashboard', compact(
            'totalDemandesAbsence',
            'totalAbsencesValides',
            'totalAbsencesEnAttente',
            'totalAbsencesEnAttenteDepuis3Jours'
        ));
    }
}
