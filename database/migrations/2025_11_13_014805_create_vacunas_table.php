<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacunas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ganado_id')->constrained('ganado')->onDelete('cascade');
            $table->string('tipo_vacuna');
            $table->date('fecha_aplicacion');
            $table->date('proxima_dosis')->nullable();
            $table->string('veterinario')->nullable();
            $table->text('observaciones')->nullable();
            $table->enum('estado', ['aplicada', 'proxima', 'vencida'])->default('aplicada');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacunas');
    }
};

