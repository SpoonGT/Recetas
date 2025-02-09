<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblInformacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_informacion', function (Blueprint $table) {
            $table->id();
            $table->enum('prefijo', ['PTL', 'PTI', 'SE', 'MP', 'EM', 'AS']);
            $table->smallInteger('codigo');
            $table->string('netsuit', 100)->unique();

            $table->string('nombre', 150);
            $table->longText('descripcion')->nullable();

            $table->bigInteger('marca_id')->unsigned()->index();
            $table->foreign('marca_id')->references('id')->on('tbl_marca');

            $table->bigInteger('unidad_id')->unsigned()->index();
            $table->foreign('unidad_id')->references('id')->on('tbl_unidad');

            $table->timestamps();

            $table->string('created_by', 25);
            $table->string('updated_by', 25)->nullable();

            $table->index(array('netsuit'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_informacion');
    }
}
