<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'author_id' => \App\Models\Author::inRandomOrder()->first()->id,
            'publisher' => $this->faker->company(),
            'year_published' => $this->faker->year(),
            'isbn' => $this->faker->unique()->isbn13(),
            'location_id' => \App\Models\Location::inRandomOrder()->first()->id,
            'status_id' => \App\Models\Status::inRandomOrder()->first()->id,
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Book $book) {
            $categories = Category::inRandomOrder()->limit(rand(1, 3))->get();
            $book->categories()->attach($categories);
        });
    }
}
