<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Conges;
use App\Models\TypeConges;
use Illuminate\Http\Request;
use App\Mail\CongeApprovalMail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\HolidaysService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Notifications\CustomCongeRejectNotification;

class CongesController extends Controller
{
    protected $holidaysService;

    public function __construct(HolidaysService $holidaysService)
    {
        $this->holidaysService = $holidaysService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Conges::query();

        // Filtres de recherche
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Gestion des profils
        if ($user->profil === 'administrateurs') {
            $conges = $query->paginate(7);
        } elseif ($user->profil === 'manager') {
            $conges = $query->whereIn('UserId', $user->employees->pluck('id')->push($user->id))->paginate(7);
        } elseif ($user->profil === 'responsables RH') {
            $employeeIds = $user->rhEmployees->pluck('id');
            $conges = $query->whereIn('UserId', $employeeIds->push($user->id))->paginate(7);
        } else {
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
        $validatedData = $request->validate([
            'userId' => 'required|exists:users,id',
            'type_conge_id' => 'required|exists:type_conges,id',
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after_or_equal:dateDebut',
            'commentaire' => 'nullable|string',
        ]);

        $user = User::findOrFail($validatedData['userId']);
        $typeConge = TypeConges::findOrFail($validatedData['type_conge_id']);

        // Dates de début et de fin
        $startDate = Carbon::parse($validatedData['dateDebut']);
        $endDate = Carbon::parse($validatedData['dateFin']);

        // Calcul des jours ouvrables demandés (exclusion des weekends et jours fériés)
        $daysRequested = $this->holidaysService->countWorkingDays($startDate, $endDate);

        // Vérification de la durée maximale pour le type de congé
        if ($daysRequested > $typeConge->duree_max) {
            return redirect()->route('conges.index')->with('error', 'La durée demandée dépasse la durée maximale autorisée pour ce type de congé.');
        }

        // Calcul des jours de congé disponibles
        $days = Carbon::now()->diffInDays($user->initialization_date) + 1;
        $nbreConge = ($days * 2.5) / 30;
        $congeRestant = floor($nbreConge + $user->initial + $user->joursBonus- $user->pris);

        // Vérification si le nombre de jours demandés dépasse les congés restants
        if ($daysRequested > $congeRestant) {
            return redirect()->route('conges.index')->with('error', 'Impossible de créer une demande avec ce nombre de jours.');
        }

        try {
            // Création de la demande de congé
            $conge = Conges::create([
                'userId' => $validatedData['userId'],
                'type_conge_id' => $validatedData['type_conge_id'],
                'dateDebut' => $validatedData['dateDebut'],
                'dateFin' => $validatedData['dateFin'],
                'commentaire' => $validatedData['commentaire'],
                'status' => 'en attente',
            ]);

            return redirect()->route('conges.index')->with('success', 'Demande de congé soumise avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('conges.index')->with('error', 'Une erreur est survenue lors de la soumission de la demande.');
        }
    }

    public function edit(Conges $conge)
    {
        $users = User::all();
        $typeConges = TypeConges::all();
        return view('conges.edit', compact('conge', 'users', 'typeConges'));
    }

    public function update(Request $request, Conges $conge)
    {
        $validatedData = $request->validate([
            'type_conge_id' => 'required|exists:type_conges,id',
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after_or_equal:dateDebut',
            'commentaire' => 'nullable|string',
        ]);

        $user = User::findOrFail($conge->UserId);
        $typeConge = TypeConges::findOrFail($validatedData['type_conge_id']);

        // Calcul du nombre de jours demandés
        $daysRequested = $this->calculateDays($validatedData['dateDebut'], $validatedData['dateFin']);

        // Vérification de la durée maximale pour le type de congé
        if ($daysRequested > $typeConge->duree_max) {
            return redirect()->route('conges.index')->with('error', 'La durée demandée dépasse la durée maximale autorisée pour ce type de congé.');
        }

        // Calcul des jours de congé disponibles
        $days = $this->calculateDays($user->initialization_date, now());
        $nbreConge = ($days * 2.5) / 30;
        $congeRestant = floor($nbreConge + $user->initial+$user->joursBonus - $user->pris);

        // Vérification si le nombre de jours demandés dépasse les congés restants
        if ($daysRequested > $congeRestant) {
            return redirect()->route('conges.index')->with('error', 'Impossible de modifier la demande avec ce nombre de jours.');
        }

        // Mise à jour des informations du congé
        $conge->update($validatedData);

        return redirect()->route('conges.index')->with('success', 'Demande de congé modifiée avec succès.');
    }

    public function show(Conges $conge)
    {
        return view('conges.show', compact('conge'));
    }

    public function approveByManager(Conges $conge)
    {
        $user = Auth::user();

        if ($user->profil !== 'manager') {
            return redirect()->back()->with('error', 'Accès non autorisé.');
        }

        $conge->update([
            'status' => 'en attente RH',
            'approved_by_manager' => $user->id,
        ]);

        return redirect()->route('conges.index')->with('success', 'Demande approuvée par le manager.');
    }

    public function approveByRh(Conges $conge)
    {
        $user = Auth::user();

        if ($user->profil !== 'responsables RH') {
            return redirect()->back()->with('error', 'Accès non autorisé.');
        }

        if ($conge->status === 'en attente RH') {
            if ($conge->typeConge->nom === 'Congé annuel') {
                $days = $this->calculateDays($conge->dateDebut, $conge->dateFin);
                $conge->user->increment('pris', $days);
            }

            $pdf = Pdf::loadView('pdf.conge', ['conge' => $conge]);
            $pdfPath = storage_path('app/public/conges/conge_' . $conge->id . '.pdf');

            if (!is_dir(dirname($pdfPath))) {
                mkdir(dirname($pdfPath), 0755, true);
            }

            $pdf->save($pdfPath);

            $conge->update([
                'status' => 'approuvé',
                'approved_by_rh' => $user->id,
                'pdf_path' => 'conges/conge_' . $conge->id . '.pdf',
            ]);

            Mail::to($conge->user->email)->send(new CongeApprovalMail($conge, $pdfPath));

            return redirect()->route('conges.index')->with('success', 'Demande approuvée, email envoyé et jours de congé mis à jour.');
        } elseif ($conge->status === 'en attente') {
            $conge->update(['status' => 'refusé']);
            return redirect()->back()->with('success', 'Demande refusée.');
        }

        return redirect()->back()->with('error', 'Le statut de la demande est invalide.');
    }

    public function reject(Request $request,Conges $conge)
    {

        $request->validate([
            'commentaire' => 'nullable|string|max:255', // Validation du commentaire

        ]);

        $user = Auth::user();

        if (!in_array($user->profil, ['manager', 'responsables RH'])) {
            return redirect()->back()->with('error', 'Accès non autorisé.');
        }

        if ($user->profil === 'manager') {
            $conge->update(['status' => 'refusé par manager']);
        } elseif ($user->profil === 'responsables RH') {
            $conge->update(['status' => 'refusé par RH']);
        }
        $conge->update(['motif_rejet' => $request->commentaire]);


        $conge->user->notify(new CustomCongeRejectNotification());

        return redirect()->route('conges.index')->with('success', 'Demande refusée.');
    }

    public function destroy(Conges $conge)
    {
        $conge->delete();
        return redirect()->route('conges.index')->with('success', 'Demande de congé supprimée avec succès.');
    }

    protected function calculateDays($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        return $this->holidaysService->countWorkingDays($start, $end);
    }
}
