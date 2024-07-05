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
            $table->string('plataforma', 100);
            $table->string('id_pedido', 50)->nullable();
            $table->string('local', 125);
            $table->string('serie', 10)->nullable();
            $table->string('serie_compuesta', 25)->nullable();
            $table->bigInteger('numero_documento')->nullable();
            $table->string('numero_orden', 50)->nullable();
            $table->date('fecha_pedido');
            $table->date('fecha_entrega')->nullable();
            $table->date('fecha_pago')->nullable();
            $table->string('estado', 25)->nullable();
            $table->string('forma_pago', 50)->nullable();
            $table->decimal('total_bruto', 14, 4);
            $table->decimal('total_promocion', 14, 4)->nullable();
            $table->decimal('total_neto', 14, 4);
            $table->string('nombre_cliente', 150)->nullable();
            $table->string('cajero', 150)->nullable();
            $table->string('tipo_servicio', 50)->nullable();

            $table->timestamp('created_at', 0);
            $table->string('created_by', 25);

            $table->index(array('id', 'plataforma', 'fecha_pedido', 'local'));
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
