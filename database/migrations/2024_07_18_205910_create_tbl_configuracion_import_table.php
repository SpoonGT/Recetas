<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblConfiguracionImportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_configuracion_import', function (Blueprint $table) {
            $table->id();
            $table->string('icg', 250);
            $table->string('encabezado', 250);
            $table->smallInteger('posicion');

            $table->bigInteger('plataforma_id')->unsigned()->index();
            $table->foreign('plataforma_id')->references('id')->on('tbl_plataforma');

            $table->timestamps();

            $table->string('created_by', 25);
            $table->string('updated_by', 25)->nullable();

            $table->unique(array('icg', 'encabezado', 'plataforma_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_configuracion_import');
    }
}
