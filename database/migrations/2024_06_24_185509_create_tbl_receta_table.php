<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblRecetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_receta', function (Blueprint $table) {
            $table->id();

            $table->string('nombre', 150); //Form

            $table->boolean('activo')->default(true); //BackEnd

            $table->bigInteger('estado_id')->unsigned()->index(); //BackEnd
            $table->foreign('estado_id')->references('id')->on('tbl_estado');

            $table->bigInteger('chef_id')->unsigned()->index(); //Form
            $table->foreign('chef_id')->references('id')->on('tbl_usuario');

            $table->bigInteger('produce_id')->unsigned()->index(); //Form
            $table->foreign('produce_id')->references('id')->on('tbl_area');

            $table->bigInteger('empaque_id')->unsigned()->index(); //Form
            $table->foreign('empaque_id')->references('id')->on('tbl_area');

            $table->timestamps(); //BackEnd

            $table->string('created_by', 25); //BackEnd
            $table->string('updated_by', 25)->nullable(); //BackEnd

            $table->index(array('id', 'activo', 'estado_id'));
            $table->index(array('activo', 'estado_id'));
            $table->index(array('chef_id', 'activo', 'estado_id'));
            $table->index(array('produce_id', 'activo', 'estado_id'));
            $table->index(array('empaque_id', 'activo', 'estado_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_receta');
    }
}
