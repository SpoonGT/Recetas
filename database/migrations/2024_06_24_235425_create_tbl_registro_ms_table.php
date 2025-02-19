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
            
            $table->string('codigo', 50)->unique();
            $table->longText('documento');

            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_vencimiento');

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
        Schema::dropIfExists('tbl_registro_ms');
    }
}
