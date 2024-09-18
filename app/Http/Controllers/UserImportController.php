<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Models\User;
use App\Imports\UsersImport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Rap2hpoutre\FastExcel\FastExcel;
use Maatwebsite\Excel\Validators\ValidationException;


class UserImportController extends Controller
{
    /**
     * Gère la demande d'importation des utilisateurs depuis un fichier Excel
     */

     public function import(Request $request)
     {
         // Validation du fichier uploadé
         $request->validate([
             'file' => 'required|file|mimes:xlsx,xls',
         ]);

         try {
             // Importation des utilisateurs avec FastExcel
             $fastexcel = new FastExcel();
             $ligneErreur = 1;

             $fastexcel->import($request->file('file'), function ($ligne) use (&$ligneErreur) {
                 $ligneErreur++; // Incrémente le compteur de lignes pour suivre la ligne courante

                 // Tenter de créer un utilisateur
                 try {
                     return User::create([
                         'nom' => $ligne['nom'],
                         'prenom' => $ligne['prenom'],
                         'matricule' => $ligne['matricule'],
                         'email' => $ligne['email'],
                         'password' => Hash::make('password'),
                         'telephone1' => $ligne['telephone1'],
                         'telephone2' => isset($ligne['telephone2']) ? (string)$ligne['telephone2'] : null,
                         'birth_date' => Carbon::parse($ligne['birth_date'])->format('Y-m-d'),
                         'profil' => 'employés',
                         'departementId' => $ligne['departementId'] ?? null,
                         'posteId' => $ligne['posteId'] ?? null,
                         'arrival_date' => Carbon::parse($ligne['arrival_date'])->format('Y-m-d'),
                         'initialization_date' => Carbon::parse($ligne['initialization_date'])->format('Y-m-d'),
                         'initial' => $ligne['initial'],
                     ]);
                 } catch (\Exception $e) {
                     // En cas d'erreur, lancer une exception avec la ligne en cause
                     throw new \Exception("Erreur à la ligne $ligneErreur : " . $e->getMessage());
                 }
             });

             // Redirection en cas de succès
             return redirect()->route('admins.index')->with('success', 'Utilisateurs importés avec succès');
         } catch (\Exception $e) {
             // En cas d'erreur, rediriger avec le message d'erreur et la ligne concernée
             return redirect()->back()->with('error', $e->getMessage());
         }
     }



}
