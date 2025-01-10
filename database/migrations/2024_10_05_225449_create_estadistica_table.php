<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstadisticaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estadistica', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('anio_id')->unsigned()->index();
            $table->foreign('anio_id')->references('id')->on('anio');

            $table->smallInteger('correlativo');

            $table->bigInteger('interesado_id')->unsigned()->index();
            $table->foreign('interesado_id')->references('id')->on('interesado');

            $table->bigInteger('tramite_id')->unsigned()->index();
            $table->foreign('tramite_id')->references('id')->on('tramite');

            $table->date('ingreso');

            $table->bigInteger('estado_id')->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('estado');

            $table->boolean('resuelto')->default(false);

            $table->index(['estado_id', 'anio_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estadistica');
    }
}
