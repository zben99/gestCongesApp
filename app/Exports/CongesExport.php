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
                    $conge->employe->nom.' '. $conge->employe->prenom,
                    $conge->employe->departement->name_departement,
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
            'Date de début',
            'Date de fin',
        ];
    }

    
}
