<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblBitacoraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_bitacora', function (Blueprint $table) {
            $table->id();
            $table->enum('accion', ['AGREGAR', 'MODIFICAR', 'ELIMINAR']);
            $table->longText("metadata_anterior")->nullable();
            $table->longText("metadata_nueva");

            $table->bigInteger('control_cambio_id')->unsigned()->index();
            $table->foreign('control_cambio_id')->references('id')->on('tbl_control_cambio');

            $table->bigInteger('chef_id')->unsigned()->index();
            $table->foreign('chef_id')->references('id')->on('tbl_usuario');

            $table->timestamp('created_at', 0);
            $table->string('created_by', 25);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_bitacora');
    }
}
