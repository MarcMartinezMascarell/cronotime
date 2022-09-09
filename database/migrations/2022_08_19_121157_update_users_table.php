<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table) {
            $table->unsignedBigInteger('id_empresa')->nullable();
            $table->tinyText('locale')->default('es');
            $table->foreign('id_empresa')->references('id')->on('empresas')->onConstraint('cascade')->onDelete('cascade');
            $table->foreign('horario')->references('id')->on('horarios')->onConstraint('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
