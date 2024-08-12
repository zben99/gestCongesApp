<?php

use App\Models\Post;
use App\Models\User;
use App\Models\FormulaireContact;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PosteController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\FormulaireContactController;
use App\Http\Controllers\CongesController;
use App\Http\Controllers\AbsenceControlleur;
use App\Http\Controllers\UserManagerController;





Route::get('/', function () {
    $nombreUsers = User::count();
    //$nombrePosts = Post::count();
   // $nombreContacts = FormulaireContact::count();

    return view('dashboard', compact('nombreUsers'));

})->middleware(['auth', 'verified'])->name('dashboard');




Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    // Route pour la mise à jour du profil
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Route pour la mise à jour du mot de passe
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // La route-ressource => Les routes "post.*"
    Route::resource("posts", PostController::class);

    Route::resource("admins", AdminController::class);

    Route::delete('/admins/{user}', [AdminController::class, 'destroy'])->name('admins.destroy');

    Route::resource("contacts", FormulaireContactController::class);

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
    Route::post('/user-manager/assign', [UserManagerController::class, 'assign'])->name('user-manager.assign');
    Route::post('/user-manager/remove', [UserManagerController::class, 'remove'])->name('user-manager.remove');

});








Route::get('/conges', [CongesController::class, 'index'])->name('conges.index');
Route::get('/conges/create', [CongesController::class, 'create'])->name('conges.create');
Route::post('/conges', [CongesController::class, 'store'])->name('conges.store');
Route::get('/conges/{id}', [CongesController::class, 'show'])->name('conges.show');
Route::put('/conges/{id}/approve', [CongesController::class, 'approve'])->name('conges.approve');
Route::put('/conges/{id}/approve-manager', [CongesController::class, 'approveByManager'])->name('conges.approveByManager');
Route::put('/conges/{id}/approve-rh', [CongesController::class, 'approveByRh'])->name('conges.approveByRh');
Route::put('/conges/{id}/reject', [CongesController::class, 'reject'])->name('conges.reject');



// Route pour afficher la liste des employés
Route::get('/employes', [EmployeController::class, 'index'])->name('employes.index');
// Route pour afficher le formulaire de création d'un nouvel employé
Route::get('/employes/create', [EmployeController::class, 'create'])->name('employes.create');
// Route pour enregistrer un nouvel employé
Route::post('/employes', [EmployeController::class, 'store'])->name('employes.store');
// Route pour afficher le formulaire d'édition d'un employé
Route::get('/employes/{id}/edit', [EmployeController::class, 'edit'])->name('employes.edit');
// Route pour mettre à jour un employé
Route::put('/employes/{employe}', [EmployeController::class, 'update'])->name('employes.update');
// Route pour supprimer un employé
Route::delete('/employes/{employe}', [EmployeController::class, 'destroy'])->name('employes.destroy');


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




require __DIR__.'/auth.php';
