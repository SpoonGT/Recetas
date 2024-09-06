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
            $table->enum('tipo', ['PRODUCTO', 'MATERIA PRIMA'])->index(); //BackEnd

            $table->bigInteger('receta_id')->unsigned()->index(); //BackEnd
            $table->foreign('receta_id')->references('id')->on('tbl_receta');

            $table->string('categoria', 75); //BackEnd

            $table->bigInteger('categoria_id')->unsigned()->index(); //Form
            $table->foreign('categoria_id')->references('id')->on('tbl_categoria');

            $table->bigInteger('informacion_id')->unsigned()->index(); //Form
            $table->foreign('informacion_id')->references('id')->on('tbl_informacion');

            $table->string('netsuit', 100); //BackEnd
            $table->string('nombre', 150); //BackEnd

            $table->string('marca', 75); //BackEnd
            $table->longText('alergenos')->nullable(); //BackEnd

            $table->decimal('cantidad', 14, 4)->default(1); //Form
            $table->string('nomenclatura', 20); //BackEnd

            $table->timestamp('created_at', 0); //BackEnd
            $table->string('created_by', 25); //BackEnd

            $table->index(array('receta_id', 'tipo'));
            $table->index(array('receta_id', 'categoria_id', 'informacion_id'));
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
