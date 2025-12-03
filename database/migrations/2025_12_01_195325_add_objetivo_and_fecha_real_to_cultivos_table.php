<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cultivos', function (Blueprint $table) {
            if (!Schema::hasColumn('cultivos', 'objetivo_produccion')) {
                $table->decimal('objetivo_produccion', 10, 2)->nullable()->after('area');
            }

            if (!Schema::hasColumn('cultivos', 'fecha_cosecha_real')) {
                $table->date('fecha_cosecha_real')->nullable()->after('fecha_cosecha_estimada');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cultivos', function (Blueprint $table) {
            if (Schema::hasColumn('cultivos', 'objetivo_produccion')) {
                $table->dropColumn('objetivo_produccion');
            }

            if (Schema::hasColumn('cultivos', 'fecha_cosecha_real')) {
                $table->dropColumn('fecha_cosecha_real');
            }
        });
    }
};
