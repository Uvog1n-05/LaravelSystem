<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Genre;


class GenreSeeder extends Seeder
{
    public function run()
    {
     Genre::factory()->count(10)->create();
    
   
    }
}
