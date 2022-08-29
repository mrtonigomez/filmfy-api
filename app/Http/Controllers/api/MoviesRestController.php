<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Services\MoviesService;
use App\Models\Entities;
use App\Models\Movies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Info(title="API Filmfy", version="1.0")
 *
 */
class MoviesRestController extends Controller
{
    protected $_context;

    public function __construct(MoviesService $context)
    {
        $this->_context = $context;
    }


    /**
     * @OA\Get(
     *     path="/api/movies",
     *     tags={"Movies"},
     *     summary="Mostrar peliculas",
     *     @OA\Response(
     *         response=200,
     *         description="Mostrar todas las peliculas."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * )
     */
    public function index()
    {
        $movies = Movies::with(["comment", "entities", "category"])
            ->withCount("likes")
            ->get();

        $response = [];
        foreach ($movies as $key => $movie) {
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

    /**
     * @OA\Get(
     *      path="/api/movies/{id}",
     *      summary="Mostrar una película por ID",
     *     tags={"Movies"},
     *      @OA\Parameter(
     *          name="id",
     *          description="Movie id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
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
            ->select("m.title", "m.id", "m.description", "m.image", DB::raw("count(ml.id) as likes"))
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
            ->select('m.id', "m.title", "m.description", "m.image", DB::raw("count(ml.id) as likes"))
            ->join("categories_movies as c", "m.id", "=", "c.movies_id")
            ->join("movies_likes as ml", "ml.movies_id", "=", "m.id", "left")
            ->where("c.categories_id", "=", $category_id)
            ->groupBy('m.id', "m.title", "m.description", "m.image")
            ->get();
        return $movies_categories;
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
                ->select("m.title", "m.id", "m.description", "m.image", DB::raw("count(ml.id) as likes"))
                ->where("release_date", "like", "%" . $i . "%")
                ->join("movies_likes as ml", "ml.movies_id", "=", "m.id", "left")
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
            ->select("m.title", "m.id", "m.description", "m.image", DB::raw("count(ml.id) as likes"))
            ->join("movies_likes as ml", "ml.movies_id", "=", "m.id", "left")
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
            ->select("m.title", "m.id", "m.release_date", "m.description", "m.image", DB::raw("count(ml.id) as likes"))
            ->join("movies_likes as ml", "ml.movies_id", "=", "m.id", "left")
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
