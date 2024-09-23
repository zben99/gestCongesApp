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
    
        $employeesQuery = User::where('profil', 'employés')->with('rh');

        if ($search) {
            $employeesQuery->where(function($query) use ($search) {
                $query->where('matricule', 'like', "%{$search}%")
                      ->orWhere('nom', 'like', "%{$search}%")
                      ->orWhere('prenom', 'like', "%{$search}%");
            });
        }
    
        $employees = $employeesQuery->paginate(10);

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

        $employeeId = $request->input('employee_id');
        $managerId = $request->input('manager_id');
        $rhId = $request->input('rh_id');

        // Assigner le manager
        if ($managerId) {
            DB::table('user_manager')->updateOrInsert(
                ['user_id' => $employeeId],
                ['manager_id' => $managerId, 'updated_at' => now()]
            );
        }

        // Assigner le responsable RH
        if ($rhId) {
            DB::table('user_manager')->updateOrInsert(
                ['user_id' => $employeeId],
                ['rh_id' => $rhId, 'updated_at' => now()]
            );
        }

        return redirect()->route('user-manager.index')->with('success', 'Manager et Responsable RH assignés avec succès.');
    }

    // Afficher le formulaire pour changer le manager et le responsable RH
    public function showChangeForm(User $employee)
    {
        $currentManager = DB::table('user_manager')
            ->where('user_id', $employee->id)
            ->value('manager_id');
        
        $managers = User::where('profil', 'manager')->get();
        $currentRh = DB::table('user_manager')
            ->where('user_id', $employee->id)
            ->value('rh_id');
        $rhs = User::where('profil', 'responsables RH')->get();

        return view('user_manager.change', compact('employee', 'managers', 'rhs', 'currentManager', 'currentRh'));
    }

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

        DB::beginTransaction();

        try {
            // Changer le manager
            if ($newManagerId) {
                DB::table('user_manager')->updateOrInsert(
                    ['user_id' => $employeeId],
                    ['manager_id' => $newManagerId, 'updated_at' => now()]
                );
            } else {
                DB::table('user_manager')
                    ->where('user_id', $employeeId)
                    ->update(['manager_id' => null, 'updated_at' => now()]);
            }

            // Changer le responsable RH
            if ($newRhId) {
                DB::table('user_manager')->updateOrInsert(
                    ['user_id' => $employeeId],
                    ['rh_id' => $newRhId, 'updated_at' => now()]
                );
            } else {
                DB::table('user_manager')
                    ->where('user_id', $employeeId)
                    ->update(['rh_id' => null, 'updated_at' => now()]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('user-manager.index')->with('error', 'Erreur lors du changement du Manager ou du Responsable RH.');
        }

        return redirect()->route('user-manager.index')->with('success', 'Manager et Responsable RH changés avec succès.');
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
    


    // Afficher le formulaire pour assigner un manager à un responsable RH
    public function showAssignManagerRhForm()
    {
        $managers = User::where('profil', 'manager')->get();
        $rhs = User::where('profil', 'responsables RH')->get();

        return view('user_manager.assign_manager_rh', compact('managers', 'rhs'));
    }

    // Assigner un manager à un responsable RH
public function assignManagerToRh(Request $request)
{
    $request->validate([
        'manager_id' => 'required|exists:users,id',
        'rh_id' => 'required|exists:users,id',
    ]);

    DB::table('user_manager')->updateOrInsert(
        ['manager_id' => $request->manager_id],
        ['rh_manager_id' => $request->rh_id, 'updated_at' => now()]
    );

    return redirect()->route('user-manager.voirmanagerRh')->with('success', 'Manager associé au Responsable RH avec succès.');
}


// Afficher le formulaire pour changer le responsable RH d'un manager
public function showChangeManagerRhForm(User $manager)
{
    $currentRhId = DB::table('user_manager')
        ->where('manager_id', $manager->id)
        ->value('rh_manager_id');

    $currentRh = User::find($currentRhId); // Récupérer les informations du responsable RH actuel

    $rhs = User::where('profil', 'responsables RH')->get();
    $managers = User::where('profil', 'manager')->get(); // Récupérer tous les managers

    return view('user_manager.change_manager_rh', compact('manager', 'rhs', 'currentRh', 'managers'));
}





// Changer le responsable RH d'un manager
public function changeManagerToRh(Request $request)
{
    $request->validate([
        'manager_id' => 'required|exists:users,id',
        'rh_id' => 'nullable|exists:users,id',
    ]);

    $managerId = $request->input('manager_id');
    $newRhId = $request->input('rh_id');

    DB::beginTransaction();

    try {
        if ($newRhId) {
            DB::table('user_manager')->updateOrInsert(
                ['manager_id' => $managerId],
                ['rh_manager_id' => $newRhId, 'updated_at' => now()] // Mise à jour de rh_manager_id
            );
        } else {
            DB::table('user_manager')
                ->where('manager_id', $managerId)
                ->update(['rh_manager_id' => null, 'updated_at' => now()]); // Remplacement de rh_id par rh_manager_id
        }

        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('user-manager.voirmanagerRh')->with('error', 'Erreur lors du changement du Responsable RH.');
    }

    return redirect()->route('user-manager.voirmanagerRh')->with('success', 'Responsable RH changé avec succès.');
}

}
