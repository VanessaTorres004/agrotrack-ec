<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indicadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultivo_id')->constrained()->onDelete('cascade');
            $table->date('fecha_calculo');
            $table->decimal('rendimiento', 5, 2)->default(0); // 0-100
            $table->decimal('oportunidad', 5, 2)->default(0); // 0-100
            $table->decimal('calidad', 5, 2)->default(0); // 0-100
            $table->decimal('registro', 5, 2)->default(0); // 0-100
            $table->decimal('factor_clima', 3, 2)->default(1.00);
            $table->decimal('idc', 5, 2)->default(0); // Índice final calculado
            $table->enum('clasificacion', ['excelente', 'bueno', 'en_riesgo', 'critico']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indicadores');
    }
};

