<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblImportNetsuitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_import_netsuit', function (Blueprint $table) {
            $table->id();
            $table->string('articulo', 25);
            $table->string('nombre', 500);
            $table->string('unidad', 75);

            $table->timestamp('created_at', 0);
            $table->string('created_by', 25);
            $table->boolean('procesado')->default(false);
            $table->longText('mensaje')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_import_netsuit');
    }
}
