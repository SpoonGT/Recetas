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
            $table->string('codigo_receta', 50)->unique();
            $table->string('netsuit', 100)->unique();
            $table->string('codigo_barra', 50)->unique();
            $table->string('nombre', 150)->unique();
            $table->boolean('activo')->default(true);
            $table->boolean('aprobado')->default(false);

            $table->bigInteger('chef_id')->unsigned()->index();
            $table->foreign('chef_id')->references('id')->on('tbl_usuario');

            $table->timestamps();
            $table->softDeletes();

            $table->string('created_by', 25);
            $table->string('updated_by', 25)->nullable();
            $table->string('deleted_by', 25)->nullable();

            $table->index(array('id', 'activo', 'aprobado'));
            $table->index(array('activo', 'aprobado'));
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
