<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * Define the validation rules for each row of data
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'matricule' => 'required|unique:users,matricule',
            'departement_id' => 'required|integer',
            'poste_id' => 'required|integer',
            // Ajoutez ici d'autres règles de validation si nécessaire
        ];
    }

    /**
     * Process the import data
     */
    public function model(array $row)
    {
        return new User([
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'email' => $row['email'],
            'password' => Hash::make($row['password']), // Hash du mot de passe
            'matricule' => $row['matricule'],
            'telephone1' => $row['telephone1'],
            'telephone2' => $row['telephone2'],
            'birth_date' => $row['date_de_naissance'],
            'profil' => $row['profil'],
            'departementId' => $row['departement_id'],
            'posteId' => $row['poste_id'],
            'arrival_date' => $row['date_arrivee'],
            'initialization_date' => $row['date_initialisation'],
            'initial' => $row['conge_initial'],
        ]);
    }
}
