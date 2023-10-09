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
        Schema::create('componentes', function (Blueprint $table) {
            $table->id();
            $table->string("Nombre");
            $table->string("Descripcion");
            $table->string("Unidad");
            $table->string("DireccionIp");

            $table->unsignedBigInteger('tipo_componente_id');
            $table->foreign('tipo_componente_id')->references('id')->on('tipo_componentes');

            $table->unsignedBigInteger('etapa_id')->nullable();
            $table->foreign('etapa_id')->references('id')->on('etapas');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('componentes');
    }
};
