<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use Carbon\Carbon;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function index()
{
    $totalDemandesAbsence = Absence::count();
    $totalAbsencesValides = Absence::where('status', 'approuvé')->count();
    $totalAbsencesEnAttente = Absence::where('status', 'en attente')->count();
    $totalAbsencesEnAttenteDepuis3Jours = Absence::where('status', 'en attente')
        ->where('dateDebut', '<', Carbon::now()->subDays(3))
        ->count();

    dd($totalDemandesAbsence, $totalAbsencesValides, $totalAbsencesEnAttente, $totalAbsencesEnAttenteDepuis3Jours);

    return view('Dashboard', [
        'totalDemandesAbsence' => $totalDemandesAbsence,
        'totalAbsencesValides' => $totalAbsencesValides,
        'totalAbsencesEnAttente' => $totalAbsencesEnAttente,
        'totalAbsencesEnAttenteDepuis3Jours' => $totalAbsencesEnAttenteDepuis3Jours
    ]);
}

    
}
