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
        
        // Commencez par la requête de base pour obtenir les employés
        $employeesQuery = User::where('profil', 'employés');
        
        // Appliquez les filtres de recherche si un terme de recherche est fourni
        if ($search) {
            $employeesQuery->where(function($query) use ($search) {
                $query->where('matricule', 'like', "%{$search}%")
                      ->orWhere('nom', 'like', "%{$search}%")
                      ->orWhere('prenom', 'like', "%{$search}%");
            });
        }
        
        // Obtenez les résultats filtrés
        $employees = $employeesQuery->get();
        
        // Retournez la vue avec les employés filtrés
        return view('user_manager.index', compact('employees'));
    }

    // Afficher le formulaire pour assigner un manager
    public function showAssignForm(User $employee)
    {
        $managers = User::where('profil', 'manager')->get();

        return view('user_manager.assign', compact('employee', 'managers'));
    }

    // Assigner un manager à un employé
    public function assign(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'manager_id' => 'required|exists:users,id',
        ]);

        $employee = User::find($request->input('employee_id'));
        $manager = User::find($request->input('manager_id'));

        // Assigner le manager à l'employé
        $employee->managers()->syncWithoutDetaching([$manager->id]);

        return redirect()->route('user-manager.index')->with('success', 'Manager assigné avec succès.');

    }

    // Afficher le formulaire pour changer le manager
    public function showChangeForm(User $employee)
    {
        $managers = User::where('profil', 'manager')->get();

        return view('user_manager.change', compact('employee', 'managers'));
    }

    // Changer le manager de l'employé
    public function change(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'manager_id' => 'required|exists:users,id',
        ]);

        $employee = User::find($request->input('employee_id'));
        $newManager = User::find($request->input('manager_id'));

        // Détacher tous les managers existants
        $employee->managers()->detach();

        // Assigner le nouveau manager
        $employee->managers()->syncWithoutDetaching([$newManager->id]);

        return redirect()->route('user-manager.index')->with('success', 'Manager changé avec succès.');
    }
}
