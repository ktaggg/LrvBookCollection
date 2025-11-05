<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Book;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Do not auto-create related Book/User here to avoid accidental
            // inserts when calling ->make() in bulk seeders. The seeder will
            // assign `book_id` and `user_id` explicitly to control uniqueness
            // and avoid duplicate-key errors.
            'rating' => $this->faker->numberBetween(1, 10)
        ];
    }
}
