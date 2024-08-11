<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserManagerController extends Controller
{
    // Afficher l'interface pour gérer les relations
    public function index()
    {
        $employees = User::where('profil', 'employés')->get();
        $managers = User::where('profil', 'manager')->get();

        return view('user_manager.index', compact('employees', 'managers'));
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

        $employee->managers()->syncWithoutDetaching([$manager->id]);

        return redirect()->route('user-manager.index')->with('success', 'Manager assigné avec succès.');
    }

    // Retirer un manager d'un employé
    public function remove(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'manager_id' => 'required|exists:users,id',
        ]);

        $employee = User::find($request->input('employee_id'));
        $manager = User::find($request->input('manager_id'));

        $employee->managers()->detach($manager->id);

        return redirect()->route('user-manager.index')->with('success', 'Manager retiré avec succès.');
    }
}
