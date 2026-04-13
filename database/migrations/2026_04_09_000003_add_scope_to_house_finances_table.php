<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('house_finances', function (Blueprint $table) {
            if (! Schema::hasColumn('house_finances', 'scope')) {
                $table->string('scope')->default('global')->after('notes');
            }
            // Índice de performance — ignora se já existir
            if (! Schema::hasIndex('house_finances', ['status', 'due_date'])) {
                $table->index(['status', 'due_date']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('house_finances', function (Blueprint $table) {
            if (Schema::hasColumn('house_finances', 'scope')) {
                $table->dropColumn('scope');
            }
        });
    }
};
