<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblRecetaEstadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_receta_estado', function (Blueprint $table) {
            $table->enum('estado', ['CREADO', 'REVISIÓN', 'RECHAZADO', 'APROBADO']);
            $table->enum('color', ['primary', 'warning', 'danger', 'success']);

            $table->bigInteger('receta_id')->unsigned()->index();
            $table->foreign('receta_id')->references('id')->on('tbl_receta');

            $table->timestamp('created_at', 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_receta_estado');
    }
}
