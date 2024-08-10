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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('matricule', 191)->unique()->nullable();
            $table->string('email', 191)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('telephone1', 191)->index();
            $table->string('telephone2', 191)->nullable()->index();
            $table->date('birth_date')->nullable();
            $table->enum('profil', ['employés', 'manager', 'responsables RH', 'administrateurs']);
            $table->foreignId('departementId')->nullable()->constrained('departements')->onDelete('set null');
            $table->foreignId('posteId')->nullable()->constrained('postes')->onDelete('set null');
            $table->date('arrival_date')->nullable();
            $table->date('initialization_date')->nullable();
            $table->integer('initial')->default(0);
            $table->integer('pris')->default(0);
            $table->integer('reste')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });



        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
