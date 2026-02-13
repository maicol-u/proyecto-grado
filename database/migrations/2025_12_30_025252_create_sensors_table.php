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
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crop_id');
            $table->unsignedBigInteger('type_id');

            $table->string('name', 100);
            $table->string('model', 100)->nullable();
            $table->string('unit', 20)->nullable();
            $table->enum('status', ['active', 'inactive', 'maintenance'])
                ->default('active');

            $table->enum('alert_level', ['normal', 'high', 'low'])
                ->default('normal');

            $table->integer('reading_interval')
                ->comment('Reading interval in seconds');

            $table->decimal('min_value', 10, 2)->nullable();
            $table->decimal('max_value', 10, 2)->nullable();

            $table->foreign('crop_id')
                ->references('id')
                ->on('crops')
                ->onDelete('restrict');

            $table->foreign('type_id')
                ->references('id')
                ->on('sensor_types')
                ->onDelete('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensors');
    }
};
