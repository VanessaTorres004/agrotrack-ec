<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('actualizaciones', function (Blueprint $table) {

            // Renombrar fecha → fecha_actividad
            if (Schema::hasColumn('actualizaciones', 'fecha') &&
                !Schema::hasColumn('actualizaciones', 'fecha_actividad')) {
                $table->renameColumn('fecha', 'fecha_actividad');
            }

            // Renombrar observaciones → descripcion
            if (Schema::hasColumn('actualizaciones', 'observaciones') &&
                !Schema::hasColumn('actualizaciones', 'descripcion')) {
                $table->renameColumn('observaciones', 'descripcion');
            }

            // Renombrar tipo → tipo_actividad
            if (Schema::hasColumn('actualizaciones', 'tipo') &&
                !Schema::hasColumn('actualizaciones', 'tipo_actividad')) {
                $table->renameColumn('tipo', 'tipo_actividad');
            }

            // Agregar campo costo si no existe
            if (!Schema::hasColumn('actualizaciones', 'costo')) {
                $table->decimal('costo', 10, 2)->nullable()->after('tipo_actividad');
            }
        });
    }

    public function down(): void
    {
        Schema::table('actualizaciones', function (Blueprint $table) {

            if (Schema::hasColumn('actualizaciones', 'fecha_actividad')) {
                $table->renameColumn('fecha_actividad', 'fecha');
            }

            if (Schema::hasColumn('actualizaciones', 'descripcion')) {
                $table->renameColumn('descripcion', 'observaciones');
            }

            if (Schema::hasColumn('actualizaciones', 'tipo_actividad')) {
                $table->renameColumn('tipo_actividad', 'tipo');
            }

            if (Schema::hasColumn('actualizaciones', 'costo')) {
                $table->dropColumn('costo');
            }
        });
    }
};
