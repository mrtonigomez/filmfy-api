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

        $csvFile = fopen(base_path("database/seeders/movies.csv"), "r");

        $firstline = true;
        while (($data = fgetcsv($csvFile, 0, ",")) !== FALSE) {
            if (!$firstline) {
                dump($data);
                Movies::create([
                    // Data at current CSV
                    // Title,release_date,runtime,genre/s,description,image,trailer,actors,director,writers
                    "title" => $data['0'],
                    "description" => $data['4'],
                    "release_date" => $data['1'],
                    "image" => $data['5'],
                    "runtime" => $data['2'],
                    "status" => 1,
                    "trailer" => $data['6']
                ]);
            }
            $firstline = false;
        }

        fclose($csvFile);

        Schema::enableForeignKeyConstraints();

    }
}
