<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Services\MoviesService;
use App\Models\Entities;
use App\Models\Lists;
use App\Models\Movies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MoviesRestController extends Controller
{
    protected $_context;

    public function __construct(MoviesService $context)
    {
        $this->_context = $context;
    }

    public function index()
    {
        $movies = Movies::with(["comment", "entities", "category"])
            ->withCount("likes")
            ->get();

        $response = [];
        foreach ($movies as $movie) {
            $response[] = [
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

        return $response;
    }

    public function onlyAllMovies()
    {
        $movies = DB::table("movies")
            ->select("*")
            ->get();

        return $movies;
    }

    public function store(Request $request)
    {

    }

    public function show($id)
    {
        $movie = Movies::with(["comment", "entities", "category"])
            ->withCount("likes")
            ->find($id);

        $response = [
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
        return $response;
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    //Show all movies from an actor
    public function moviesActor($id)
    {
        $movies_actor = Entities::with(["movies"])->find($id);
        return [
            "name" => $movies_actor->name,
            "movies" => $movies_actor->movies

        ];
    }

    public function moviesLikes($id)
    {
        $movies_likes = Movies::withCount(["likes"])->find($id);
        return [
            "number_likes" => $movies_likes->likes_count
        ];
    }

    public function moviesStoreLikes(Request $request)
    {
        $data = [
            "movies_id" => $request->movies_id,
            "users_id" => $request->users_id
        ];

        DB::table("movies_likes")->insert($data);
    }

    public function bestMovies()
    {
        $movies_likes = DB::table("movies as m")
            ->select("m.id", "m.title", "m.description", "m.image", DB::raw("count(l.id) as likes"))
            ->join("likes as l", "l.likeable_id", "=", "m.id", "left")
            ->where("l.likeable_type", "=", "App\Models\Movies")
            ->orderBy("likes", "DESC")
            ->groupBy('m.id', "m.title", "m.description", "m.image")
            ->limit(15)
            ->get();


        return $movies_likes;

        /*$movies = Movies::with("likes")
            ->withCount("likes")
            ->orderBy("likes_count", "desc")
            ->limit(15)
            ->get();

        $response = [];
        foreach ($movies as $movie) {
            $response[] = [
                "id" => $movie->id,
                "title" => $movie->title,
                "description" => $movie->description,
                "image" => $movie->image,
                "likes" => $movie->likes_count,
            ];
        }
        return $response;*/
    }

    //Find movies name
    public function findMovies(Request $request)
    {
        $parameterToSearch = $request->selector;
        $parameterToFind = "%" . $request->parameter . "%";

        $movies = DB::table("movies")
            ->where("title", "like", $parameterToFind)->get();
        return $movies;
    }

    //Return movies related to a one category
    public function moviesWithCategory($category)
    {
        //Not working for where clause
        /*$category_filter = str_replace('-', ' ', $category);
        $category_id = DB::table("categories")
            ->where("name", "=", $category_filter)
            ->value("id");

        $movies_categories = DB::table("movies as m")
            ->select('m.id', "m.title", "m.description", "m.image", DB::raw("count(l.id) as likes"))
            ->join("categories_movies as c", "m.id", "=", "c.movies_id")
            ->join("likes as l", "l.likeable_id", "=", "m.id", "left")
            ->where("l.likeable_type", "=", "App\Models\Movies")
            ->where("c.categories_id", "=", $category_id)
            ->groupBy('m.id', "m.title", "m.description", "m.image")
            ->get();

        return $movies_categories;*/

        $category_filter = str_replace('-', ' ', $category);
        $category_id = DB::table("categories")
            ->where("name", "=", $category_filter)
            ->value("id");

        $movies = Movies::whereHas("category" ,function ($query) use ($category_id) {
                $query->where("categories_id", $category_id);
            })
            ->withCount("likes")
        ->get();

        $response = [];
        foreach ($movies as $movie) {
            $response[] = [
                "id" => $movie->id,
                "title" => $movie->title,
                "description" => $movie->description,
                "image" => $movie->image,
                "likes" => $movie->likes_count,
            ];
        }

        return $response;
    }

    //TODO: Refactor this method
    public function moviesOnMoreLists()
    {
        $reponse = [];
        $moviesSearch = DB::table("lists_movies as lm")
            ->select("lm.movies_id", DB::raw("count(lm.movies_id) as count_movies"))
            ->orderBy("count_movies", "DESC")
            ->groupBy('lm.movies_id')
            ->limit(10)
            ->get();

        foreach ($moviesSearch as $key => $movie) {
            $movieFind = Movies::withCount(["likes"])->find($movie->movies_id);
            $reponse[$key] = [
                "id" => $movieFind->id,
                "title" => $movieFind->title,
                "likes" => $movieFind->likes_count,
                "image" => $movieFind->image,
                "times_added" => $movie->count_movies
            ];
        }

        return $reponse;
    }

    public function moviesYear($year)
    {
        $response = [];
        for ($i = $year + 1; $i < ($year + 11); $i++) {
            $movies = DB::table("movies as m")
                ->select("m.title", "m.id", "m.description", "m.image", DB::raw("count(l.id) as likes"))
                ->where("release_date", "like", "%" . $i . "%")
                ->join("likes as ml", "l.movies_id", "=", "m.id", "left")
                ->groupBy('m.id', "m.title", "m.description", "m.image")
                ->get();

            foreach ($movies as $movie) {
                $response[] = $movie;
            }
        }

        return $response;
    }

    public function recentMovies()
    {
        $moviesAll = DB::table("movies as m")
            ->select("m.title", "m.id", "m.description", "m.image", DB::raw("count(l.id) as likes"))
            ->join("likes as ml", "l.movies_id", "=", "m.id", "left")
            ->orderBy("release_date", "DESC")
            ->groupBy('m.id', "m.title", "m.description", "m.image")
            ->limit(15)
            ->get();

        return $moviesAll;
    }

    public function upcommingMovies()
    {
        $today = date("Y-m-d");

        $moviesAll = DB::table("movies as m")
            ->select("m.title", "m.id", "m.release_date", "m.description", "m.image", DB::raw("count(l.id) as likes"))
            ->join("likes l", "l.movies_id", "=", "m.id", "left")
            ->orderBy("release_date", "ASC")
            ->groupBy('m.id', "m.title", "m.description", "m.image", "m.release_date")
            ->where("m.release_date", ">", $today)
            ->limit(15)
            ->get();

        return $moviesAll;
    }

    public function userHadLikeMovie(Request $request)
    {

        $exist = DB::table("movies_likes")
            ->select("*")
            ->where("movies_id", $request->movie)
            ->where("users_id", $request->user)
            ->count();


        if ($exist) {
            return $response = [
                "status" => 1,
            ];
        } else {
            return $response = [
                "status" => 0,
            ];
        }
    }
}
