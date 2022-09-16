<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Services\CategoriesService;
use App\Http\Services\MoviesService;
use App\Models\Entities;
use App\Models\Movies;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MoviesRestController extends Controller
{
    protected MoviesService $movies_service;
    protected CategoriesService $categoriesService;

    public function __construct(MoviesService $movies_service, CategoriesService $categoriesService)
    {
        $this->movies_service = $movies_service;
        $this->categoriesService = $categoriesService;
    }

    public function index()
    {
        return $this->movies_service->getAllMoviesData();
    }

    public function onlyAllMovies()
    {
        return $this->movies_service->onlyAllMovies();
    }

    public function store(Request $request)
    {

    }

    public function show($id)
    {
        $movie = $this->findOrFail(Movies::class, $id);

        if (!$movie) {
            return response()->json("Pelicula no encontrada", 404);
        }

        return $this->movies_service->findMoviesById($id);
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
        $actor = $this->findOrFail(Entities::class, $id);

        if (!$actor) {
            return response()->json("Actor no encontrada", 404);
        }

        return $this->movies_service->moviesActor($id);
    }

    public function moviesLikes($id)
    {
        $movie = $this->findOrFail(Movies::class, $id);

        if (!$movie) {
            return response()->json("Pelicula no encontrada", 404);
        }

        return $this->movies_service->findLikesMovieById($id);
    }

    public function moviesStoreLikes(Request $request)
    {
        $user = $this->findOrFail(User::class, $request->users_id);
        if (!$user) {
            return response()->json("Usuario no encontrado", 400);
        }

        $movie = $this->findOrFail(Movies::class, $request->movies_id);
        if (!$movie) {
            return response()->json("Pelicula no encontrada", 404);
        }

        $record = $this->movies_service->checkUserLikedMovie($request);
        if (!$record->isEmpty()) {
            return response()->json("El usuario ya ha dado like a esta pelicula", 400);
        }

        return $this->movies_service->storeLikesInMovies($request);
    }

    public function bestMovies()
    {
        return $this->movies_service->bestMovies();
    }

    //Find movies name
    public function findMovies(Request $request)
    {
        return $this->movies_service->findMoviesByParam($request);
    }

    //Return movies related to a one category
    public function moviesWithCategory($category)
    {
        $category_id = $this->categoriesService->backCategoryId($category);

        if ($category_id === null) {
            return response()->json("Categoria no encontrada", 404);
        }

        return $this->movies_service->moviesWithCategory($category_id);
    }

    public function moviesOnMoreLists()
    {
        return $this->movies_service->moviesOnMoreLists();
    }

    public function moviesYear($year)
    {
        return $this->movies_service->moviesYear($year);
    }

    public function recentMovies()
    {
        $this->movies_service->recentMovies();
    }

    public function upcommingMovies()
    {
        $this->movies_service->upcommingMovies();
    }

    public function userHadLikeMovie(Request $request)
    {
        $user = $this->findOrFail(User::class, $request->users_id);
        if (!$user) {
            return response()->json("Usuario no encontrado", 400);
        }

        $movie = $this->findOrFail(Movies::class, $request->movies_id);
        if (!$movie) {
            return response()->json("Pelicula no encontrada", 404);
        }

        $record = $this->movies_service->checkUserLikedMovie($request);
        if ($record->isEmpty()) {
            return response()->json(["status" => 0], 400);
        }

        return response()->json(["status" => 1]);
    }
}
