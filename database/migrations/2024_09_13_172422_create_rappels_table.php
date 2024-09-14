<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('rappels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conge_id')->constrained('conges')->onDelete('cascade'); // Lien avec le congé
            $table->date('dateDebutRappel'); // Début de la période de rappel
            $table->date('dateFinRappel'); // Fin de la période de rappel
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rappels');
    }
};
