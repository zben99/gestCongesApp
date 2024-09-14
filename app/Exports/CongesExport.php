<?php

namespace App\Exports;

use App\Models\Conges;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class CongesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $dateNow = Carbon::now();

        return Conges::where('dateDebut', '<=', $dateNow)
            ->where('dateFin', '>=', $dateNow)
            ->with('employe.departement')
            ->get()
            ->map(function ($conge) {
                return [
                    $conge->employe->matricule,
                    $conge->employe->nom.' '. $conge->employe->prenom,
                    $conge->employe->departement->name_departement,
                    $conge->employe->email,
                    $conge->employe->telephone1,
                    $conge->typeConge->nom,
                    $conge->dateDebut,
                    $conge->dateFin,
                    
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Matricule',
            'Nom',
            'Département',
            'Email',
            'Téléphone',
            'Type de Congé',
            'Date de début',
            'Date de fin',
        ];
    }    
}
