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
        Schema::create('conges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('UserId')->constrained('users');
            $table->enum('typeConges', ['annuels', 'maladie', 'maternité', 'paternité']);
            $table->date('dateDebut');
            $table->date('dateFin');
            $table->text('commentaire')->nullable();
            $table->string('status', 191)->unique()->nullable();
            $table->unsignedBigInteger('approved_by_manager')->nullable();
            $table->unsignedBigInteger('approved_by_rh')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conges');
    }
};
