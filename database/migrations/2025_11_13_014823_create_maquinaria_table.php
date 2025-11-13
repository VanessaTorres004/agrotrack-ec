<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maquinaria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finca_id')->constrained('fincas')->onDelete('cascade');
            $table->string('identificador')->unique();
            $table->enum('tipo', ['tractor', 'cosechadora', 'sembradora', 'fumigadora', 'otro']);
            $table->string('marca');
            $table->string('modelo');
            $table->integer('horas_uso')->default(0);
            $table->enum('estado', ['operativa', 'mantenimiento', 'fuera_servicio'])->default('operativa');
            $table->date('fecha_ultimo_servicio')->nullable();
            $table->date('fecha_proximo_servicio')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maquinaria');
    }
};

