<?php

namespace App\Http\Controllers;

use App\Models\Movies;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function testing() {

        $movies = Movies::with(["comment", "entities", "category"])
            ->withCount("likes")
            ->get();

        $response = [];
        foreach ($movies as $key => $movie) {
            $response[$key] = [
                "id" => $movie->id,
                "title" => $movie->title,
                "description" => $movie->description,
                "release_date" => $movie->release_date,
                "image" => $movie->image,
                "runtime" => $movie->runtime,
                "status" => $movie->status,
                "trailer" => $movie->trailer,
                "categories" => $movie->category->pluck("name"),
                "actors" => $movie->entities->where("roles_id", 1)->pluck("name"),
                "directors" => $movie->entities->where("roles_id", 2)->pluck("name"),
                "writters" => $movie->entities->where("roles_id", 3)->pluck("name"),
                "comments" => $movie->comment,
                "likes" => $movie->likes_count,
            ];
        }
        return view("testing", compact("response"));
    }
}
