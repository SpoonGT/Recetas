<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblControlCambioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_control_cambio', function (Blueprint $table) {
            $table->id();
            $table->longText('observacion')->nullable();
            $table->boolean('aplicado')->default(false);

            $table->bigInteger('receta_id')->unsigned()->index();
            $table->foreign('receta_id')->references('id')->on('tbl_receta');

            $table->bigInteger('revision_receta_id')->unsigned()->index();
            $table->foreign('revision_receta_id')->references('id')->on('tbl_revision_receta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_control_cambio');
    }
}
