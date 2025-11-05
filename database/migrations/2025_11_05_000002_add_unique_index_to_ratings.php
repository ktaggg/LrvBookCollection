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
            // Add a composite unique index to prevent duplicate ratings per user per book.
            // MySQL allows multiple NULLs in unique indexes, so anonymous ratings (NULL user_id)
            // are still allowed.
            $table->unique(['book_id', 'user_id'], 'ratings_book_user_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropUnique('ratings_book_user_unique');
        });
    }
};
