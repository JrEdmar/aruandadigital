<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('house_suggestions')) {
            Schema::create('house_suggestions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('house_id')->constrained('houses')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->text('message');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
                $table->index(['house_id', 'read_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('house_suggestions');
    }
};
