<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblRegistroMsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_registro_ms', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 200);
            $table->string('formato', 10);
            $table->string('ruta', 500);

            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_vencimiento');

            $table->bigInteger('ficha_tecnica_id')->unsigned()->index();
            $table->foreign('ficha_tecnica_id')->references('id')->on('tbl_ficha_tecnica');

            $table->timestamp('created_at', 0);
            $table->softDeletes();

            $table->string('created_by', 25);
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
        Schema::dropIfExists('tbl_registro_ms');
    }
}
