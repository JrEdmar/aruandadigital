<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('studies', function (Blueprint $table) {
            if (! Schema::hasColumn('studies', 'content_file')) {
                $table->string('content_file')->nullable()->after('content_body');
            }
        });

        // PostgreSQL: expand content_type enum to include 'pdf'
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE studies DROP CONSTRAINT IF EXISTS studies_content_type_check");
            DB::statement("ALTER TABLE studies ADD CONSTRAINT studies_content_type_check
                CHECK (content_type IN ('video','audio','text','pdf'))");
        }
    }

    public function down(): void
    {
        Schema::table('studies', function (Blueprint $table) {
            if (Schema::hasColumn('studies', 'content_file')) {
                $table->dropColumn('content_file');
            }
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE studies DROP CONSTRAINT IF EXISTS studies_content_type_check");
            DB::statement("ALTER TABLE studies ADD CONSTRAINT studies_content_type_check
                CHECK (content_type IN ('video','audio','text'))");
        }
    }
};
