<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblDescripcionProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_descripcion_producto', function (Blueprint $table) {
            $table->bigInteger('ficha_tecnica_id')->unsigned()->index();
            $table->foreign('ficha_tecnica_id')->references('id')->on('tbl_ficha_tecnica');

            $table->longText('descripcion');
            $table->longText('ingredientes');
            $table->longText('alergenos');
            $table->longText('uso_intensional');
            $table->longText('aspecto_rechazo');

            $table->string('peso_bruto', 50);
            $table->string('peso_neto', 50);
            $table->string('peso_etiqueta', 50);

            $table->string('vida_util_congelado', 25);
            $table->string('vida_util_refrigerado', 25);

            $table->string('codigo_barra', 50);

            $table->boolean('requiere_etiqueta');

            $table->longText('tipo_unidad_empaque');
            $table->longText('almacenamiento_maejo');

            $table->timestamp('created_at', 0);
            $table->string('created_by', 25);

            $table->unique('ficha_tecnica_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_descripcion_producto');
    }
}
