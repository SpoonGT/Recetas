<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblRecetaCodigoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_receta_codigo', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('version'); //1

            $table->string('codigo_receta', 50); //ESP-RE-DES-175

            $table->string('netsuit', 50)->index(); //PTI00152
            $table->string('codigo_barra', 50)->index(); //30800115

            $table->dateTime('fecha_aprobacion')->index();

            $table->string('usuario', 75);
            $table->bigInteger('aprueba_id')->unsigned()->index();
            $table->foreign('aprueba_id')->references('id')->on('tbl_usuario');

            $table->bigInteger('correlativo_codigo_id')->unsigned()->index();
            $table->foreign('correlativo_codigo_id')->references('id')->on('tbl_correlativo_codigo');

            $table->bigInteger('receta_id')->unsigned()->index();
            $table->foreign('receta_id')->references('id')->on('tbl_receta');

            $table->bigInteger('receta_padre_id')->default(0);
            $table->bigInteger('receta_original_id')->default(0);

            $table->unique(array('version', 'correlativo_codigo_id', 'receta_id'));
            $table->unique(array('version', 'correlativo_codigo_id', 'receta_padre_id', 'receta_original_id'));
            $table->index(array('version', 'correlativo_codigo_id'));
            $table->index(array('version', 'receta_id'));
            $table->index(array('codigo_receta', 'netsuit', 'codigo_barra'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_receta_codigo');
    }
}
