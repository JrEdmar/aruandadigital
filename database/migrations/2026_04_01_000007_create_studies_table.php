<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('studies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('content_type', ['video', 'audio', 'text'])->default('text');
            $table->string('content_url')->nullable();
            $table->longText('content_body')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('category')->nullable();
            $table->integer('points')->default(20);
            $table->integer('order_column')->default(0);
            $table->boolean('published')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('studies');
    }
};
