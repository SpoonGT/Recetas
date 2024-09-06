<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblCsvIcgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_csv_icg', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('plataforma_id')->unsigned()->index();
            $table->foreign('plataforma_id')->references('id')->on('tbl_plataforma');

            $table->string('plataforma', 10);
            $table->string('id_pedido', 50);

            $table->bigInteger('punto_venta_id')->unsigned()->index();
            $table->foreign('punto_venta_id')->references('id')->on('tbl_punto_venta');

            $table->string('punto_venta', 125);

            $table->date('fecha_pedido');
            $table->date('fecha_entrega');
            $table->decimal('total_bruto', 14, 6);
            $table->decimal('total_promocion', 14, 6);
            $table->decimal('total_neto', 14, 6);
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
            $table->boolean('no_id')->default(false);

            $table->index(array('id', 'procesado'));
            $table->index(array('id', 'plataforma_id', 'punto_venta_id', 'procesado'));
            $table->index(array('id', 'fecha_pedido', 'procesado'));
            $table->index(array('plataforma', 'punto_venta', 'serie_compuesta', 'numero_documento', 'total_bruto', 'total_neto',  'procesado'));
            $table->index(array('plataforma', 'punto_venta', 'serie_compuesta', 'numero_documento', 'fecha_pedido', 'total_bruto', 'procesado'));
            $table->index(array('created_by', 'procesado'));
            $table->index(array('no_id', 'procesado'));
            $table->index(array('no_id', 'id_pedido', 'procesado', 'plataforma_id'));
            $table->index(array('no_id', 'id_pedido', 'plataforma_id'));

            $table->index(array('id', 'plataforma', 'id_pedido', 'fecha_pedido', 'fecha_entrega', 'punto_venta', 'procesado'), 'index_optimizacion_busqueda_uno');
            $table->index(array('id', 'plataforma', 'id_pedido', 'punto_venta', 'serie_compuesta', 'fecha_pedido', 'total_bruto', 'total_promocion', 'total_neto', 'forma_pago', 'procesado'), 'index_optimizacion_busqueda_dos');
            $table->index(array('plataforma', 'id_pedido', 'punto_venta', 'serie_compuesta', 'fecha_pedido', 'fecha_entrega', 'total_bruto', 'total_promocion', 'total_neto', 'forma_pago', 'procesado'), 'index_optimizacion_busqueda_tres');
            $table->index(array('plataforma', 'id_pedido', 'punto_venta', 'serie_compuesta', 'numero_documento', 'fecha_pedido', 'total_bruto', 'total_neto', 'forma_pago', 'procesado'), 'index_optimizacion_busqueda_cuatro');
            $table->index(array('plataforma', 'punto_venta', 'serie_compuesta', 'numero_documento', 'procesado'), 'index_optimizacion_busqueda_cinco');
            $table->index(array('plataforma', 'id_pedido', 'punto_venta', 'fecha_pedido', 'fecha_entrega', 'total_bruto', 'total_promocion', 'total_neto', 'serie_compuesta', 'numero_documento', 'numero_orden', 'forma_pago', 'nombre_cliente', 'cajero', 'estado', 'procesado'), 'index_optimizacion_busqueda_seis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_csv_icg');
    }
}
