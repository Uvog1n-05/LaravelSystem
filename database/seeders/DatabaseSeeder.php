<?php

namespace Database\Seeders;

 //use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create admin user
        \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@tmc.com',
            'password' => 'admin123',
            'role' => 'admin'
        ]);

        // Run other seeders
        $this->call([
            GenreSeeder::class,
            BooksSeeder::class,
        ]);


    }
}
