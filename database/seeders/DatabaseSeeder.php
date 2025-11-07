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

        $chunks = 100;
        $booksPerChunk = 1000;

        for ($i = 0; $i < $chunks; $i++) {
            Book::factory($booksPerChunk)->create();
            echo "Created books chunk " . ($i + 1) . " of $chunks\n";
        }

    }
}
