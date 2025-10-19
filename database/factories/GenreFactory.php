<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Genre>
 */
class GenreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
          'genre_name' => fake()->randomElement([
        'Fantasy',
        'Science Fiction',
        'Mystery',
        'Thriller',
        'Romance',
        'Horror',
        'Historical Fiction',
        'Non-fiction',
        'Adventure',
        'Drama',
        'Romance',
        'Comedy',
        'Action',
        'Sci-fi',
    ]),
    
        'description' =>fake()->realText(10),
        ];
    }
}
