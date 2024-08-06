<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblCsvPlataformaTemporalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_csv_plataforma_temporal', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('plataforma_id')->unsigned()->index();
            $table->foreign('plataforma_id')->references('id')->on('tbl_plataforma');

            $table->string('plataforma', 10);
            $table->string('id_pedido', 50);
            $table->string('local', 125);
            $table->date('fecha');
            $table->decimal('total', 14, 6);
            $table->string('estado', 75);

            $table->timestamp('created_at', 0);
            $table->string('created_by', 25);
            $table->boolean('procesado')->default(false);
            $table->longText('mensaje')->nullable();

            $table->index(array('plataforma', 'id_pedido', 'local', 'fecha', 'total', 'estado', 'procesado'), 'index_optimizacion_busqueda_uno');
            $table->index(array('plataforma_id', 'procesado'), 'index_optimizacion_busqueda_dos');
            $table->index(array('plataforma_id', 'procesado', 'id_pedido'), 'index_optimizacion_busqueda_tres');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_csv_plataforma_temporal');
    }
}
