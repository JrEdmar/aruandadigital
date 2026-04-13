<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('studies', function (Blueprint $table) {
            if (! Schema::hasIndex('studies', ['house_id', 'published'])) {
                $table->index(['house_id', 'published'], 'studies_house_published_idx');
            }
        });

        Schema::table('tasks', function (Blueprint $table) {
            if (! Schema::hasIndex('tasks', ['house_id', 'status'])) {
                $table->index(['house_id', 'status'], 'tasks_house_status_idx');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (! Schema::hasIndex('order_items', ['order_id'])) {
                $table->index('order_id', 'order_items_order_id_idx');
            }
            if (! Schema::hasIndex('order_items', ['product_id'])) {
                $table->index('product_id', 'order_items_product_id_idx');
            }
        });

        // house_user já tem índice [house_id, status] criado na migração original
        // não duplicar
    }

    public function down(): void
    {
        Schema::table('studies', function (Blueprint $table) {
            $table->dropIndex('studies_house_published_idx');
        });
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex('tasks_house_status_idx');
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex('order_items_order_id_idx');
            $table->dropIndex('order_items_product_id_idx');
        });
    }
};
