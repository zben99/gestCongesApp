<?php

namespace Database\Seeders;

use App\Models\TypeConges;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\TypeConge; // Assurez-vous que ce namespace est correct

class TypesCongesSeeder extends Seeder
{
    /**
     * Exécute les seeds de la base de données.
     */
    public function run(): void
    {
        $typesConges = [
            [
                'nom' => 'Congé annuel',
                'description' => 'Congé annuel payé accordé aux employés.',
                'duree_max' => 30, // Nombre de jours alloués par an
            ],
            [
                'nom' => 'Congé de maternité',
                'description' => 'Congé accordé aux employées enceintes avant et après l’accouchement.',
                'duree_max' => 90,
            ],
            [
                'nom' => 'Congé de paternité',
                'description' => 'Congé accordé aux employés à la naissance de leur enfant.',
                'duree_max' => 3,
            ],
        ];

        foreach ($typesConges as $type) {
            TypeConges::create($type);
        }
    }
}
