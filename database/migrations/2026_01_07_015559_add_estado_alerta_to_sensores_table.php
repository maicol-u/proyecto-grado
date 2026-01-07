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
        Schema::table('sensores', function (Blueprint $table) {
            $table->enum('estado_alerta', ['normal', 'alto', 'bajo'])
                ->default('normal')
                ->after('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sensores', function (Blueprint $table) {
            $table->dropColumn('estado_alerta');
        });
    }
};
