<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRhManagerIdToUserManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_manager', function (Blueprint $table) {
            $table->unsignedBigInteger('rh_manager_id')->nullable()->after('rh_id');

            // Si vous souhaitez ajouter une clé étrangère
            $table->foreign('rh_manager_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_manager', function (Blueprint $table) {
            // Supprimer la clé étrangère si elle a été ajoutée
            $table->dropForeign(['rh_manager_id']);
            $table->dropColumn('rh_manager_id');
        });
    }
}
