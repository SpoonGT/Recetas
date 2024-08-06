<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblProductoMateriaPrimaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_producto_materia_prima', function (Blueprint $table) {
            $table->id();
            $table->decimal('cantidad', 14, 4)->default(1);

            $table->string('nomenclatura', 20);
            $table->bigInteger('unidad_id')->unsigned()->index();
            $table->foreign('unidad_id')->references('id')->on('tbl_unidad');

            $table->boolean('activo')->default(true);

            $table->bigInteger('producto_id')->unsigned()->index();
            $table->foreign('producto_id')->references('id')->on('tbl_producto');

            $table->bigInteger('informacion_id')->unsigned()->index();
            $table->foreign('informacion_id')->references('id')->on('tbl_informacion');

            $table->bigInteger('materia_prima_id')->unsigned()->index();
            $table->foreign('materia_prima_id')->references('id')->on('tbl_materia_prima');

            $table->timestamps();
            $table->softDeletes();

            $table->string('created_by', 25);
            $table->string('updated_by', 25)->nullable();
            $table->string('deleted_by', 25)->nullable();

            $table->index(array('producto_id', 'unidad_id'));
            $table->index(array('producto_id', 'materia_prima_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_producto_materia_prima');
    }
}
