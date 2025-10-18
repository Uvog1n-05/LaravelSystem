<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Genre;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Books>
 */
class BooksFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'title' => $this->faker->name(),
        'author' => $this->faker->name(),
        'about' => $this->faker->realText(500),
        'number_of_books' => $this->faker->numberBetween(1, 10),
        'genre_id' => Genre::inRandomOrder()->first()->id,
        ];
    }
}
