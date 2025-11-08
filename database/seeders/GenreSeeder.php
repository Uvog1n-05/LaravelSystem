<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Genre;


/**
 * Seeder class for populating the genres table with predefined book categories.
 * This ensures the library system has a consistent set of genres for book classification.
 */
class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates a standard set of literary genres with descriptions.
     *
     * @return void
     */
    public function run()
    {
        $genres = [
            [
                'name' => 'Fiction',
                'description' => 'Literary works created from the imagination'
            ],
            [
                'name' => 'Non-fiction',
                'description' => 'Literature based on facts and real events'
            ],
            [
                'name' => 'Mystery',
                'description' => 'Fiction dealing with the solution of a crime or puzzle'
            ],
            [
                'name' => 'Science Fiction',
                'description' => 'Fiction based on imagined future scientific or technological advances'
            ],
            [
                'name' => 'Fantasy',
                'description' => 'Fiction featuring magical and supernatural elements'
            ],
            [
                'name' => 'Romance',
                'description' => 'Fiction focusing on romantic love relationships'
            ],
            [
                'name' => 'Thriller',
                'description' => 'Fiction intended to create suspense and excitement'
            ],
            [
                'name' => 'Horror',
                'description' => 'Fiction intended to frighten, scare, or disgust'
            ],
            [
                'name' => 'Historical Fiction',
                'description' => 'Fiction set in the past, often during significant historical events'
            ],
            [
                'name' => 'Biography',
                'description' => 'Non-fiction narrative of a person\'s life'
            ],
            [
                'name' => 'Self-Help',
                'description' => 'Books aimed at personal improvement'
            ],
            [
                'name' => 'Poetry',
                'description' => 'Literary work in which special intensity is given to the expression of feelings and ideas'
            ],
            [
                'name' => 'Drama',
                'description' => 'Literature in the form of prose or verse presenting dialogue and action'
            ],
            [
                'name' => 'Children\'s Literature',
                'description' => 'Books specifically written for young readers'
            ],
            [
                'name' => 'Young Adult',
                'description' => 'Literature aimed at readers from 12 to 18 years of age'
            ]
        ];

        foreach ($genres as $genre) {
            Genre::create($genre);
        }
    }
}
