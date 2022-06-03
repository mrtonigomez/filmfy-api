<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Entities;
use App\Models\Movies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MoviesRestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $moviesAll = Movies::all();
        $movies = [];

        foreach ($moviesAll->toArray() as $key => $movie) {

            $data = Movies::returnExtraInformation($movie["id"]);

            $movie["categories"] = $data["categories"];
            $movie["actors"] = $data["actors"];
            $movie["directors"] = $data["directors"];
            $movie["writters"] = $data["writters"];
            $movie["comments"] = $data["comments"];
            $movie["likes"] = $data["likes"];

            array_push($movies, $movie);
        }

        return $movies;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $movie = Movies::find($id);
        $data = Movies::returnExtraInformation($id);


        $movie["categories"] = $data["categories"];
        $movie["actors"] = $data["actors"];
        $movie["directors"] = $data["directors"];
        $movie["writters"] = $data["writters"];
        $movie["comments"] = $data["comments"];
        $movie["likes"] = $data["likes"];

        return $movie;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    //Show all movies from an actor
    public function moviesActor($id)
    {
        $movies_actor = Entities::find($id)->movies;
        return $movies_actor;
    }

    public function moviesLikes($id)
    {
        $movies_likes = Movies::find($id)->likes;
        return count($movies_likes);
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
            ->select("m.title", "m.id", "m.description", "m.image",  DB::raw("count(ml.id) as likes"))
            ->join("movies_likes as ml", "ml.movies_id", "=", "m.id", "left")
            ->orderBy("likes", "DESC")
            ->groupBy('m.id', "m.title", "m.description", "m.image")
            ->limit(15)
            ->get();

        return $movies_likes;
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
        $category_filter = str_replace('-', ' ', $category);
        $category_id = DB::table("categories")
            ->where("name", "=", $category_filter)
            ->value("id");

        $movies_categories = DB::table("movies as m")
            ->select('m.id', "m.title", "m.description", "m.image",  DB::raw("count(ml.id) as likes"))
            ->join("categories_movies as c", "m.id", "=", "c.movies_id")
            ->join("movies_likes as ml", "ml.movies_id", "=", "m.id", "left")
            ->where("c.categories_id", "=", $category_id)
            ->groupBy('m.id', "m.title", "m.description", "m.image")
            ->get();
        return $movies_categories;
    }

    public function moviesOnMoreLists() {
        $movies = [];
        $moviesSearch = DB::table("lists_movies as lm")
            ->select("lm.movies_id", DB::raw("count(lm.movies_id) as count_movies"))
            ->orderBy("count_movies", "DESC")
            ->groupBy('lm.movies_id')
            ->limit(10)
            ->get();

        foreach ($moviesSearch->toArray() as $movie) {
            $movieFind = Movies::find($movie->movies_id);
            $movieFinal = [
                "id" => $movieFind->id,
                "title" => $movieFind->title,
                "likes" => count($movieFind->likes),
                "image" => $movieFind->image,
                "times_added" => $movie->count_movies
            ];
            array_push($movies, $movieFinal);
        }

        return $movies;
    }

    public function moviesYear($year)
    {
        $allMovie = [];
        for ($i = $year + 1; $i < ($year + 11); $i++) {
            $movies = DB::table("movies as m")
                ->select("m.title", "m.id", "m.description", "m.image",  DB::raw("count(ml.id) as likes"))
                ->where("release_date", "like", "%" . $i . "%")
                ->join("movies_likes as ml", "ml.movies_id", "=", "m.id", "left")
                ->groupBy('m.id', "m.title", "m.description", "m.image")
                ->get();


            foreach ($movies as $movie) {
                array_push($allMovie, $movie);
            }
        }

        return $allMovie;
    }

    public function recentMovies()
    {
        $moviesAll = DB::table("movies as m")
            ->select("m.title", "m.id", "m.description", "m.image",  DB::raw("count(ml.id) as likes"))
            ->join("movies_likes as ml", "ml.movies_id", "=", "m.id", "left")
            ->orderBy("release_date", "DESC")
            ->groupBy('m.id', "m.title", "m.description", "m.image")
            ->limit(15)
            ->get();

        return $moviesAll;
    }

    public function userHadLikeMovie(Request $request){

        $exist = DB::table("movies_likes")
            ->select("*")
            ->where("movies_id", $request->movie)
            ->where("users_id", $request->user)
            ->count();


        if ($exist) {
            return $response = [
                "status" => 1,
            ];
        }else {
            return $response = [
                "status" => 0,
            ];
        }
    }
}
