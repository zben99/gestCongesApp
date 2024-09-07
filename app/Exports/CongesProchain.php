<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class CongesProchain implements FromCollection, WithHeadings
{
    use Exportable;

    protected $conges;

    public function __construct($conges)
    {
        $this->conges = $conges;
    }

    public function collection()
    {
        return $this->conges->map(function($conge) {
            return [
                $conge->employe->nom . ' ' . $conge->employe->prenom,
                $conge->employe->departement->name_departement ?? 'Non attribué',
                $conge->typeConge->nom,
                $conge->dateDebut,
                $conge->dateFin,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nom',
            'Département',
            'Type de Congé',
            'Date de Début',
            'Date de Fin',
        ];
    }
}
