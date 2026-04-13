<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('house_user', function (Blueprint $table) {
            $table->text('message')->nullable()->after('status');
            $table->string('role_membro', 50)->nullable()->after('role');
            $table->string('entities', 500)->nullable()->after('role_membro');
            $table->timestamp('cancelled_at')->nullable()->after('joined_at');
        });

        // Adiciona 'cancelled' ao check constraint do status (PostgreSQL apenas)
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE house_user DROP CONSTRAINT IF EXISTS house_user_status_check");
            DB::statement("ALTER TABLE house_user ADD CONSTRAINT house_user_status_check
                CHECK (status IN ('pending','active','rejected','removed','cancelled'))");
        }
    }

    public function down(): void
    {
        Schema::table('house_user', function (Blueprint $table) {
            $table->dropColumn(['message', 'role_membro', 'entities', 'cancelled_at']);
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE house_user DROP CONSTRAINT IF EXISTS house_user_status_check");
            DB::statement("ALTER TABLE house_user ADD CONSTRAINT house_user_status_check
                CHECK (status IN ('pending','active','rejected','removed'))");
        }
    }
};
