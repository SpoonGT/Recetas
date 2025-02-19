<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblFichaTecnicaRegistroMsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ficha_tecnica_registro_ms', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('ficha_tecnica_id')->unsigned()->index();
            $table->foreign('ficha_tecnica_id')->references('id')->on('tbl_ficha_tecnica');
            
            $table->bigInteger('registro_ms_id')->unsigned()->index();
            $table->foreign('registro_ms_id')->references('id')->on('tbl_registro_ms');

            $table->timestamp('created_at', 0);
            $table->string('created_by', 25);

            $table->unique(['ficha_tecnica_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_ficha_tecnica_registro_ms');
    }
}
