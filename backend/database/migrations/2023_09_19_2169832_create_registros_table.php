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
        Schema::create('registros', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('Marca');

            $table->unsignedBigInteger('etapa_id');
            $table->foreign('etapa_id')->references('id')->on('etapas')->onDelete('cascade');


            $table->unsignedBigInteger('componente_id');
            $table->foreign('componente_id')->references('id')->on('componentes')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registros');
    }
};
