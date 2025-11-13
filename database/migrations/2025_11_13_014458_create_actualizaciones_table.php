<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actualizaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultivo_id')->constrained()->onDelete('cascade');
            $table->date('fecha');
            $table->enum('tipo', ['plaga', 'riego', 'fertilizacion', 'general']);
            $table->text('observaciones');
            $table->string('accion_tomada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actualizaciones');
    }
};
