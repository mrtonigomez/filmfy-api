<?php

use App\Http\Controllers\api\CommentsRestController;
use App\Http\Controllers\api\ListsRestController;
use App\Http\Controllers\api\MoviesRestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource("movies",MoviesRestController::class);
Route::get("movies-actors/{id}", [MoviesRestController::class, "moviesActor"]);

Route::get("movies-categories/{category}", [MoviesRestController::class, "moviesWithCategory"]);
Route::get("movies-year/{year}", [MoviesRestController::class, "moviesYear"]);
Route::post("find-movies", [MoviesRestController::class, "findMovies"]);
Route::get("recent-movies", [MoviesRestController::class, "recentMovies"]);

Route::apiResource("lists", ListsRestController::class);
Route::get("movies-from-list/{id}", [ListsRestController::class, "moviesFromList"]);
Route::get("user-lists/{id}", [ListsRestController::class, "userLists"]);
Route::post("add-movie-to-list", [ListsRestController::class, "addMoviesToList"]);
Route::get("lists-recent", [ListsRestController::class, "recentLists"]);

Route::apiResource("categories", \App\Http\Controllers\api\CategoriesRestController::class);

Route::apiResource("comments",CommentsRestController::class);
Route::get("comments-movie/{id}", [CommentsRestController::class, "movieComments"]);
Route::get("comments-recent", [CommentsRestController::class, "recentComments"]);
Route::post("comments-store/{movie_id}", [CommentsRestController::class, "store"]);
