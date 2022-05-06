<?php

namespace Database\Seeders;

use App\Models\Movies;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MoviesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        $csvFile = fopen(base_path("database/seeders/top-movies-data.csv"), "r");

        $firstline = true;
        while (($data = fgetcsv($csvFile, 0, ",")) !== FALSE) {
            if (!$firstline) {
                $new_movie = Movies::create([
                    // Data at current CSV
                    // Title,release_date,runtime,genre/s,categories_id,description,image,trailer,actors,director,writers
                    "title" => $data['0'],
                    "description" => $data['5'],
                    "release_date" => date($data['1']),
                    "image" => $data['6'],
                    "runtime" => $data['2'],
                    "status" => 1,
                    "trailer" => $data['7']
                ]);
                $new_movie->save();
                $categories_array = explode(',', $data['4']);

                foreach ($categories_array as $category) {
                    DB::table('categories_movies')->insert([
                        "movies_id" => $new_movie->id,
                        "categories_id" => $category,
                    ]);
                }
            }
            $firstline = false;
        }

        fclose($csvFile);

        Schema::enableForeignKeyConstraints();

    }
}
