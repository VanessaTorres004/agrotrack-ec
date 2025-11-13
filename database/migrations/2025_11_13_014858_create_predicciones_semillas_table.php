<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('predicciones_semillas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('cultivo_id')->constrained('cultivos')->onDelete('cascade');
            $table->decimal('area_hectareas', 10, 2);
            $table->decimal('temperatura_promedio', 5, 2)->nullable();
            $table->decimal('humedad_promedio', 5, 2)->nullable();
            $table->decimal('ph_suelo', 4, 2)->nullable();
            $table->decimal('densidad_siembra', 10, 2);
            $table->decimal('uso_promedio_historico', 10, 2);
            $table->decimal('factor_desperdicio', 5, 4)->default(0.10);
            $table->decimal('factor_climatico', 5, 4)->default(1.00);
            $table->decimal('paquetes_predichos', 10, 2);
            $table->decimal('ahorro_estimado_porcentaje', 5, 2)->nullable();
            $table->enum('nivel_confianza', ['estable', 'variable', 'riesgo'])->default('estable');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('predicciones_semillas');
    }
};

