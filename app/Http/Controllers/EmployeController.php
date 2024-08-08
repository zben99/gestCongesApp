<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use App\Models\Departement;
use App\Models\Poste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeController extends Controller
{
    public function index()
    {
        $employes = Employe::all();
        return view('employes.index', compact('employes'));
    }

    public function create()
    {
        $departements = Departement::all();
        $postes = Poste::all();
        return view('employes.create', compact('departements', 'postes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email|unique:employes',
            'password' => 'required|min:8',
            'profil' => 'required',
            'departementId' => 'required|integer',
            'posteId' => 'required|integer',
        ]);

        $employe = Employe::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profil' => $request->profil,
            'departementId' => $request->departementId,
            'posteId' => $request->posteId,
            'dateArrive' => $request->dateArrive,
            'initial' => $request->initial,
            'pris' => $request->pris,
            'reste' => $request->reste,
        ]);

        $employe->assignRole($request->profil);

        return redirect()->route('employes.index')->with('success', 'Employé créé avec succès.');
    }

    public function edit($id)
    {
        $employe = Employe::findOrFail($id);
        $departements = Departement::all();
        $postes = Poste::all();
        return view('employes.edit', compact('employe', 'departements', 'postes'));
    }

    public function update(Request $request, Employe $employe)
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email|unique:employes,email,' . $employe->id,
            'profil' => 'required',
            'departementId' => 'required|integer',
            'posteId' => 'required|integer',
        ]);

        $employe->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'profil' => $request->profil,
            'departementId' => $request->departementId,
            'posteId' => $request->posteId,
            'dateArrive' => $request->dateArrive,
            'initial' => $request->initial,
            'pris' => $request->pris,
            'reste' => $request->reste,
        ]);

        $employe->syncRoles($request->profil);

        return redirect()->route('employes.index')->with('success', 'Employé mis à jour avec succès.');
    }


    public function destroy(Employe $employe)
    {
        $employe->delete();
        return redirect()->route('employes.index')->with('success', 'Employé supprimé avec succès.');
    }
}
