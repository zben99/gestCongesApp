<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Absence;
use App\Models\TypeAbsences;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Notifications\AbsenceStatusNotification;

class AbsenceControlleur extends Controller
{
   // Afficher la liste des absences
   public function index(Request $request)
   {
       $query = Absence::query();
       $user = auth()->user();

       // Récupérer le terme de recherche
       $search = $request->input('search');

       // Condition pour les administrateurs et responsables RH
       if ($user->profil == 'administrateurs' || $user->profil == 'responsables RH') {
           $query = Absence::with('user');
       }
       // Condition pour les managers
       else if ($user->profil == 'manager') {
           // Récupérer les absences des employés supervisés ainsi que celles du manager
           $query = Absence::where(function($q) use ($user) {
               $q->where('approved_by', $user->id) // Absences à approuver par le manager
                 ->orWhere('UserId', $user->id);   // Absences du manager lui-même
           })->with('user');
       }
       // Sinon, montrer seulement ses propres absences
       else {
           $query = Absence::where('UserId', $user->id)->with('user');
       }

       // Ajouter la condition de recherche si un terme est fourni
       if ($search) {
           $query->whereHas('user', function($q) use ($search) {
               $q->where('matricule', 'LIKE', "%$search%")
                 ->orWhereRaw("CONCAT(prenom, ' ', nom) LIKE ?", ["%$search%"]);
           })
           ->orWhere('motif', 'LIKE', "%$search%");
       }

       $absences = $query->paginate(2); // Pagination avec 2 absences par page

       return view('absence.index', compact('absences'));
   }

   // Afficher le formulaire de création
   public function create()
   {
       $users = User::where('profil', 'employés')->get(); // Récupérer uniquement les employés pour le formulaire
       $typeAbsences = TypeAbsences::all();

       return view('absence.edit', compact('users', 'typeAbsences'));
   }

   // Stocker une nouvelle absence
   public function store(Request $request)
   {
       // Validation des données
       $validator = Validator::make($request->all(), [
           'type_absence_id' => 'required|exists:type_absences,id', // Validation pour le type d'absence
           'motif' => 'required|string|max:255',
           'dateDebut' => 'required|date',
           'dateFin' => 'required|date|after_or_equal:dateDebut',
       ]);

         // Si la validation échoue
         if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }



       // Récupérer l'utilisateur connecté
       $user = auth()->user();

       // Récupérer le type d'absence
       $typeAbsence = TypeAbsences::find($request->input('type_absence_id'));

       // Validation spécifique selon le type d'absence
       $dureeAbsence = $this->calculateDays( $request->input('dateDebut'), $request->input('dateFin'));

       if ($typeAbsence->duree_max > 0 && $dureeAbsence > $typeAbsence->duree_max) {
           return redirect()->back()->withErrors(['dateFin' => 'La durée de l\'absence dépasse la durée maximale autorisée pour ce type d\'absence.'])->withInput();
       }

       if ($typeAbsence->justificatif_requis && !$request->hasFile('justificatif')) {
           return redirect()->back()->withErrors(['justificatif' => 'Un justificatif est requis pour ce type d\'absence.'])->withInput();
       }

       // Création de l'absence
       $absence = new Absence();
       $absence->UserId = $user->id; // Associer l'absence à l'utilisateur connecté
       $absence->motif = $request->input('motif');
       $absence->dateDebut = $request->input('dateDebut');
       $absence->dateFin = $request->input('dateFin');
       $absence->status = 'en attente'; // Définir le statut par défaut
       $absence->commentaire = $request->input('commentaire');
       $absence->type_absence_id = $typeAbsence->id;

       // Associer l'absence au premier manager de l'utilisateur connecté
       if ($user->managers->isNotEmpty()) {
           $absence->approved_by = $user->managers->first()->id;
       }

       $absence->save();

       return redirect()->route('absences.index')->with('success', 'Absence créée avec succès');
   }

   // Afficher une absence spécifique
   public function show(Absence $absence)
   {
       return view('absence.show', compact('absence'));
   }

   // Afficher le formulaire d'édition
   public function edit(Absence $absence)
   {
       $users = User::all(); // Récupère tous les utilisateurs
       $typeAbsences = TypeAbsences::all(); // Récupère tous les types d'absences
       $connectedUser = auth()->user(); // Récupère l'utilisateur connecté
       return view('absence.edit', compact('absence', 'users', 'typeAbsences', 'connectedUser'));
   }

   // Mettre à jour une absence
   public function update(Request $request, Absence $absence)
   {
       // Validation des données
       $validator = Validator::make($request->all(), [
           'UserId' => 'required|exists:users,id',
           'type_absence_id' => 'required|exists:type_absences,id', // Validation pour le type d'absence
           'motif' => 'required|string|max:255',
           'dateDebut' => 'required|date',
           'dateFin' => 'required|date|after_or_equal:dateDebut',
           'commentaire' => 'nullable|string|max:500', // Validation pour le commentaire
       ]);

       if ($validator->fails()) {
           return redirect()->back()->withErrors($validator)->withInput();
       }

       // Vérifier si l'utilisateur a le droit de modifier cette absence
       $user = auth()->user();
       if ($user->profil !== 'administrateur' && $user->id !== $absence->UserId) {
           return redirect()->route('absences.index')->with('error', 'Vous n\'avez pas l\'autorisation de modifier cette absence');
       }

       // Mise à jour de l'absence
       $absence->UserId = $request->input('UserId');
       $absence->motif = $request->input('motif');
       $absence->dateDebut = Carbon::parse($request->input('dateDebut'));
       $absence->dateFin = Carbon::parse($request->input('dateFin'));
       $absence->commentaire = $request->input('commentaire');
       $absence->type_absence_id = $request->input('type_absence_id');

       // Sauvegarder les modifications
       $absence->save();

       // Redirection avec message de succès
       return redirect()->route('absences.index')->with('success', 'Absence mise à jour avec succès');
   }

   // Supprimer une absence
   public function destroy(Absence $absence)
   {
       if (!$absence) {
           return redirect(route('absences.index'))->with('error', 'Absence non trouvée');
       }

       $absence->delete();

       return redirect(route('absences.index'))->with('success', 'Absence supprimée avec succès');
   }

   public function validateRequest($id)
   {
       $absence = Absence::findOrFail($id);
       $absence->status = 'approuvé';
       $absence->approved_by = auth()->user()->id; // Ajouter l'ID de l'utilisateur qui approuve
       $absence->save();

       // Envoyer une notification par email à l'utilisateur
       $absence->user->notify(new AbsenceStatusNotification($absence, 'approuvé'));

       return redirect()->route('absences.index')->with('success', 'La demande d\'absence a été validée.');
   }

   public function rejectRequest($id)
   {
       $absence = Absence::findOrFail($id);
       $absence->status = 'refusé'; // Assurez-vous que 'refusé' est bien dans les valeurs acceptées
       $absence->approved_by = auth()->user()->id; // Ajouter l'ID de l'utilisateur qui rejette
       $absence->save();

       // Envoyer une notification par email à l'utilisateur
       $absence->user->notify(new AbsenceStatusNotification($absence, 'refusé'));

       return redirect()->route('absences.index')->with('success', 'La demande d\'absence a été rejetée.');
   }

   public function absencesList()
   {
       $userId = auth()->user()->id;
       $absences = Absence::where('UserId', $userId)->with('typeAbsence')->get();

       return view('absences.liste', compact('absences'));
   }


   private function calculateDays($dateDebut, $dateFin)
{
    return (new \DateTime($dateFin))->diff(new \DateTime($dateDebut))->days + 1;
}

}
