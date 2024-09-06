<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblReglaValidacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_regla_validacion', function (Blueprint $table) {
            $table->id();

            $table->smallInteger('secuencia');
            $table->longText('descripcion');
            $table->longText('query');

            $table->bigInteger('plataforma_id')->unsigned()->index();
            $table->foreign('plataforma_id')->references('id')->on('tbl_plataforma');

            $table->bigInteger('caso_id')->unsigned()->index();
            $table->foreign('caso_id')->references('id')->on('tbl_caso');

            $table->timestamp('created_at', 0);
            $table->softDeletes();

            $table->string('created_by', 25);
            $table->string('deleted_by', 25)->nullable();

            $table->index(array('secuencia', 'plataforma_id', 'caso_id', 'deleted_at'), 'index_optimizacion_busqueda_uno');
            $table->index(array('plataforma_id', 'caso_id', 'deleted_at'), 'index_optimizacion_busqueda_dos');
            $table->index(array('plataforma_id', 'deleted_at'), 'index_optimizacion_busqueda_tres');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_regla_validacion');
    }
}
