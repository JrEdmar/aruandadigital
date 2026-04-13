<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                'visitante', 'membro', 'assistente', 'dirigente',
                'loja', 'loja_master', 'moderador', 'admin',
            ])->default('visitante')->after('email');

            $table->string('phone', 20)->nullable()->after('role');
            $table->string('cpf', 14)->nullable()->after('phone');
            $table->date('birth_date')->nullable()->after('cpf');
            $table->string('avatar')->nullable()->after('birth_date');
            $table->string('google_id')->nullable()->after('avatar');
            $table->string('facebook_id')->nullable()->after('google_id');
            $table->timestamp('lgpd_accepted_at')->nullable()->after('facebook_id');
            $table->integer('points')->default(0)->after('lgpd_accepted_at');
            $table->integer('level')->default(1)->after('points');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role', 'phone', 'cpf', 'birth_date', 'avatar',
                'google_id', 'facebook_id', 'lgpd_accepted_at',
                'points', 'level',
            ]);
        });
    }
};
