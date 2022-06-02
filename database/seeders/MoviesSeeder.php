<?php

namespace Database\Seeders;

use App\Models\Entities;
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

        $sql_data = [
        file_get_contents(base_path('database/resources/movies.sql')),
        file_get_contents(base_path('database/resources/categories_movies.sql')),
        file_get_contents(base_path('database/resources/entities.sql')),
        file_get_contents(base_path('database/resources/entities_movies.sql')),
        ];

        foreach ($sql_data as $sql){
            DB::unprepared($sql);
        }

//        /** Function to store entities, roles and associated movies from CSV file
//         * @param $entities_array
//         * @param $role
//         * @param $movie_id
//         * @return void
//         */
//        function entities_seed($entities_array, $role, $movie_id) {
//            foreach ($entities_array as $entity) {
//                if ($entity !== '') {
//                    $result = Entities::where('name', '=', $entity,)->where('roles_id', '=', $role)->get();
//                    if ($result->isEmpty()) {
//                        $new_entity = Entities::create([
//                            'name' => $entity,
//                            'roles_id' => $role,
//                        ]);
//                        $new_entity->save();
//
//                        DB::table('entities_movies')->insert([
//                            "entities_id" => $new_entity->id,
//                            "movies_id" => $movie_id,
//                        ]);
//                    }else {
//                        $result->toArray();
//                        DB::table('entities_movies')->insert([
//                            "entities_id" => $result[0]["id"],
//                            "movies_id" => $movie_id,
//                        ]);
//                    }
//                }
//            }
//        }
//
//        /** Opening CSV data
//         * Title, release_date, runtime, genre/s, categories_id, description, image, trailer, actors, director, writers
//         */
//        $csvFile = fopen(base_path("database/resources/movies-june22.csv"), "r");
//
//        // Ignoring first line and creating all movies first
//        $firstline = true;
//        while (($data = fgetcsv($csvFile, 0, ",")) !== FALSE) {
//            if (!$firstline) {
//                $new_movie = Movies::create([
//                    "title" => $data['0'],
//                    "description" => $data['5'],
//                    "release_date" => date($data['1']),
//                    "image" => $data['6'],
//                    "runtime" => $data['2'],
//                    "status" => 1,
//                    "trailer" => $data['7']
//                ]);
//                $new_movie->save(); // Saving created item
//
//                // After creating movies, checking categories at the CSV
//                $categories_array = explode(',', $data['4']); // Convert to array category id's
//
//                // Loop so seed categories_movies table with id of item created and categories_id at the array
//                foreach ($categories_array as $category) {
//                    DB::table('categories_movies')->insert([
//                        "movies_id" => $new_movie->id,
//                        "categories_id" => $category,
//                    ]);
//                }
//
//                // Reading data of entities of each movie (actors, directors, writers)
//                $actors_array = explode(',', $data['8']);
//                $directors_array = explode(',', $data['9']);
//                $writers_array = explode(',', $data['10']);
//
//                // Using function to seed passing role and added movie "id"
//                entities_seed($actors_array,1, $new_movie->id);
//                entities_seed($directors_array,2, $new_movie->id);
//                entities_seed($writers_array,3, $new_movie->id);
//
//            }
//            $firstline = false;
//        }
//        fclose($csvFile);

        Schema::enableForeignKeyConstraints();
    }
}
