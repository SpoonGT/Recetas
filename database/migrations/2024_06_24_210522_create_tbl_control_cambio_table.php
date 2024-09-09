<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblControlCambioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_control_cambio', function (Blueprint $table) {
            $table->bigInteger('receta_rechazada_id')->unsigned()->index();
            $table->foreign('receta_rechazada_id')->references('id')->on('tbl_receta_rechazada');

            $table->enum('accion', ['NUEVO', 'MODIFICAR', 'ELIMINAR'])->index();
            $table->longText('anterior')->nullable();
            $table->longText('actual');

            $table->bigInteger('receta_id')->unsigned()->index();
            $table->foreign('receta_id')->references('id')->on('tbl_receta');

            $table->bigInteger('usuario_id')->unsigned()->index();
            $table->foreign('usuario_id')->references('id')->on('tbl_usuario');

            $table->timestamp('created_at', 0);

            $table->index(array('receta_rechazada_id', 'receta_id'));
            $table->index(array('receta_rechazada_id', 'receta_id', 'usuario_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_control_cambio');
    }
}
