<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $systemEmail = 'system.user@local';

        $systemUser = DB::table('users')->where('email', $systemEmail)->first();

        if (! $systemUser) {
            $systemUserId = DB::table('users')->insertGetId([
                'name' => 'System User',
                'email' => $systemEmail,
                'password' => bcrypt(str()->random(16)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $systemUserId = $systemUser->id;
        }

        // Backfill existing NULL user_id values
        DB::table('ratings')->whereNull('user_id')->update(['user_id' => $systemUserId]);

        // Drop existing FK if it exists
        try {
            Schema::table('ratings', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        } catch (\Throwable $e) {
            // ignore if foreign key does not exist or different name
        }

        // Modify column to NOT NULL using raw SQL to avoid doctrine/dbal requirement
        DB::statement('ALTER TABLE ratings MODIFY user_id BIGINT UNSIGNED NOT NULL');

        // Recreate foreign key constraint
        DB::statement('ALTER TABLE ratings ADD CONSTRAINT ratings_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::beginTransaction();

        try {
            // Drop FK
            try {
                DB::statement('ALTER TABLE ratings DROP FOREIGN KEY ratings_user_id_foreign');
            } catch (\Throwable $e) {
                // ignore
            }

            // Make column nullable again
            DB::statement('ALTER TABLE ratings MODIFY user_id BIGINT UNSIGNED NULL');

            // Recreate FK (nullable)
            DB::statement('ALTER TABLE ratings ADD CONSTRAINT ratings_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
};
