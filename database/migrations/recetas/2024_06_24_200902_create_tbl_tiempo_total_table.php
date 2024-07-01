<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblTiempoTotalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_tiempo_total', function (Blueprint $table) {
            $table->bigInteger('receta_id')->unsigned()->index();
            $table->foreign('receta_id')->references('id')->on('tbl_receta');

            $table->time('cantidad');
            $table->string('nomenclatura', 20);

            $table->bigInteger('unidad_id')->unsigned()->index();
            $table->foreign('unidad_id')->references('id')->on('tbl_unidad');

            $table->timestamps();

            $table->string('created_by', 25);
            $table->string('updated_by', 25)->nullable();

            $table->unique('receta_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_tiempo_total');
    }
}
