<?php

namespace Database\Seeders;

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
        $this->call(CategorySeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(MoviesSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(ListsSeeder::class);
    }
}
