<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Author;
use App\Models\Category;

class BaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Author::factory(1000)->create();
        Category::factory(3000)->create();

        //Location Seeder
        $now = now();
        DB::table('locations')->insert([
            [
                'name' => 'Mall Bali Galeria - JDBooks 1',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Living World - JDBooks 2',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Level 21 - JDBooks 3',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);

        //Status seeder
        DB::table('statuses')->insert([
            [
                'name' => 'Available',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Rented',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Reserved',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);
    }
}
