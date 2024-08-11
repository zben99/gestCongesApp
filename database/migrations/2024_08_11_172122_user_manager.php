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
        Schema::create('user_manager', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // L'employé
            $table->unsignedBigInteger('manager_id'); // Le manager
            $table->timestamps();

            // Définir les clés étrangères et les relations
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('manager_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_manager');
    }
};
