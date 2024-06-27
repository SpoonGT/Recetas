<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblRevisionRecetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_revision_receta', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('numero');

            $table->boolean('aprobado')->default(false);

            $table->bigInteger('receta_id')->unsigned()->index();
            $table->foreign('receta_id')->references('id')->on('tbl_receta');

            $table->bigInteger('area_aprueba_id')->unsigned()->index();
            $table->foreign('area_aprueba_id')->references('id')->on('tbl_area_aprueba');

            $table->bigInteger('usuario_id')->unsigned()->index();
            $table->foreign('usuario_id')->references('id')->on('tbl_usuario');

            $table->timestamp('created_at', 0);
            $table->string('created_by', 25);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_revision_receta');
    }
}
