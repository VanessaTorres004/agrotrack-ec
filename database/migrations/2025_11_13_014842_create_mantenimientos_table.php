<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mantenimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maquinaria_id')->constrained('maquinaria')->onDelete('cascade');
            $table->date('fecha_mantenimiento');
            $table->enum('tipo', ['preventivo', 'correctivo', 'revision']);
            $table->text('descripcion');
            $table->decimal('costo', 10, 2)->default(0);
            $table->string('tecnico')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mantenimientos');
    }
};

