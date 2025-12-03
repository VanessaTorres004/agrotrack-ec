<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cosechas', function (Blueprint $table) {
            if (!Schema::hasColumn('cosechas', 'fecha_cosecha')) {
                $table->renameColumn('fecha', 'fecha_cosecha');
            }

            if (!Schema::hasColumn('cosechas', 'cantidad_kg')) {
                $table->renameColumn('cantidad', 'cantidad_kg');
            }

            if (!Schema::hasColumn('cosechas', 'mermas')) {
                $table->decimal('mermas', 5, 2)->nullable()->after('calidad');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cosechas', function (Blueprint $table) {
            if (Schema::hasColumn('cosechas', 'fecha_cosecha')) {
                $table->renameColumn('fecha_cosecha', 'fecha');
            }

            if (Schema::hasColumn('cosechas', 'cantidad_kg')) {
                $table->renameColumn('cantidad_kg', 'cantidad');
            }

            if (Schema::hasColumn('cosechas', 'mermas')) {
                $table->dropColumn('mermas');
            }
        });
    }
};
