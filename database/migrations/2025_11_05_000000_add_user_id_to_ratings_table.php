<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            // Add nullable user_id so existing records won't break. You can make this non-nullable
            // in a later migration after backfilling values if desired.
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            // dropConstrainedForeignId is available in Laravel 8.57+
            if (method_exists($table, 'dropConstrainedForeignId')) {
                $table->dropConstrainedForeignId('user_id');
            } else {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};
