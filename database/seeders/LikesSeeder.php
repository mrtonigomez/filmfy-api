<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LikesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $movies_likes = [
            ['movies_id' => 1, 'users_id' => 1],
            ['movies_id' => 25, 'users_id' => 1],
            ['movies_id' => 9, 'users_id' => 1],
            ['movies_id' => 260, 'users_id' => 1],
            ['movies_id' => 175, 'users_id' => 1],
            ['movies_id' => 124, 'users_id' => 1],
            ['movies_id' => 11, 'users_id' => 1],
            ['movies_id' => 79, 'users_id' => 1],
            ['movies_id' => 99, 'users_id' => 1],
            ['movies_id' => 260, 'users_id' => 2],
            ['movies_id' => 99, 'users_id' => 2],
            ['movies_id' => 1, 'users_id' => 2],
            ['movies_id' => 2, 'users_id' => 2],
            ['movies_id' => 3, 'users_id' => 2],
            ['movies_id' => 3, 'users_id' => 3],
            ['movies_id' => 25, 'users_id' => 3],
            ['movies_id' => 44, 'users_id' => 3],
            ['movies_id' => 11, 'users_id' => 3],
            ['movies_id' => 177, 'users_id' => 3],
        ];

        $lists_likes = [
            ['lists_id' => 7, 'users_id' => 1],
            ['lists_id' => 7, 'users_id' => 2],
            ['lists_id' => 7, 'users_id' => 3],
            ['lists_id' => 1, 'users_id' => 2],
            ['lists_id' => 9, 'users_id' => 2],
            ['lists_id' => 9, 'users_id' => 3],
            ['lists_id' => 5, 'users_id' => 1],
            ['lists_id' => 5, 'users_id' => 2],
            ['lists_id' => 5, 'users_id' => 3],
        ];

        DB::table('movies_likes')->insert($movies_likes);
        DB::table('lists_likes')->insert($lists_likes);

    }
}
