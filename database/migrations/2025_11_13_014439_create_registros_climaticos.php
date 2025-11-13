<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registros_climaticos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finca_id')->constrained()->onDelete('cascade');
            $table->date('fecha');
            $table->decimal('temperatura_min', 5, 2)->nullable(); // °C
            $table->decimal('temperatura_max', 5, 2)->nullable();
            $table->decimal('humedad', 5, 2)->nullable(); // %
            $table->decimal('precipitacion', 8, 2)->nullable(); // mm
            $table->string('eventos')->nullable(); // helada, granizo, sequía
            $table->decimal('factor_clima', 3, 2)->default(1.00); // para cálculo IDC
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registros_climaticos');
    }
};
