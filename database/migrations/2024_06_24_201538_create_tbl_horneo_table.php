<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblHorneoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_horneo', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('receta_id')->unsigned()->index();
            $table->foreign('receta_id')->references('id')->on('tbl_receta');

            $table->time('tiempo_horneo');
            $table->smallInteger('horno');

            $table->smallInteger('temperatura');
            $table->string('nomenclatura', 20);

            $table->bigInteger('unidad_id')->unsigned()->index();
            $table->foreign('unidad_id')->references('id')->on('tbl_unidad');

            $table->longText('otros')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->string('created_by', 25);
            $table->string('updated_by', 25)->nullable();
            $table->string('deleted_by', 25)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_horneo');
    }
}
