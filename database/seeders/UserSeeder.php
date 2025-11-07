<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $minUsers = 1000;
        $existingUsers = User::count();
        echo "Current users: $existingUsers\n";

        if ($existingUsers < $minUsers) {
            $toCreate = $minUsers - $existingUsers;
            $batch = 100;
            echo "Will create $toCreate more users\n";

            for ($j = 0; $j < ceil($toCreate / $batch); $j++) {
                $chunkSize = min($batch, $toCreate - ($j * $batch));
                $rows = [];
                $ts = time();

                echo "Processing batch " . ($j + 1) . " (size: $chunkSize)...\n";

                for ($k = 0; $k < $chunkSize; $k++) {
                    $unique = ($j * $batch) + $k + $existingUsers + 1;
                    $email = "seed_user_{$ts}_{$unique}@example.org";
                    $rows[] = [
                        'name' => "Seed User {$unique}",
                        'email' => $email,
                        'email_verified_at' => now(),
                        'password' => Hash::make('password'),
                        'remember_token' => Str::random(10),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                DB::table('users')->insertOrIgnore($rows);
                echo "Completed batch " . ($j + 1) . "\n";
            }
        }
    }
}
