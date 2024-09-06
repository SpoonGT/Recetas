<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblConciliacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_conciliacion', function (Blueprint $table) {
            $table->id();
            $table->enum('informacion', ['CONCILIACION AUTOMATICA', 'CONCILIACION MANUAL']);
            $table->longText('comentario')->nullable();

            $table->bigInteger('csv_plataforma_id')->unsigned()->index();
            $table->foreign('csv_plataforma_id')->references('id')->on('tbl_csv_plataforma');

            $table->bigInteger('csv_icg_id')->unsigned()->index();
            $table->foreign('csv_icg_id')->references('id')->on('tbl_csv_icg');

            $table->year('anio');
            $table->smallInteger('mes');

            $table->timestamp('created_at', 0);
            $table->string('created_by', 25);

            $table->unique(array('csv_plataforma_id', 'csv_icg_id'), 'unique_optimizacion_busqueda_uno');

            $table->index(array('informacion', 'csv_plataforma_id'), 'index_optimizacion_busqueda_uno');
            $table->index(array('informacion', 'anio', 'mes'), 'index_optimizacion_busqueda_dos');
            $table->index(array('informacion', 'anio', 'mes', 'csv_plataforma_id'), 'index_optimizacion_busqueda_tres');
            $table->index(array('informacion', 'csv_icg_id'), 'index_optimizacion_busqueda_cuatro');
            $table->index(array('informacion', 'anio', 'mes', 'csv_icg_id'), 'index_optimizacion_busqueda_cinco');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_conciliacion');
    }
}
