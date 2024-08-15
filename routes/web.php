<?php


use App\Models\User;
use App\Models\Absence;
use App\Models\Conges;
use App\Notifications\AbsenceStatusNotification;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PosteController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepartementController;

use App\Http\Controllers\CongesController;
use App\Http\Controllers\AbsenceControlleur;
use App\Http\Controllers\UserManagerController;
use App\Http\Controllers\DashboardController;





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
    $congesEnAttente = Conges::where('status', 'en attente')->count();
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




Route::middleware('auth')->group(function () {
    

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    // Route pour la mise à jour du profil
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Route pour la mise à jour du mot de passe
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // La route-ressource => Les routes "post.*"

    Route::resource("admins", AdminController::class);

    Route::delete('/admins/{user}', [AdminController::class, 'destroy'])->name('admins.destroy');



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

    Route::get('/user-manager', [UserManagerController::class, 'index'])->name('user-manager.index');
    Route::get('/user-manager/assign/{employee}', [UserManagerController::class, 'showAssignForm'])->name('user-manager.assign-form');
    Route::post('/user-manager/assign', [UserManagerController::class, 'assign'])->name('user-manager.assign');
    Route::get('/user-manager/change/{employee}', [UserManagerController::class, 'showChangeForm'])->name('user-manager.change-form');
    Route::post('/user-manager/change', [UserManagerController::class, 'change'])->name('user-manager.change');

    Route::get('/conges', [CongesController::class, 'index'])->name('conges.index');
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

    Route::get('/test-email', function () {
        $user = User::find(1); // Remplacez par l'ID de l'utilisateur à tester
        $absence = Absence::find(1); // Remplacez par l'ID d'une absence existante

        // Envoyer une notification d'approbation
        $user->notify(new AbsenceStatusNotification($absence, 'approuvé'));

        return 'Email de test envoyé !';
    });

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
