<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblIngredienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ingrediente', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['PRODUCTO', 'MATERIA PRIMA']);

            $table->bigInteger('receta_id')->unsigned()->index();
            $table->foreign('receta_id')->references('id')->on('tbl_receta');

            $table->string('categoria', 75);
            $table->bigInteger('categoria_id')->unsigned()->index();
            $table->foreign('categoria_id')->references('id')->on('tbl_categoria');

            $table->bigInteger('informacion_id')->unsigned()->index();
            $table->foreign('informacion_id')->references('id')->on('tbl_informacion');

            $table->string('netsuit', 100);
            $table->string('nombre', 150);

            $table->string('marca', 75);
            $table->longText('alergenos')->nullable();

            $table->decimal('cantidad', 14, 4)->default(1);
            $table->string('nomenclatura', 20);

            $table->timestamp('created_at', 0);
            $table->string('created_by', 25);

            $table->unique(array('receta_id', 'categoria_id', 'informacion_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_ingrediente');
    }
}
