<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Book;
use App\Models\Rating;


class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Book::count() === 0) {
            throw new \Exception('No books found. Please seed authors, categories, and books first.');
        }

        $userIds = User::pluck('id')->toArray();
        $faker = \Faker\Factory::create();

        DB::disableQueryLog();

        $batchSize = 10000;
        $totalRatings = 500000;

        $bookIds = Book::pluck('id')->toArray();

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
