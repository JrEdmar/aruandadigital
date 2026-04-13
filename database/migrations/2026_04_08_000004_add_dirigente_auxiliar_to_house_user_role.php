<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE house_user DROP CONSTRAINT IF EXISTS house_user_role_check');
            DB::statement("ALTER TABLE house_user ADD CONSTRAINT house_user_role_check
                CHECK (role IN ('membro','assistente','dirigente auxiliar','dirigente'))");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE house_user DROP CONSTRAINT IF EXISTS house_user_role_check');
            DB::statement("ALTER TABLE house_user ADD CONSTRAINT house_user_role_check
                CHECK (role IN ('membro','assistente','dirigente'))");
        }
    }
};
