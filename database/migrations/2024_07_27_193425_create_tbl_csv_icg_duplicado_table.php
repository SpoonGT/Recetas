<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblCsvIcgDuplicadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_csv_icg_duplicado', function (Blueprint $table) {
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
            $table->longText('numero_documento');
            $table->longText('numero_orden')->nullable();
            $table->string('forma_pago', 50)->nullable();
            $table->longText('nombre_cliente')->nullable();
            $table->longText('cajero')->nullable();
            $table->longText('estado')->nullable();
            $table->smallInteger('repetido');
            $table->boolean('resuelto')->default(false);

            $table->year('anio');
            $table->smallInteger('mes');

            $table->timestamps();

            $table->string('created_by', 25);
            $table->string('updated_by', 25)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_csv_icg_duplicado');
    }
}
