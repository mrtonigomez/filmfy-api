<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lists = [
            [
                'users_id' => 1,
                'title' => 'Películas vistas en 2022',
                'description' => 'Películas que he visto este año',
                'is_private' => 0,
                'status' => 1,
            ],
            [
                'users_id' => 1,
                'title' => 'Películas de Star Wars',
                'description' => 'Aquí están todas las pelis de StarWards que he encontrado en Filmfy',
                'is_private' => 0,
                'status' => 1,
            ],
            [
                'users_id' => 1,
                'title' => 'Ganadoras Óscars',
                'description' => 'Creo que estas películas han ganado un premio bueno de estos aunque es altamente probable que me equivoque',
                'is_private' => 0,
                'status' => 1,
            ],
            [
                'users_id' => 1,
                'title' => 'Películas con las que lloro',
                'description' => 'Estas pelis me han emocionado mucho y no querría olvidarlas nunca',
                'is_private' => 0,
                'status' => 0,
            ],
            [
                'users_id' => 2,
                'title' => 'Películas en las que he salido como extra',
                'description' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. ',
                'is_private' => 0,
                'status' => 1,
            ],
            [
                'users_id' => 2,
                'title' => 'Películas vistas en 2022',
                'description' => 'Estas son mis películas, las de Toni Gómez',
                'is_private' => 0,
                'status' => 1,
            ],
            [
                'users_id' => 2,
                'title' => 'Películas míticas',
                'description' => 'Si hay alguien que no haya visto estas películas ya tiene deberes que hacer',
                'is_private' => 0,
                'status' => 1,
            ],
            [
                'users_id' => 3,
                'title' => 'Películas de test',
                'description' => 'Pues esto es una historia que me acabo de enviar',
                'is_private' => 1,
                'status' => 1,
            ],
            [
                'users_id' => 3,
                'title' => 'Películas random',
                'description' => 'He escogido muchas películas para tener una lista en Filmfy',
                'is_private' => 0,
                'status' => 1,
            ],
        ];

        DB::table('lists')->insert($lists);


        $movies_lists = [
            [ 'lists_id' => 1, 'movies_id' => 1,],
            [ 'lists_id' => 1, 'movies_id' => 87,],
            [ 'lists_id' => 1, 'movies_id' => 40,],
            [ 'lists_id' => 1, 'movies_id' => 29,],
            [ 'lists_id' => 1, 'movies_id' => 11,],
            [ 'lists_id' => 1, 'movies_id' => 167,],
            [ 'lists_id' => 1, 'movies_id' => 269,],
            [ 'lists_id' => 1, 'movies_id' => 102,],
            [ 'lists_id' => 1, 'movies_id' => 83,],
            [ 'lists_id' => 1, 'movies_id' => 44,],

            [ 'lists_id' => 2, 'movies_id' => 90,],
            [ 'lists_id' => 2, 'movies_id' => 151,],
            [ 'lists_id' => 2, 'movies_id' => 155,],
            [ 'lists_id' => 2, 'movies_id' => 197,],

            [ 'lists_id' => 3, 'movies_id' => 10,],
            [ 'lists_id' => 3, 'movies_id' => 20,],
            [ 'lists_id' => 3, 'movies_id' => 30,],
            [ 'lists_id' => 3, 'movies_id' => 40,],
            [ 'lists_id' => 3, 'movies_id' => 50,],
            [ 'lists_id' => 3, 'movies_id' => 60,],
            [ 'lists_id' => 3, 'movies_id' => 70,],
            [ 'lists_id' => 3, 'movies_id' => 80,],

            [ 'lists_id' => 4, 'movies_id' => 250,],
            [ 'lists_id' => 4, 'movies_id' => 251,],
            [ 'lists_id' => 4, 'movies_id' => 255,],
            [ 'lists_id' => 4, 'movies_id' => 97,],
            [ 'lists_id' => 4, 'movies_id' => 53,],

            [ 'lists_id' => 5, 'movies_id' => 37,],
            [ 'lists_id' => 5, 'movies_id' => 22,],
            [ 'lists_id' => 5, 'movies_id' => 133,],
            [ 'lists_id' => 5, 'movies_id' => 38,],
            [ 'lists_id' => 5, 'movies_id' => 202,],
            [ 'lists_id' => 5, 'movies_id' => 223,],
            [ 'lists_id' => 5, 'movies_id' => 225,],

            [ 'lists_id' => 6, 'movies_id' => 25,],
            [ 'lists_id' => 6, 'movies_id' => 277,],
            [ 'lists_id' => 6, 'movies_id' => 177,],
            [ 'lists_id' => 6, 'movies_id' => 77,],
            [ 'lists_id' => 6, 'movies_id' => 7,],

            [ 'lists_id' => 7, 'movies_id' => 19,],
            [ 'lists_id' => 7, 'movies_id' => 48,],

            [ 'lists_id' => 8, 'movies_id' => 222,],
            [ 'lists_id' => 8, 'movies_id' => 111,],
            [ 'lists_id' => 8, 'movies_id' => 4,],

            [ 'lists_id' => 9, 'movies_id' => 255,],
            [ 'lists_id' => 9, 'movies_id' => 257,],
            [ 'lists_id' => 9, 'movies_id' => 1,],
            [ 'lists_id' => 9, 'movies_id' => 3,],
            [ 'lists_id' => 9, 'movies_id' => 76,],

        ];

        DB::table('lists_movies')->insert($movies_lists);

    }
}
