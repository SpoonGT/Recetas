<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblCsvPlataformaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_csv_plataforma', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('plataforma_id')->unsigned()->index();
            $table->foreign('plataforma_id')->references('id')->on('tbl_plataforma');

            $table->string('plataforma', 10);
            $table->string('id_pedido', 50);

            $table->bigInteger('punto_venta_id')->unsigned()->index();
            $table->foreign('punto_venta_id')->references('id')->on('tbl_punto_venta');

            $table->string('punto_venta', 125);

            $table->bigInteger('alias_id')->unsigned()->index();
            $table->foreign('alias_id')->references('id')->on('tbl_alias');

            $table->date('fecha');
            $table->decimal('total', 14, 6);
            $table->string('estado', 75);

            $table->bigInteger('plataforma_estado_id')->unsigned()->index();
            $table->foreign('plataforma_estado_id')->references('id')->on('tbl_plataforma_estado');

            $table->bigInteger('estado_id')->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('tbl_estado');

            $table->timestamp('created_at', 0);
            $table->string('created_by', 25);
            $table->boolean('procesado')->default(false);
            $table->enum('informacion', ['REGISTRADO', 'REGLA VALIDACION', 'CONCILIACION AUTOMATICA', 'CONCILIACION MANUAL', 'RE PROSESAR'])->default('REGISTRADO');

            $table->unique(array('plataforma', 'id_pedido', 'punto_venta', 'fecha', 'total', 'estado'), 'llave_unica_uno');
            $table->index(array('plataforma', 'id_pedido', 'punto_venta', 'fecha', 'total', 'estado', 'procesado'), 'index_optimizacion_busqueda_uno');
            $table->index(array('plataforma_id', 'procesado'), 'index_optimizacion_busqueda_dos');
            $table->index(array('plataforma_id', 'procesado', 'id_pedido'), 'index_optimizacion_busqueda_tres');

            $table->index(array('plataforma_id', 'punto_venta_id', 'alias_id', 'procesado'), 'index_optimizacion_busqueda_cuatro');
            $table->index(array('punto_venta_id', 'alias_id', 'procesado'), 'index_optimizacion_busqueda_cinco');
            $table->index(array('punto_venta_id', 'procesado'), 'index_optimizacion_busqueda_seis');
            $table->index(array('alias_id', 'procesado'), 'index_optimizacion_busqueda_siete');
            $table->index(array('plataforma_id', 'informacion', 'procesado'), 'index_optimizacion_busqueda_ocho');
            $table->index(array('plataforma_id', 'informacion'), 'index_optimizacion_busqueda_nueve');
            $table->index(array('plataforma_id', 'id_pedido', 'punto_venta_id', 'fecha', 'total', 'estado_id', 'informacion'), 'index_optimizacion_busqueda_diez');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_csv_plataforma');
    }
}
