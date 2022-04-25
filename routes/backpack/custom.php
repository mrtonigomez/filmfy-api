<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('users', 'UsersCrudController');
    Route::crud('items', 'ItemsCrudController');
    Route::crud('categories', 'CategoriesCrudController');
    Route::crud('countries', 'CountriesCrudController');
    Route::crud('actors', 'ActorsCrudController');
    Route::crud('productors', 'ProductorsCrudController');
    Route::crud('movies', 'MoviesCrudController');
    Route::crud('documentaries', 'DocumentariesCrudController');
    Route::crud('series', 'SeriesCrudController');
    Route::crud('seasons', 'SeasonsCrudController');
    Route::crud('chapters', 'ChaptersCrudController');
    Route::crud('comments', 'CommentsCrudController');
    Route::crud('ratings', 'RatingsCrudController');
    Route::crud('lists', 'ListsCrudController');
}); // this should be the absolute last line of this file