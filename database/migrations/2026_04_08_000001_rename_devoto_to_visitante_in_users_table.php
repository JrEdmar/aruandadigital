<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Atualiza registros existentes com role='devoto' para 'visitante'
        DB::table('users')->where('role', 'devoto')->update(['role' => 'visitante']);

        // PostgreSQL: o enum vira CHECK constraint — precisa dropar e recriar
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check');
            DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (
                role::text = ANY (ARRAY[
                    'visitante'::text, 'membro'::text, 'assistente'::text, 'dirigente'::text,
                    'loja'::text, 'loja_master'::text, 'moderador'::text, 'admin'::text
                ])
            )");
            DB::statement("ALTER TABLE users ALTER COLUMN role SET DEFAULT 'visitante'");
        }
    }

    public function down(): void
    {
        DB::table('users')->where('role', 'visitante')->update(['role' => 'devoto']);

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check');
            DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (
                role::text = ANY (ARRAY[
                    'devoto'::text, 'membro'::text, 'assistente'::text, 'dirigente'::text,
                    'loja'::text, 'loja_master'::text, 'moderador'::text, 'admin'::text
                ])
            )");
            DB::statement("ALTER TABLE users ALTER COLUMN role SET DEFAULT 'devoto'");
        }
    }
};
