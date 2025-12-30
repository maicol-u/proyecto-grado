<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sensores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_invernadero');
            $table->unsignedBigInteger('id_tipo');

            $table->string('nombre', 100);
            $table->string('modelo', 100)->nullable();
            $table->string('unidad', 20)->nullable();

            $table->enum('estado', ['activo', 'inactivo', 'mantenimiento'])
                ->default('activo');

            $table->integer('intervalo_lectura')
                ->comment('Intervalo de lectura en segundos');

            $table->decimal('valor_min', 10, 2)->nullable();
            $table->decimal('valor_max', 10, 2)->nullable();

            $table->foreign('id_invernadero')
                ->references('id')
                ->on('invernaderos')
                ->onDelete('restrict');

            $table->foreign('id_tipo')
                ->references('id')
                ->on('tipos_sensor')
                ->onDelete('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensores');
    }
};
