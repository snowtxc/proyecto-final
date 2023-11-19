<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('componente_unidads', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('componente_id');
            $table->unsignedBigInteger('unidades_id');
            $table->double('min');
            $table->double('max');
            $table->foreign('componente_id')->references('id')->on('componentes')->onDelete('cascade');
            $table->foreign('unidades_id')->references('id')->on('unidades')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('componente_unidads');
    }
};
