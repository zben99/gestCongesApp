<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(TypesCongesSeeder::class);
        $this->call(TypeAbsencesSeeder::class);

        User::factory()->create([
            'nom' => 'Super',
            'prenom' => 'Admin',
            'matricule' => '00000',
            'email' => 'admin@admin.com',
           // 'password' => '$2y$12$26Ve8sEtk7YfE5hvxKjxgujicF7bQaPWaWS5Njt8tRYSUSkmWCT82', //12345678
            'telephone1' => '25000000',
            'profil' => 'administrateurs',

        ]);


    }
}
