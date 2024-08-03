<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblCsvIcgTemporalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_csv_icg_temporal', function (Blueprint $table) {
            $table->id();

            $table->string('plataforma', 10);
            $table->string('id_pedido', 50);
            $table->string('local', 125);
            $table->date('fecha_pedido');
            $table->date('fecha_entrega');
            $table->decimal('total_bruto', 14, 6);
            $table->string('total_promocion')->nullable();
            $table->string('total_neto')->nullable();
            $table->string('serie_compuesta', 25);
            $table->bigInteger('numero_documento');
            $table->string('numero_orden')->nullable();
            $table->string('forma_pago', 50)->nullable();
            $table->string('nombre_cliente', 150)->nullable();
            $table->string('cajero', 150)->nullable();
            $table->string('estado', 25)->nullable();

            $table->timestamp('created_at', 0);
            $table->string('created_by', 25);
            $table->boolean('procesado')->default(false);
            $table->longText('mensaje')->nullable();

            $table->index(array('id', 'plataforma', 'id_pedido', 'fecha_pedido', 'fecha_entrega', 'local', 'procesado'), 'index_optimizacion_busqueda_uno');
            $table->index(array('id', 'plataforma', 'id_pedido', 'local', 'serie_compuesta', 'fecha_pedido', 'total_bruto', 'total_promocion', 'total_neto', 'forma_pago', 'procesado'), 'index_optimizacion_busqueda_dos');
            $table->index(array('plataforma', 'id_pedido', 'local', 'serie_compuesta', 'fecha_pedido', 'fecha_entrega', 'total_bruto', 'total_promocion', 'total_neto', 'forma_pago', 'procesado'), 'index_optimizacion_busqueda_tres');
            $table->index(array('plataforma', 'id_pedido', 'local', 'serie_compuesta', 'numero_documento', 'fecha_pedido', 'total_bruto', 'total_neto', 'forma_pago', 'procesado'), 'index_optimizacion_busqueda_cuatro');
            $table->index(array('plataforma', 'local', 'serie_compuesta', 'numero_documento', 'procesado'), 'index_optimizacion_busqueda_cinco');
            $table->index(array('plataforma', 'id_pedido', 'local', 'fecha_pedido', 'fecha_entrega', 'total_bruto', 'total_promocion', 'total_neto', 'serie_compuesta', 'numero_documento', 'numero_orden', 'forma_pago', 'nombre_cliente', 'cajero', 'estado', 'procesado'), 'index_optimizacion_busqueda_seis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_csv_icg_temporal');
    }
}
