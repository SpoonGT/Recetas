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

            $table->string('plataforma', 100); //0
            $table->string('id_pedido', 50)->nullable(); //1
            $table->string('local', 125); //2
            $table->string('serie', 10)->nullable(); //3
            $table->string('serie_compuesta', 25)->nullable(); //4
            $table->string('numero_documento')->nullable(); //5
            $table->string('numero_orden', 50)->nullable(); //6
            $table->date('fecha_pedido'); //7
            $table->date('fecha_entrega')->nullable(); //8
            $table->date('fecha_pago')->nullable(); //9
            $table->string('estado', 25)->nullable(); //10
            $table->string('forma_pago', 50)->nullable(); //11
            $table->string('total_bruto', 14, 4); //12
            $table->string('total_promocion', 14, 4)->nullable(); //13
            $table->string('total_neto', 14, 4); //14
            $table->string('nombre_cliente', 150)->nullable(); //15
            $table->string('cajero', 150)->nullable(); //16
            $table->string('tipo_servicio', 50)->nullable(); //17

            $table->timestamp('created_at', 0);
            $table->string('created_by', 25);
            $table->boolean('procesado')->default(false);

            $table->index(array('id', 'plataforma', 'fecha_pedido', 'local', 'procesado'));
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
