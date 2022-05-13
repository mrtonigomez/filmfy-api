<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
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
        $movies = Movies::all();
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
        $movies_actor = DB::table("movies as m")
            ->select("m.id", "m.title", "m.description", "m.release_date", "m.runtime", "m.status", "m.trailer", "m.image")
            ->join("entities_movies as e", "m.id", "=", "e.movies_id")
            ->join("entities as en", "e.entities_id", "=", "en.id")
            ->where("en.roles_id", "=", 1)
            ->where("e.entities_id", "=", $id)
            ->get();
        return $movies_actor;
    }

    //Find movies name
    public function findMovies(Request $request)
    {
        $parameterToFind = "%" . $request->category . "%";
        $movies = DB::table("movies")
            ->where("title", "like", $parameterToFind)->get();
        return $movies;
    }

    //Return movies related to a one category
    public function moviesWithCategory(Request $request)
    {
        $category_id = DB::table("categories")
            ->where("name", "=", $request->category)
            ->value("id");

        $movies_categories = DB::table("movies as m")
            ->select("m.id", "m.title", "m.description", "m.release_date", "m.runtime", "m.status", "m.trailer", "m.image")
            ->join("categories_movies as c", "m.id", "=", "c.movies_id")
            ->where("c.categories_id", "=", $category_id)
            ->get();
        return $movies_categories;
    }

    public function recentMovies()
    {

    }
}
