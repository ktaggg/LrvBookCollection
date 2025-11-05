<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
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
