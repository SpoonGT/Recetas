<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblImportacionNetsuitTable extends Migration
{
    /**
     * Run the migrations.
     * importas ---> En el proceso de importación es de 500 registros pero solo fueron importados 450, estos 450 registros se encuentra en proceso de validación.
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_importacion_netsuit', function (Blueprint $table) {
            $table->id();
            $table->enum('estado', ['PROCESANDO', 'VALIDADO'])->default('PROCESANDO');
            $table->timestamp('created_at', 0);
            $table->string('created_by', 25);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_importacion_netsuit');
    }
}
