<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ganado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finca_id')->constrained('fincas')->onDelete('cascade');
            $table->string('identificador')->unique();
            $table->enum('tipo', ['bovino', 'porcino', 'ovino', 'caprino', 'otro']);
            $table->string('raza');
            $table->integer('edad_meses');
            $table->decimal('peso_kg', 8, 2);
            $table->enum('estado_salud', ['sano', 'observacion', 'enfermo'])->default('sano');
            $table->text('observaciones')->nullable();
            $table->date('fecha_ingreso');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ganado');
    }
};

