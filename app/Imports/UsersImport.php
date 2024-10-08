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
     * Définir les règles de validation pour chaque ligne
     */
    public function rules(): array
    {
        return [
            'nom' => 'nullable|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'matricule' => 'nullable|unique:users,matricule',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'nullable|min:8',
            'telephone1' => 'nullable',
            'telephone2' => 'nullable',
            'birth_date' => 'nullable',
            'profil' => 'nullable|string|max:255',
            'departementId' => 'nullable|integer|exists:departements,id',
            'posteId' => 'nullable|integer|exists:postes,id',
            'arrival_date' => 'nullable',
            'initialization_date' => 'nullable',
            'initial' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Traite chaque ligne du fichier importé et crée un utilisateur
     */
    public function model(array $row)
    {
        return new User([
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'matricule' => $row['matricule'],
            'email' => $row['email'],
            'password' => Hash::make('password'), // Hash du mot de passe
            'telephone1' => $row['telephone1'],
            'telephone2' => isset($row['telephone2']) ? (string) $row['telephone2'] : null, // Gestion de la valeur nullable
            'birth_date' => $row['birth_date'],
            'profil' => 'employés',
           'departementId' => $row['departementId'] ?? null,
        'posteId' => $row['posteId'] ?? null,
            'arrival_date' => $row['arrival_date'],
            'initialization_date' => $row['initialization_date'],
            'initial' => $row['initial'],
        ]);
    }
}
