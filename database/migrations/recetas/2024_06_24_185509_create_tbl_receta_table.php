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
            $table->smallInteger('version');

            $table->string('codigo_receta', 50);

            $table->string('netsuit', 50)->nullable();
            $table->string('codigo_barra', 50)->nullable();

            $table->string('nombre', 150);

            $table->boolean('activo')->default(true);
            $table->enum('estado', ['REVISIÓN', 'APROBADO', 'RECHAZADO'])->default('REVISIÓN');
            $table->dateTime('fecha_aprobacion')->nullable();
            $table->bigInteger('aprueba_id')->default(0)->unsigned()->index();

            $table->bigInteger('chef_id')->unsigned()->index();
            $table->foreign('chef_id')->references('id')->on('tbl_usuario');

            $table->bigInteger('produce_id')->unsigned()->index();
            $table->foreign('produce_id')->references('id')->on('tbl_area');

            $table->bigInteger('empaque_id')->unsigned()->index();
            $table->foreign('empaque_id')->references('id')->on('tbl_area');

            $table->bigInteger('correlativo_codigo_id')->unsigned()->index();
            $table->foreign('correlativo_codigo_id')->references('id')->on('tbl_correlativo_codigo');

            $table->timestamps();
            $table->softDeletes();

            $table->string('created_by', 25);
            $table->string('updated_by', 25)->nullable();
            $table->string('deleted_by', 25)->nullable();

            $table->unique(array('version', 'correlativo_codigo_id', 'codigo_receta'));
            $table->index(array('id', 'activo', 'estado', 'version'));
            $table->index(array('netsuit', 'activo', 'estado', 'version'));
            $table->index(array('codigo_barra', 'activo', 'estado', 'version', 'correlativo_codigo_id'));
            $table->index(array('codigo_receta', 'activo', 'estado', 'version', 'correlativo_codigo_id'));
            $table->index(array('activo', 'estado', 'version'));
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
