<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
    }
}
