<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblImportacionNetsuitDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_importacion_netsuit_data', function (Blueprint $table) {
            $table->id();

            $table->enum('estado', ['PROCESANDO', 'VALIDADO', 'EXISTENTE'])->default('PROCESANDO');

            $table->string('embalaje', 75)->nullable();
            $table->string('lista_material', 75)->nullable();
            $table->string('revision', 75)->nullable();
            $table->string('nota', 75)->nullable();
            $table->string('articulo', 75)->nullable();
            $table->string('descripcion', 800)->nullable();
            $table->string('marca', 75);
            $table->string('unidad', 75);

            $table->bigInteger('importacion_netsuit_id')->unsigned()->index();
            $table->foreign('importacion_netsuit_id')->references('id')->on('tbl_importacion_netsuit');

            $table->index(array('importacion_netsuit_id', 'estado'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_importacion_netsuit_data');
    }
}
