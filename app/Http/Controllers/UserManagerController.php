<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserManagerController extends Controller
{
    // Afficher la liste des employés
    public function index(Request $request)
    {
        $search = $request->input('search');

        $employeesQuery = User::where('profil', 'employés')-> orWhere('profil', 'manager');

        if ($search) {
            $employeesQuery->where(function($query) use ($search) {
                $query->where('matricule', 'like', "%{$search}%")
                      ->orWhere('nom', 'like', "%{$search}%")
                      ->orWhere('prenom', 'like', "%{$search}%");
            });
        }

        $employees = $employeesQuery->paginate(10);
        $managers = User::where('profil', 'manager')->get(); // Récupérer nom et prénom avec l'ID;
        $rhs = User::where('profil', 'responsables RH')->get(); // Récupérer nom et prénom avec l'ID;

            // Formater pour avoir "Nom Prénom" comme valeur
            $managersFormatted = [];
            $managersFormatted[0] = 'Sélectionnez un manager';
            foreach ($managers as $manager) {
                $managersFormatted[$manager->id] = $manager->nom . ' ' . $manager->prenom;
            }

            $rhsFormatted = [];
            $rhsFormatted[0] = 'Sélectionnez un Responsable RH';
            foreach ($rhs as $rh) {
                $rhsFormatted[$rh->id] = $rh->nom . ' ' . $rh->prenom;
            }


        return view('user_manager.index', compact('employees', 'managersFormatted', 'rhsFormatted'));
    }



    // Assigner un manager et un responsable RH à un employé
    public function assign(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $user=User::find($request->input('employee_id'));
        $user->manager_id= $request->input('manager_id');
        $user->save();


        return redirect()->route('user-manager.index')->with('success', 'Manager et Responsable RH assignés avec succès.');
    }



    public function voirManagerRh(Request $request)
    {
        $userManagers = DB::table('user_manager')
            ->join('users as managers', 'user_manager.manager_id', '=', 'managers.id')
            ->leftJoin('users as rhs', 'user_manager.rh_manager_id', '=', 'rhs.id') // Utiliser leftJoin pour inclure les managers sans responsable RH
            ->select('user_manager.*', 'managers.nom as manager_nom', 'managers.prenom as manager_prenom', 'rhs.nom as rh_nom', 'rhs.prenom as rh_prenom')
            ->get();

        return view('user_manager.voirmanagerRh', compact('userManagers'));
    }





    // Assigner un manager à un responsable RH
public function assignManagerToRh(Request $request)
{
    $request->validate([
        'employee_id' => 'required|exists:users,id',
        'rh_id' => 'nullable|exists:users,id',
    ]);

    $user=User::find($request->input('employee_id'));
    $user->rh_id= $request->input('rh_id');
    $user->save();

    return redirect()->route('user-manager.voirmanagerRh')->with('success', 'Manager associé au Responsable RH avec succès.');
}




}
