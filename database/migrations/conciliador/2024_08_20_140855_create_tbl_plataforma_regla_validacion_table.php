<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblPlataformaReglaValidacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_plataforma_regla_validacion', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('csv_plataforma_id')->unsigned()->index();
            $table->foreign('csv_plataforma_id')->references('id')->on('tbl_csv_plataforma');

            $table->bigInteger('csv_icg_id')->unsigned()->index();
            $table->foreign('csv_icg_id')->references('id')->on('tbl_csv_icg');

            $table->bigInteger('regla_validacion_id')->unsigned()->index();
            $table->foreign('regla_validacion_id')->references('id')->on('tbl_regla_validacion');

            $table->year('anio');
            $table->smallInteger('mes');
            $table->boolean('resuelto')->default(false);

            $table->timestamp('created_at', 0);
            $table->string('created_by', 25);

            $table->timestamp('updated_at', 0)->nullable();
            $table->string('updated_by', 25)->nullable();

            $table->index(array('csv_plataforma_id', 'csv_icg_id', 'regla_validacion_id', 'resuelto'), 'index_optimizacion_busqueda_uno');
            $table->index(array('regla_validacion_id', 'anio', 'mes', 'resuelto'), 'index_optimizacion_busqueda_dos');
            $table->index(array('created_by', 'anio', 'mes', 'resuelto'), 'index_optimizacion_busqueda_tres');
            $table->index(array('created_at', 'regla_validacion_id', 'csv_plataforma_id', 'resuelto'), 'index_optimizacion_busqueda_cuatro');
            $table->index(array('regla_validacion_id', 'anio', 'mes', 'csv_plataforma_id', 'resuelto'), 'index_optimizacion_busqueda_cinco');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_plataforma_regla_validacion');
    }
}
