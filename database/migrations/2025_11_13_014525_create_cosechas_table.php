<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cosechas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultivo_id')->constrained()->onDelete('cascade');
            $table->date('fecha');
            $table->decimal('cantidad', 10, 2); // kg o toneladas
            $table->string('unidad')->default('kg');
            $table->enum('calidad', ['excelente', 'buena', 'regular', 'mala']);
            $table->decimal('mermas', 10, 2)->nullable(); // %
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cosechas');
    }
};

