<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Check if the column doesn't exist and add it
        if (!Schema::hasColumn('cultivos', 'hectareas')) {
            Schema::table('cultivos', function (Blueprint $table) {
                $table->decimal('hectareas', 8, 2)->after('variedad')->default(0);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('cultivos', 'hectareas')) {
            Schema::table('cultivos', function (Blueprint $table) {
                $table->dropColumn('hectareas');
            });
        }
    }
};
