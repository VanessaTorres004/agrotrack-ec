<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cultivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finca_id')->constrained()->onDelete('cascade');
            $table->string('nombre'); // ej: Maíz, Banano, Cacao
            $table->string('variedad')->nullable();
            $table->decimal('area', 8, 2); // hectáreas
            $table->date('fecha_siembra');
            $table->date('fecha_cosecha_estimada')->nullable();
            $table->enum('estado', ['activo', 'cosechado', 'inactivo'])->default('activo');
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cultivos');
    }
};

