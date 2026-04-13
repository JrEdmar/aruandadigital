<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();

            // Identificação
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('cnpj', 18)->nullable();
            $table->enum('type', ['umbanda', 'candomble', 'misto', 'outro'])->default('umbanda');

            // Descrição
            $table->text('description')->nullable();
            $table->text('spiritual_line')->nullable();
            $table->text('history')->nullable();
            $table->text('differentials')->nullable();

            // Mídia
            $table->string('cover_image')->nullable();
            $table->string('logo_image')->nullable();

            // Contato
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('website')->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();

            // Funcionamento
            $table->date('foundation_date')->nullable();
            $table->integer('capacity')->nullable();
            $table->string('schedule')->nullable();

            // Endereço
            $table->string('zip_code', 10)->nullable();
            $table->string('street')->nullable();
            $table->string('number', 20)->nullable();
            $table->string('complement')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('city')->nullable();
            $table->string('state', 2)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Status
            $table->enum('status', ['pending', 'active', 'inactive', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'city']);
            $table->index(['latitude', 'longitude']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('houses');
    }
};
