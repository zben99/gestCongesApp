<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase; // Cette directive assure que la base de données est réinitialisée entre chaque test.

    /**
     * Teste la création d'un utilisateur.
     */
    public function test_user_creation()
    {
        // Les données de l'utilisateur
        $userData = [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'matricule' => '12345',
            'email' => 'jean.dupont@example.com',
            'password' => bcrypt('password'),
            'telephone1' => '123456789',
            'telephone2' => null, // valeur nullable
            'birth_date' => '1985-12-12',
            'profil' => 'admin',
            'departementId' => 1,
            'posteId' => 2,
            'arrival_date' => '2023-01-15',
            'initialization_date' => '2023-01-20',
            'initial' => '10'
        ];

        // Créer l'utilisateur via la méthode store
        $user = User::create($userData);

        // Assertions
        $this->assertDatabaseHas('users', [
            'email' => 'jean.dupont@example.com',
        ]);

        // Vérifie que le mot de passe est hashé
        $this->assertTrue(password_verify('password', $user->password));

        // Vérifie que la date d'initialisation est bien insérée
        $this->assertEquals('2023-01-20', $user->initialization_date);
    }

    /**
     * Teste la validation des champs obligatoires.
     */
    public function test_required_fields()
    {
        // Les données avec des champs manquants
        $userData = [
            'nom' => 'Dupont',
            'prenom' => '',
            'email' => '',
            'password' => bcrypt('password'),
        ];

        // Crée un utilisateur avec des champs manquants
        $response = $this->post('/user/store', $userData);

        // Attendre une validation d'erreur
        $response->assertSessionHasErrors(['prenom', 'email']);
    }
}
