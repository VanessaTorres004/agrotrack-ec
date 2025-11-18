<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cosechas', function (Blueprint $table) {
            if (!Schema::hasColumn('cosechas', 'cantidad_kg')) {
                $table->decimal('cantidad_kg', 10, 2)->nullable()->after('cantidad');
            }
            if (!Schema::hasColumn('cosechas', 'fecha_cosecha')) {
                $table->date('fecha_cosecha')->nullable()->after('fecha');
            }
        });
        
        // Copy data from cantidad to cantidad_kg if the source column exists
        if (Schema::hasColumn('cosechas', 'cantidad') && Schema::hasColumn('cosechas', 'cantidad_kg')) {
            DB::statement('UPDATE cosechas SET cantidad_kg = cantidad WHERE cantidad_kg IS NULL');
        }
        
        // Copy data from fecha to fecha_cosecha if the source column exists
        if (Schema::hasColumn('cosechas', 'fecha') && Schema::hasColumn('cosechas', 'fecha_cosecha')) {
            DB::statement('UPDATE cosechas SET fecha_cosecha = fecha WHERE fecha_cosecha IS NULL');
        }
    }

    public function down(): void
    {
        Schema::table('cosechas', function (Blueprint $table) {
            $table->dropColumn(['cantidad_kg', 'fecha_cosecha']);
        });
    }
};
