<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Conges;
use App\Models\TypeConges;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\CongeApprovalMail;
use Barryvdh\DomPDF\Facade\Pdf; // Assurez-vous d'avoir installé barryvdh/laravel-dompdf
use Illuminate\Support\Facades\Auth;


class CongesController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Conges::query();
    
        // Ajout des filtres de recherche pour le statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        // Gestion des profils
        if ($user->profil == 'administrateurs') {
            // Si l'utilisateur est administrateur, il peut voir toutes les demandes
            $conges = $query->paginate(7);
        } elseif ($user->profil == 'manager') {
            // Si le profil est manager, récupérer les congés des employés sous sa supervision ainsi que les siens
            $conges = $query->whereIn('UserId', $user->employees->pluck('id')->push($user->id))
                            ->paginate(7);
        } elseif ($user->profil == 'responsables RH') {
            // Récupérer les employés supervisés par ce responsable RH
            $employeeIds = $user->rhEmployees->pluck('id');
    
            // Récupérer les congés des employés sous la supervision RH ainsi que les siens
            $conges = $query->whereIn('UserId', $employeeIds->push($user->id))
                            ->paginate(7);
        } else {
            // Sinon, afficher uniquement les congés de l'utilisateur connecté
            $conges = $query->where('UserId', $user->id)->paginate(7);
        }
    
        return view('conges.index', compact('conges'));
    }
    
     
    public function create()
    {
        $users = User::all();
        $typeConges = TypeConges::all();
        return view('conges.edit', compact('users', 'typeConges'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'userId' => 'required|exists:users,id',
            'type_conge_id' => 'required|exists:type_conges,id',
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after_or_equal:dateDebut',
            'commentaire' => 'nullable',
        ]);

        $user = User::findOrFail($request->userId);
        $typeConge = TypeConges::findOrFail($request->type_conge_id);

        // Calcul du nombre de jours demandés
        $days1 = (new \DateTime($request->dateFin))->diff(new \DateTime($request->dateDebut))->days + 1;

        // Vérification de la durée maximale pour le type de congé
        if ($days1 > $typeConge->duree_max) {
            return redirect()->route('conges.index')->with('error', 'La durée demandée dépasse la durée maximale autorisée pour ce type de congé.');
        }

        // Calcul des jours de congé disponibles
        $days = (new \DateTime(now()))->diff(new \DateTime($user->initialization_date))->days + 1;
        $nbreConge = ($days * 2.5) / 30;
        $congeRestant = floor($nbreConge + $user->initial - $user->pris);

        // Vérification si le nombre de jours demandés dépasse les congés restants
        if ($days1 > $congeRestant) {
            return redirect()->route('conges.index')->with('error', 'Impossible de créer une demande avec ce nombre de jours.');
        }

        try {

            // Création de la demande de congé
            $conge = new Conges($request->all());
            $conge->status = 'en attente';
            $conge->save();

            return redirect()->route('conges.index')->with('success', 'Demande de congé soumise avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('conges.index')->with('error', 'Une erreur est survenue lors de la soumission de la demande.');
        }
    }


    public function edit(Conges $conge)
    {
        $users = User::all();
        $typeConges = TypeConges::all();
        return view('conges.edit', compact('conge','users','typeConges'));
    }


    public function update(Request $request, Conges $conge)
    {
        $request->validate([
            'type_conge_id' => 'required|exists:type_conges,id',
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after_or_equal:dateDebut',
            'commentaire' => 'nullable',
        ]);

        $user = User::findOrFail($conge->UserId);
        $typeConge = TypeConges::findOrFail($request->type_conge_id);

        // Calcul du nombre de jours demandés
        $days1 = $this->calculateDays($request->dateDebut, $request->dateFin);

        // Vérification de la durée maximale pour le type de congé
        if ($days1 > $typeConge->duree_max) {
            return redirect()->route('conges.index')->with('error', 'La durée demandée dépasse la durée maximale autorisée pour ce type de congé.');
        }

        // Calcul des jours de congé disponibles
        $days = $this->calculateDays($user->initialization_date, now());
        $nbreConge = ($days * 2.5) / 30;
        $congeRestant = floor($nbreConge + $user->initial - $user->pris);

        // Vérification si le nombre de jours demandés dépasse les congés restants
        if ($days1 > $congeRestant) {
            return redirect()->route('conges.index')->with('error', 'Impossible de modifier la demande avec ce nombre de jours.');
        }

        // Mise à jour des informations du congé
        $conge->type_conge_id = $request->type_conge_id;
        $conge->dateDebut = $request->dateDebut;
        $conge->dateFin = $request->dateFin;
        $conge->commentaire = $request->commentaire;
        $conge->save();

        return redirect()->route('conges.index')->with('success', 'Demande de congé modifiée avec succès.');
    }


    public function show($conge)
{
    $conge = Conges::findOrFail($conge);
    return view('conges.show', compact('conge'));
}


    public function approveByManager($conge)
    {
        $conge = Conges::findOrFail($conge);
        $user = auth()->user();

        if ($user->profil !== 'manager') {
            return redirect()->back()->with('error', 'Accès non autorisé.');
        }


            $conge->status = 'en attente RH';
            $conge->approved_by_manager = $user->id;
            $conge->save();


        return redirect()->route('conges.index')->with('success', 'Demande approuvée par le manager.');
    }

    
    public function approveByRh($id)
    {
        // Récupérer la demande de congé avec la relation 'user' et 'typeConge'
        $conge = Conges::with('user', 'typeConge')->findOrFail($id);
        $user = auth()->user();

        // Vérifier si l'utilisateur est un responsables RH
        if ($user->profil !== 'responsables RH') {
            return redirect()->back()->with('error', 'Accès non autorisé.');
        }

        // Vérifier si le statut est en attente de validation RH
        if ($conge->status === 'en attente RH') {
            // Calculer le nombre de jours pour le congé annuel
            if ($conge->typeConge->nom == 'Congé annuel') {
                $days = $this->calculateDays($conge->dateDebut, $conge->dateFin);
                $conge->user->pris += $days;  // Mettre à jour le nombre de jours pris
                $conge->user->save();
            }

            // Générer le fichier PDF
            $pdf = Pdf::loadView('pdf.conge', ['conge' => $conge]);

            // Vérifier si le dossier existe, sinon le créer
            $directory = storage_path('app/public/conges');
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            // Définir le chemin du fichier PDF
            $pdfPath = storage_path('app/public/conges/conge_' . $conge->id . '.pdf');
            $pdf->save($pdfPath);

            // Mettre à jour le statut et le chemin du PDF
            $conge->status = 'approuvé';
            $conge->approved_by_rh = $user->id;
            $conge->pdf_path = 'conges/conge_' . $conge->id . '.pdf';
            $conge->save();

            // Envoyer l'email avec le PDF en pièce jointe
            Mail::to($conge->user->email)->send(new CongeApprovalMail($conge, $pdfPath));

            return redirect()->route('conges.index')->with('success', 'Demande approuvée, email envoyé et jours de congé mis à jour.');
        } elseif ($conge->status === 'en attente') {
            // En cas de refus, mettre à jour le statut
            $conge->status = 'refusé';
            $conge->save();
            return redirect()->back()->with('success', 'Demande refusée.');
        }

        return redirect()->back()->with('error', 'Le statut de la demande est invalide.');
    }


    private function calculateDays($dateDebut, $dateFin)
    {
        return (new \DateTime($dateFin))->diff(new \DateTime($dateDebut))->days + 1;
    }


    public function reject($id)
    {
        $conge = Conges::findOrFail($id);
        $user = auth()->user();
    
        if ($user->profil !== 'manager' && $user->profil !== 'responsables RH') {
            return redirect()->back()->with('error', 'Accès non autorisé.');
        }
    
        if ($conge->status === 'en attente' || $conge->status === 'en attente RH') {
            $conge->status = 'refusé';
            $conge->save();
    
            // Envoyer la notification à l'employé
            $employe = $conge->employe;  // Assurez-vous que $conge->employe retourne bien l'utilisateur lié
            $employe->notify(new \App\Notifications\CongeRejected($conge));
        }
    
        return redirect()->route('conges.index')->with('success', 'Demande refusée.');
    }
    

    public function destroy(Conges $conge)
    {
        $conge->delete();

        return redirect()->route('conges.index')->with('success', 'Congé supprimé avec succès.');
    }

}
