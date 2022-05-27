<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LikesRestController extends Controller
{
    public function likeMovie($movie_id, $user_id) {

        return DB::table('movies_likes')
            ->insert([
                'movies_id' => $movie_id,
                'users_id' => $user_id,
            ]);
    }

    public function unlikeMovie($movie_id, $user_id) {

        return DB::table('movie_likes')
            ->where('movies_id',$movie_id)
            ->where('users_id', $user_id)
            ->delete();
    }

    public function likeList($list_id, $user_id) {
        return DB::table('lists_likes')
            ->insert([
                'lists_id' => $list_id,
                'users_id' => $user_id,
            ]);
    }

    public function unlikeList($list_id, $user_id) {
        return DB::table('lists_likes')
            ->where('lists_id',$list_id)
            ->where('users_id', $user_id)
            ->delete();
    }
}
