<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeAbsencesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('type_absences')->insert([
            [
                'nom' => 'Absence légale exceptionnelle - Mariage',
                'description' => 'Absence légale exceptionnelle pour mariage.',
                'duree_max' => 4,
                'justificatif_requis' => true,
                'deductible_conges' => false,
                'jours_deductibles_apres' => null,
            ],
            [
                'nom' => 'Absence légale exceptionnelle - Décès',
                'description' => 'Absence légale exceptionnelle pour décès.',
                'duree_max' => 3,
                'justificatif_requis' => true,
                'deductible_conges' => false,
                'jours_deductibles_apres' => null,
            ],
            [
                'nom' => 'Absence légale exceptionnelle - Baptême',
                'description' => 'Absence légale exceptionnelle pour baptême.',
                'duree_max' => 1,
                'justificatif_requis' => true,
                'deductible_conges' => false,
                'jours_deductibles_apres' => null,
            ],
            [
                'nom' => 'Absence pour maladie',
                'description' => 'Absence due à une maladie nécessitant un justificatif médical.',
                'duree_max' => 0, // 0 peut représenter aucune limite spécifique
                'justificatif_requis' => true,
                'deductible_conges' => false,
                'jours_deductibles_apres' => null,
            ],
            [
                'nom' => 'Absence non légale',
                'description' => 'Absence non légale, déductible des congés si elle dépasse 10 jours.',
                'duree_max' => 0, // 0 peut représenter aucune limite spécifique
                'justificatif_requis' => false,
                'deductible_conges' => true,
                'jours_deductibles_apres' => 10,
            ],
            [
                'nom' => 'Absence non justifiée',
                'description' => 'Absence non justifiée, saisie par le manager et visible pour la RH.',
                'duree_max' => 0, // 0 peut représenter aucune limite spécifique
                'justificatif_requis' => false,
                'deductible_conges' => false,
                'jours_deductibles_apres' => null,
            ],

        ]);
    }
}
