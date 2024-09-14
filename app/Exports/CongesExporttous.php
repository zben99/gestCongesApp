<?php
namespace App\Exports;

use App\Models\Conges;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CongesExporttous implements FromCollection, WithHeadings, WithMapping
{
    protected $departmentId;
    protected $year;
    protected $status; // Ajout de la propriété pour le statut

    // Injecter les filtres dans le constructeur
    public function __construct($departmentId = null, $year = null, $status = null)
    {
        $this->departmentId = $departmentId;
        $this->year = $year;
        $this->status = $status; // Initialiser le filtre pour le statut
    }

    /**
     * Récupère les données à exporter.
     */
    public function collection()
    {
        // Début de la requête
        $query = Conges::with('employe.departement');

        // Filtrer par département si nécessaire
        if ($this->departmentId) {
            $query->whereHas('employe', function ($q) {
                $q->where('departement_id', $this->departmentId);
            });
        }

        // Filtrer par année si nécessaire
        if ($this->year) {
            $query->whereYear('dateDebut', $this->year);
        }

        // Filtrer par statut si nécessaire
        if ($this->status) {
            $query->where('status', $this->status);
        }

        // Retourner la collection de congés
        return $query->get();
    }

    /**
     * Définir les en-têtes du fichier Excel.
     */
    public function headings(): array
    {
        return [
            'Matricule',
            'Nom',
            'Prénom',
            'Email',
            'Contact',
            'Département',
            'Date Début',
            'Date Fin',        
        ];
    }

    /**
     * Mapper les données pour chaque ligne.
     */
    public function map($conge): array
    {
        return [
            $conge->employe->matricule,
            $conge->employe->nom,
            $conge->employe->prenom,
            $conge->employe->email,
            $conge->employe->telephone1,
            $conge->employe->departement->name_departement ?? 'Non attribué',
            \Carbon\Carbon::parse($conge->dateDebut)->format('d/m/Y'),
            \Carbon\Carbon::parse($conge->dateFin)->format('d/m/Y'),

        ];
    }
}
