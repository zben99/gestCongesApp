<?php

namespace App\Exports;

use App\Models\Absence;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AbsencesExport implements FromCollection, WithHeadings
{
    protected $departmentId;
    protected $year;
    protected $status;

    public function __construct($departmentId, $year, $status)
    {
        $this->departmentId = $departmentId;
        $this->year = $year;
        $this->status = $status;
    }

    public function collection()
    {
        // Construire la requête avec les filtres
        $query = Absence::with('user.departement');

        // Filtrer par année
        if ($this->year) {
            $query->whereYear('dateDebut', $this->year);
        }

        // Filtrer par département
        if ($this->departmentId) {
            $query->whereHas('user', function ($q) {
                $q->where('departement_id', $this->departmentId); // Assurez-vous que c'est 'departement_id'
            });
        }

        // Filtrer par statut
        if ($this->status) {
            $query->where('status', $this->status);
        }

        // Récupérer les résultats sous forme de collection
        return $query->get([
            'UserId', // Assurez-vous d'utiliser les bonnes colonnes
            'dateDebut', 
            'dateFin', 
            'status'
        ])->map(function($absence) {
            return [
                'matricule' => $absence->user->matricule, // Assurez-vous que la relation est correctement définie
                'nom' => $absence->user->nom,
                'prenom' => $absence->user->prenom,
                'departement' => $absence->user->departement->name_departement ?? 'N/A', // Utilisez l'opérateur ?? pour éviter les erreurs
                'dateDebut' => $absence->dateDebut,
                'dateFin' => $absence->dateFin,
                'status' => $absence->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Matricule',
            'Nom',
            'Prénom',
            'Département',
            'Date début',
            'Date fin',
            'Statut',
        ];
    }
}
