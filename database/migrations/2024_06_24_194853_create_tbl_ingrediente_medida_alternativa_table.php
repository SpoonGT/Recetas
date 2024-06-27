<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblIngredienteMedidaAlternativaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ingrediente_medida_alternativa', function (Blueprint $table) {
            $table->string('cantidad', 50)->default("")->nullable();
            $table->string('nomenclatura', 20);

            $table->bigInteger('unidad_id')->unsigned()->index();
            $table->foreign('unidad_id')->references('id')->on('tbl_unidad');

            $table->bigInteger('ingrediente_id')->unsigned()->index();
            $table->foreign('ingrediente_id')->references('id')->on('tbl_ingrediente');

            $table->timestamps();

            $table->string('created_by', 25);
            $table->string('updated_by', 25)->nullable();

            $table->unique('ingrediente_id');
            $table->index(array('ingrediente_id', 'unidad_id'));
            $table->index('cantidad');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_ingrediente_medida_alternativa');
    }
}
