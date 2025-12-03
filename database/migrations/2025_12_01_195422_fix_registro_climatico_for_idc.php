<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registro_climaticos', function (Blueprint $table) {
            if (Schema::hasColumn('registro_climaticos', 'precipitacion_mm')) {
                $table->renameColumn('precipitacion_mm', 'precipitacion');
            }
            if (Schema::hasColumn('registro_climaticos', 'humedad_relativa')) {
                $table->renameColumn('humedad_relativa', 'humedad');
            }
        });
    }

    public function down(): void
    {
        Schema::table('registro_climaticos', function (Blueprint $table) {
            if (Schema::hasColumn('registro_climaticos', 'precipitacion')) {
                $table->renameColumn('precipitacion', 'precipitacion_mm');
            }
            if (Schema::hasColumn('registro_climaticos', 'humedad')) {
                $table->renameColumn('humedad', 'humedad_relativa');
            }
        });
    }
};
