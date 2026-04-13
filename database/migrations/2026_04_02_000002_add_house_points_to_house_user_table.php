<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('house_user', function (Blueprint $table) {
            $table->unsignedInteger('house_points')->default(0)->after('joined_at');
            $table->unsignedInteger('house_level')->default(1)->after('house_points');
        });
    }

    public function down(): void
    {
        Schema::table('house_user', function (Blueprint $table) {
            $table->dropColumn(['house_points', 'house_level']);
        });
    }
};
