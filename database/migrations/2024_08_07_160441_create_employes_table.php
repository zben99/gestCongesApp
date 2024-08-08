<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('matricule')->unique();
            $table->string('email')->unique();
            $table->string('telephone1');
            $table->string('telephone2')->nullable();
            $table->date('dateNaissance');
            $table->string('password');
            $table->enum('profil', ['employés', 'manager', 'responsables RH', 'administrateurs']);
            $table->foreignId('departementId')->constrained('departements');
            $table->foreignId('posteId')->constrained('postes');
            $table->date('dateArrive');
            $table->integer('initial')->default(30); // Par exemple, 30 jours de congé initial
            $table->integer('pris')->default(0);
            $table->integer('reste')->default(30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employes');
    }
};
