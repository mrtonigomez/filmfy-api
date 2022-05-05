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
        DB::table('movies')->truncate();
        Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("database/seeders/movies.csv"), "r");
        $firstline = true;

        while ($data = fgetcsv($csvFile, 0, ",") !== FALSE) {
            if (!$firstline) {
                Movies::create([
                    // Data at current CSV
                    // Title,release_date,runtime,genre/s,description,image,trailer,actors,director,writers
                ]);
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}
