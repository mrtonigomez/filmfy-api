<?php

use App\Http\Controllers\api\CommentsRestController;
use App\Http\Controllers\api\ListsRestController;
use App\Http\Controllers\api\MoviesRestController;
use App\Http\Controllers\api\UsersRestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\AuthController;


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

Route::apiResource("users", UsersRestController::class);

Route::prefix('v1')->group(function () {
    //Prefijo V1, todo lo que este dentro de este grupo se accedera escribiendo v1 en el navegador, es decir /api/v1/*
    Route::post('login', [AuthController::class, 'authenticate']);
    Route::post('register', [AuthController::class, 'register']);
    Route::group(['middleware' => ['jwt.verify']], function() {
        //Todo lo que este dentro de este grupo requiere verificación de usuario.
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('get-user', [AuthController::class, 'getUser']);
	    Route::post("movies-likes", [MoviesRestController::class, "moviesStoreLikes"]);
        Route::post("add-movie-to-list", [ListsRestController::class, "addMoviesToList"]);
        Route::post("comments-store/{movie_id}", [CommentsRestController::class, "store"]);
        Route::put('edit-user/{user_id}', [CommentsRestController::class, "update"]);
    });
});


Route::apiResource("movies",MoviesRestController::class);
Route::get("movies-actors/{id}", [MoviesRestController::class, "moviesActor"]);
Route::get("movies-likes/{id}", [MoviesRestController::class, "moviesLikes"]);
Route::get("movies-categories/{category}", [MoviesRestController::class, "moviesWithCategory"]);
Route::get("movies-year/{year}", [MoviesRestController::class, "moviesYear"]);
Route::post("find-movies", [MoviesRestController::class, "findMovies"]);
Route::get("recent-movies", [MoviesRestController::class, "recentMovies"]);
Route::get("best-movies", [MoviesRestController::class, "bestMovies"]);
Route::get("movies-on-more-lists", [MoviesRestController::class, "moviesOnMoreLists"]);
Route::get("upcomming-movies", [MoviesRestController::class, "upcommingMovies"]);
Route::post("user-had-like-movie", [MoviesRestController::class, "userHadLikeMovie"]);

Route::apiResource("lists", ListsRestController::class);
Route::get("movies-from-list/{id}", [ListsRestController::class, "moviesFromList"]);
Route::get("user-lists/{id}", [ListsRestController::class, "userLists"]);
Route::get("lists-recent", [ListsRestController::class, "recentLists"]);
Route::get("lists-most-liked", [ListsRestController::class, "mostLikedLists"]);
Route::post("create-list", [ListsRestController::class, "createList"]);
Route::put("edit-list", [ListsRestController::class, "updateList"]);

Route::apiResource("categories", \App\Http\Controllers\api\CategoriesRestController::class);

Route::apiResource("comments",CommentsRestController::class);
Route::get("comments-movie/{id}", [CommentsRestController::class, "movieComments"]);
Route::get("comments-recent", [CommentsRestController::class, "recentComments"]);
Route::get("comment-like/{comment_id}", [CommentsRestController::class, "commentLike"]);
Route::get("comments-user/{user_id}", [CommentsRestController::class, "userComments"]);
Route::post("user-had-comment", [CommentsRestController::class, "userHadComment"]);

