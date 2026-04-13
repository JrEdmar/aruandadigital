<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('house_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('house_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('role', 50)->default('membro');
            $table->string('status', 30)->default('pending');
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();

            $table->unique(['house_id', 'user_id']);
            $table->index(['house_id', 'status']);
        });

        // PostgreSQL: constraints are managed explicitly (SQLite uses plain strings)
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE house_user ADD CONSTRAINT house_user_role_check
                CHECK (role IN ('membro','assistente','dirigente'))");
            DB::statement("ALTER TABLE house_user ADD CONSTRAINT house_user_status_check
                CHECK (status IN ('pending','active','rejected','removed'))");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('house_user');
    }
};
