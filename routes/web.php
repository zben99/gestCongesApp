<?php


use App\Models\User;
use App\Models\Conges;
use App\Models\Absence;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MainController;
use App\Http\Controllers\AdminController;

use App\Http\Controllers\PosteController;
use App\Http\Controllers\CongesController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AbsenceControlleur;
use App\Http\Controllers\RapportCongesController;
use App\Http\Controllers\UserImportController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TypeCongesController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\UserManagerController;
use App\Http\Controllers\TypeAbsencesController;
use App\Notifications\AbsenceStatusNotification;
use App\Http\Controllers\RapportAbsenceController;

use App\Exports\CongesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\CongeAlertService;









Route::middleware('auth')->group(function () {

    // routes/web.php

    Route::get('/', function () {
        $nombreUsers = User::count();
        $totalDemandesAbsence = Absence::count();
        $totalAbsencesValides = Absence::where('status', 'approuvé')->count();
        $totalAbsencesEnAttente = Absence::where('status', 'en attente')->count();
        $totalAbsencesrejete = Absence::where('status', 'refusé')->count();
        $totalAbsencesEnAttenteDepuis3Jours = Absence::where('status', 'en attente')
                                                     ->where('created_at', '<', now()->subDays(3))
                                                     ->count();
    
            // Statistiques en temps réel
        $totalConges = Conges::count();
        $congesApprouves = Conges::where('status', 'approuvé')->count();
        $congesEnAttente = Conges::whereIn('status', ['en attente', 'en attente RH'])->count();
        $congerejete = Conges::where('status', 'refusé')->count();
        $congesEnAttenteDepuisTroisJours = Conges::where('status', 'en attente')
                                                 ->where('created_at', '<=', now()->subDays(3))
                                                 ->count();
    
    
        return view('dashboard', compact(
                'nombreUsers',
                'totalDemandesAbsence',
                'totalAbsencesValides',
                'totalAbsencesEnAttente',
                'totalAbsencesEnAttenteDepuis3Jours',
                'totalAbsencesrejete',
                'totalConges',
                'congerejete',
                'congesApprouves',
                'congesEnAttente',
                'congesEnAttenteDepuisTroisJours'
            ));
    
    })->middleware(['auth', 'verified'])->name('dashboard');
    
    


    Route::get('/test-conge-alerts', function (CongeAlertService $congeAlertService) {
        $congeAlertService->sendAlerts();
    
        return 'Les emails d\'alerte ont été envoyés pour les utilisateurs concernés.';
    });
    
    
    Route::get('/test-email', function () {
        $user = User::find(1); // Remplacez par l'ID de l'utilisateur à tester
        $absence = Absence::find(1); // Remplacez par l'ID d'une absence existante

        // Envoyer une notification d'approbation
        $user->notify(new AbsenceStatusNotification($absence, 'approuvé'));

        return 'Email de test envoyé !';
    });

    Route::get('/rapports/export', function () {
        return Excel::download(new CongesExport, 'conges.xlsx');
    })->name('rapports.export');

    // Route pour l'exportation des congés du mois prochain
    Route::get('/conges/mois-prochain/export', [RapportCongesController::class, 'export'])->name('rapports.export1');

        
    Route::get('admins/import', [UserImportController::class, 'showImportForm'])->name('admins.import.form');
    Route::post('admins/import', [UserImportController::class, 'import'])->name('admins.import');

    
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route pour la mise à jour du profil
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Route pour la mise à jour du mot de passe
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/rapports', [RapportCongesController::class, 'index'])->name('rapports.index');
    Route::get('/rapports/en-cours', [RapportCongesController::class, 'enCours'])->name('rapports.enCours');
    Route::get('/rapports/mois-prochain', [RapportCongesController::class, 'moisProchain'])->name('rapports.moisProchain');
    Route::get('/rapports/en-attente', [RapportCongesController::class, 'enAttente'])->name('rapports.enAttente');
    Route::get('/rapports/departements', [RapportCongesController::class, 'departements'])->name('rapports.departements');

  

    Route::get('/rapportsAbsences', [RapportAbsenceController::class, 'index'])->name('rapportsAbsences.index');
    Route::get('/rapportsAbsences/enCours', [RapportAbsenceController::class, 'enCours'])->name('rapportsAbsences.enCours');
    Route::get('/rapportsAbsences/enAttente', [RapportAbsenceController::class, 'enAttente'])->name('rapportsAbsences.enAttente');
    Route::get('/rapportsAbsences/departements', [RapportAbsenceController::class, 'departements'])->name('rapportsAbsences.departements');
    Route::get('/rapportsAbsences/moisProchain', [RapportAbsenceController::class, 'moisProchain'])->name('rapportsAbsences.moisProchain');


    // La route-ressource => Les routes "post.*"

    Route::resource("admins", AdminController::class);

    Route::delete('/admins/{user}', [AdminController::class, 'destroy'])->name('admins.destroy');


    Route::get('/absences/attente', [AbsenceControlleur::class, 'absencesEnAttente'])->name('absences.attente');
    Route::put('/absences/{id}/reject', [AbsenceControlleur::class, 'rejectRequest'])->name('absences.rejectRequest');
    Route::put('/absences/{id}/validate', [AbsenceControlleur::class, 'validateRequest'])->name('absences.validateRequest');
    // Afficher la liste des absences
    Route::get('/absences', [AbsenceControlleur::class, 'index'])->name('absences.index');
    // Afficher le formulaire de création d'une absence
    Route::get('/absences/create', [AbsenceControlleur::class, 'create'])->name('absences.create');
    // Enregistrer une nouvelle absence
    Route::post('/absences', [AbsenceControlleur::class, 'store'])->name('absences.store');
    // Afficher les détails d'une absence spécifique
    Route::get('/absences/{absence}', [AbsenceControlleur::class, 'show'])->name('absences.show');
    // Afficher le formulaire d'édition d'une absence
    Route::get('/absences/{absence}/edit', [AbsenceControlleur::class, 'edit'])->name('absences.edit');
    // Mettre à jour une absence existante
    Route::put('/absences/{absence}', [AbsenceControlleur::class, 'update'])->name('absences.update');
    // Supprimer une absence
    Route::delete('/absences/{absence}', [AbsenceControlleur::class, 'destroy'])->name('absences.destroy');

    // web.php

    Route::get('/user-manager', [UserManagerController::class, 'index'])->name('user-manager.index');
    Route::get('/user-manager/assign/{employee}', [UserManagerController::class, 'showAssignForm'])->name('user-manager.assign-form');
    Route::post('/user-manager/assign', [UserManagerController::class, 'assign'])->name('user-manager.assign');
    Route::get('/user-manager/change/{employee}', [UserManagerController::class, 'showChangeForm'])->name('user-manager.change-form');
    Route::post('/user-manager/change', [UserManagerController::class, 'change'])->name('user-manager.change');

    Route::get('/conges', [CongesController::class, 'index'])->name('conges.index');

    Route::get('/conges/liste_conges', [AdminController::class, 'liste_conges'])->name('conges.liste_conges');


    Route::get('/conges/create', [CongesController::class, 'create'])->name('conges.create');
    Route::post('/conges', [CongesController::class, 'store'])->name('conges.store');
    Route::get('/conges/{conge}/edit', [CongesController::class, 'edit'])->name('conges.edit');
    Route::put('/conges/{conge}', [CongesController::class, 'update'])->name('conges.update');

    Route::get('/conges/{conge}', [CongesController::class, 'show'])->name('conges.show');
    Route::get('/conges/{conge}/approve', [CongesController::class, 'approve'])->name('conges.approve');
    Route::get('/conges/{conge}/approve-manager', [CongesController::class, 'approveByManager'])->name('conges.approveByManager');
    Route::get('/conges/{conge}/approve-rh', [CongesController::class, 'approveByRh'])->name('conges.approveByRh');
    Route::get('/conges/{conge}/reject', [CongesController::class, 'reject'])->name('conges.reject');

    Route::delete('/conges/{conge}', [CongesController::class, 'destroy'])->name('conges.destroy');




    Route::resource('type/conges', TypeCongesController::class)->names([
        'index' => 'typeConges.index',
        'create' => 'typeConges.create',
        'store' => 'typeConges.store',
        'show' => 'typeConges.show',
        'edit' => 'typeConges.edit',
        'update' => 'typeConges.update',
        'destroy' => 'typeConges.destroy',
    ]);
    Route::resource('type/absences', TypeAbsencesController::class)->names([
        'index' => 'typeAbsences.index',
        'create' => 'typeAbsences.create',
        'store' => 'typeAbsences.store',
        'show' => 'typeAbsences.show',
        'edit' => 'typeAbsences.edit',
        'update' => 'typeAbsences.update',
        'destroy' => 'typeAbsences.destroy',
    ]);


    // Route pour afficher la liste des départements
    Route::get('/departements', [DepartementController::class, 'index'])->name('departements.index');
    // Route pour afficher le formulaire de création d'un nouveau département
    Route::get('/departements/create', [DepartementController::class, 'create'])->name('departements.create');
    // Route pour enregistrer un nouveau département
    Route::post('/departements', [DepartementController::class, 'store'])->name('departements.store');
    // Route pour afficher le formulaire d'édition d'un département
    Route::get('/departements/{departement}/edit', [DepartementController::class, 'edit'])->name('departements.edit');
    // Route pour mettre à jour un département
    Route::put('/departements/{departement}', [DepartementController::class, 'update'])->name('departements.update');
    // Route pour supprimer un département
    Route::delete('/departements/{departement}', [DepartementController::class, 'destroy'])->name('departements.destroy');

    // Route pour afficher la liste des postes
    Route::get('/postes', [PosteController::class, 'index'])->name('postes.index');
    // Route pour afficher le formulaire de création d'un nouveau poste
    Route::get('/postes/create', [PosteController::class, 'create'])->name('postes.create');
    // Route pour enregistrer un nouveau poste
    Route::post('/postes', [PosteController::class, 'store'])->name('postes.store');
    // Route pour afficher le formulaire d'édition d'un poste
    Route::get('/postes/{poste}/edit', [PosteController::class, 'edit'])->name('postes.edit');
    // Route pour mettre à jour un poste
    Route::put('/postes/{poste}', [PosteController::class, 'update'])->name('postes.update');
    // Route pour supprimer un poste
    Route::delete('/postes/{poste}', [PosteController::class, 'destroy'])->name('postes.destroy');


});

require __DIR__.'/auth.php';
