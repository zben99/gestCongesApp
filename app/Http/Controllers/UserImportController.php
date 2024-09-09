<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;


class UserImportController extends Controller
{
    /**
     * Affiche le formulaire d'importation des utilisateurs
     */
    public function showImportForm()
    {
        return view('administrateurs.import');
    }

    /**
     * Gère la demande d'importation des utilisateurs depuis un fichier Excel
     */
   
     public function import(Request $request)
     {
         // Validation du fichier uploadé
         $request->validate([
             'file' => 'required|file|mimes:xlsx,xls',
         ]);
     
         // Importation des utilisateurs
         try {
             Excel::import(new UsersImport, $request->file('file'));
     
             // Redirection en cas de succès
             return redirect()->route('admins.index')->with('success', 'Utilisateurs importés avec succès');
         } catch (ValidationException $e) {
             // Gérer les erreurs de validation spécifiques à l'importation Excel
             \Log::error('Validation Exception:', [
                 'errors' => $e->errors(),
                 'failures' => $e->failures(),
             ]);
     
             $failures = $e->failures();
             $errorMessages = [];
             foreach ($failures as $failure) {
                 $errorMessages[] = "Erreur à la ligne " . $failure->row() . ": " . implode(', ', $failure->errors());
             }
     
             return redirect()->back()->with('error', 'Erreur lors de l\'importation des utilisateurs : ' . implode(' | ', $errorMessages));
         } catch (\Exception $e) {
             // Gérer d'autres erreurs génériques
             \Log::error('Exception lors de l\'importation des utilisateurs:', ['message' => $e->getMessage()]);
     
             return redirect()->back()->with('error', 'Erreur lors de l\'importation des utilisateurs : ' . $e->getMessage());
         }
     }
     
    
}
