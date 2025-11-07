<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */

    private function formatTimestamps(array $ratings): array
    {
        return array_map(function ($rating) {
            if ($rating['created_at'] instanceof \DateTime) {
                $rating['created_at'] = $rating['created_at']->format('Y-m-d H:i:s');
            }

            if ($rating['updated_at'] instanceof \DateTime) {
                $rating['updated_at'] = $rating['updated_at']->format('Y-m-d H:i:s');
            }

            return $rating;
        }, $ratings);
    }

    public function run(): void
    {
        Rating::truncate();
        // Author::factory(1000)->create();
        // Category::factory(3000)->create();

        // Book::factory(100000)->create();

        if (Book::count() === 0) {
            throw new \Exception('No books found. Please seed authors, categories, and books first.');
        }

        $bookIds = Book::pluck('id')->toArray();

        $minUsers = 1000;
        $existingUsers = User::count();
        if ($existingUsers < $minUsers) {
            $toCreate = $minUsers - $existingUsers;
            $batch = 1000;
            for ($j = 0; $j < ceil($toCreate / $batch); $j++) {
                $chunkSize = min($batch, $toCreate - ($j * $batch));
                $rows = [];
                $ts = time();
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
            }
        }

        $userIds = User::pluck('id')->toArray();
        $faker = \Faker\Factory::create();

        DB::disableQueryLog();

    $batchSize = 10000;
    $totalRatings = 500000;

        for ($i = 0; $i < $totalRatings / $batchSize; $i++) {
            $ratings = Rating::factory()
                ->count($batchSize)
                ->make()
                ->map(function ($rating) use ($bookIds, $userIds, $faker) {
                    $created = $faker->dateTimeBetween('-30 days', 'now');

                    return [
                        'book_id' => $faker->randomElement($bookIds),
                        'user_id' => $faker->randomElement($userIds),
                        'rating' => $rating->rating,
                        'created_at' => $created->format('Y-m-d H:i:s'),
                        'updated_at' => $created->format('Y-m-d H:i:s'),
                    ];
                })
                ->toArray();

            DB::table('ratings')->insertOrIgnore($ratings);

            echo "Inserted batch " . ($i + 1) . " of " . ($totalRatings / $batchSize) . "\n";
        }

    }
}
