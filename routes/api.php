<?php

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
Route::post("movies-categories", [MoviesRestController::class, "moviesWithCategory"]);
Route::post("find-movies", [MoviesRestController::class, "findMovies"]);
