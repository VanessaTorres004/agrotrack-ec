<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cosechas', function (Blueprint $table) {
            $table->renameColumn('fecha', 'fecha_cosecha');
            
            if (!Schema::hasColumn('cosechas', 'cantidad_kg')) {
                $table->decimal('cantidad_kg', 10, 2)->after('fecha_cosecha')->nullable();
            }
        });
        
        DB::statement('UPDATE cosechas SET cantidad_kg = cantidad WHERE cantidad_kg IS NULL');
    }

    public function down(): void
    {
        Schema::table('cosechas', function (Blueprint $table) {
            $table->renameColumn('fecha_cosecha', 'fecha');
            $table->dropColumn('cantidad_kg');
        });
    }
};
