<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('house_finance_members')) {
            Schema::create('house_finance_members', function (Blueprint $table) {
                $table->id();
                $table->foreignId('finance_id')->constrained('house_finances')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->enum('status', ['pending', 'paid'])->default('pending');
                $table->timestamp('paid_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->unique(['finance_id', 'user_id']);
                $table->index(['finance_id', 'status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('house_finance_members');
    }
};
