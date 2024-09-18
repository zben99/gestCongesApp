<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserManagerController extends Controller
{
    // Afficher la liste des employés
    public function index(Request $request)
    {
        $search = $request->input('search');
    
        // Construire la requête pour récupérer les employés
        $employeesQuery = User::where('profil', 'employés') // Corriger le profil si nécessaire
            ->with('rh'); // Charger la relation pour le responsable RH
    
        // Appliquer le filtre de recherche si nécessaire
        if ($search) {
            $employeesQuery->where(function($query) use ($search) {
                $query->where('matricule', 'like', "%{$search}%")
                      ->orWhere('nom', 'like', "%{$search}%")
                      ->orWhere('prenom', 'like', "%{$search}%");
            });
        }
    
        // Optionnel : utiliser la pagination pour gérer un grand nombre d'employés
        $employees = $employeesQuery->paginate(10); // 10 employés par page
    
        return view('user_manager.index', compact('employees'));
    }
    
    // Afficher le formulaire pour assigner un manager et un responsable RH
    public function showAssignForm(User $employee)
    {
        $managers = User::where('profil', 'manager')->get();
        $rhs = User::where('profil', 'responsables RH')->get();

        return view('user_manager.assign', compact('employee', 'managers', 'rhs'));
    }

    // Assigner un manager et un responsable RH à un employé
    public function assign(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'manager_id' => 'nullable|exists:users,id',
            'rh_id' => 'nullable|exists:users,id',
        ]);

        $employee = User::find($request->input('employee_id'));
        $managerId = $request->input('manager_id');
        $rhId = $request->input('rh_id');

        // Assigner le manager s'il est spécifié
        if ($managerId) {
            \DB::table('user_manager')->updateOrInsert(
                ['user_id' => $employee->id, 'manager_id' => $managerId],
                ['updated_at' => now()]
            );
        }

        // Assigner le responsable RH s'il est spécifié
        if ($rhId) {
            \DB::table('user_manager')
                ->updateOrInsert(
                    ['user_id' => $employee->id],
                    ['rh_id' => $rhId, 'updated_at' => now()]
                );
        }

        return redirect()->route('user-manager.index')->with('success', 'Manager et Responsable RH assignés avec succès.');
    }

    // Afficher le formulaire pour changer le manager et le responsable RH
    public function showChangeForm(User $employee)
    {

        $currentManager = \DB::table('user_manager')
            ->where('user_id', $employee->id)
            ->value('manager_id');
        
        $managers = User::where('profil', 'manager')->get();
        $currentRh = \DB::table('user_manager')
            ->where('user_id', $employee->id)
            ->value('rh_id');
        $rhs = User::where('profil', 'responsables RH')->get();

        return view('user_manager.change', compact('employee', 'managers', 'rhs', 'currentManager', 'currentRh'));
    }

    // Changer le manager et le responsable RH de l'employé
// Changer le manager et le responsable RH de l'employé
public function change(Request $request)
{
    $request->validate([
        'employee_id' => 'required|exists:users,id',
        'manager_id' => 'nullable|exists:users,id',
        'rh_id' => 'nullable|exists:users,id',
    ]);

    $employeeId = $request->input('employee_id');
    $newManagerId = $request->input('manager_id');
    $newRhId = $request->input('rh_id');

    // Commencer une transaction pour garantir l'intégrité des données
    \DB::beginTransaction();

    try {
        // Changer le manager si un nouveau est spécifié
        if ($newManagerId) {
            \DB::table('user_manager')->updateOrInsert(
                ['user_id' => $employeeId],
                ['manager_id' => $newManagerId, 'updated_at' => now()]
            );
        } else {
            // Supprimer l'association manager si le champ est vide
            \DB::table('user_manager')
                ->where('user_id', $employeeId)
                ->update(['manager_id' => null, 'updated_at' => now()]);
        }

        // Changer le responsable RH si un nouveau est spécifié
        if ($newRhId) {
            \DB::table('user_manager')
                ->updateOrInsert(
                    ['user_id' => $employeeId],
                    ['rh_id' => $newRhId, 'updated_at' => now()]
                );
        } else {
            // Supprimer l'association responsable RH si le champ est vide
            \DB::table('user_manager')
                ->where('user_id', $employeeId)
                ->update(['rh_id' => null, 'updated_at' => now()]);
        }

        // Valider la transaction si tout se passe bien
        \DB::commit();
    } catch (\Exception $e) {
        // En cas d'erreur, annuler la transaction
        \DB::rollBack();
        return redirect()->route('user-manager.index')->with('error', 'Erreur lors du changement du Manager ou du Responsable RH.');
    }

    return redirect()->route('user-manager.index')->with('success', 'Manager et Responsable RH changés avec succès.');
}

}
