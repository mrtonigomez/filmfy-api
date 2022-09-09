<?php

namespace Database\Seeders;

use App\Models\Likes;
use App\Models\Lists;
use App\Models\Movies;
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
        $likes = [];

        $fiberMovies = new \Fiber(function () {
            return Movies::all();
        });

        $fiberMovies->start();

        for ($i = 0; $i < 200; $i++) {
            $likes[] = ["users_id" => rand(1, 3)];
        }

        DB::table('likes')->insert($likes);

        $lists = Lists::all();

        $fiberLikes = new \Fiber(function () {
            return Likes::all();
        });

        $fiberLikes->start();
        $movies = $fiberMovies->getReturn();
        $likes = $fiberLikes->getReturn();

        $i = 0;
        foreach ($likes as $like) {
            ($i % 2 === 0)
                ? $like->likeable()->associate(Movies::find(rand(1, count($movies))))->save()
                : $like->likeable()->associate(Lists::find(rand(1, count($lists))))->save();
            $i++;
        }
    }
}
