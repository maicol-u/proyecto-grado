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
        Schema::create('usuario_invernaderos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_invernadero');
            $table->primary(['id_usuario', 'id_invernadero']);
            $table->foreign('id_usuario')->references('id')->on('users');
            $table->foreign('id_invernadero')->references('id')->on('invernaderos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_invernaderos');
    }
};
