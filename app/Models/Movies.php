<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Movies extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'movies';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['title', 'description', 'release_date', "image", 'runtime',
        'trailer', 'status'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public static function returnExtraInformation($id) {

        $categories = [];
        $actors = [];
        $directors = [];
        $writters = [];
        $comments = [];
        $likes = 0;

        $categoriesObject = Movies::find($id)->category;
        foreach ($categoriesObject as $category) {
            array_push($categories, $category->name);
        }

        $actorsObject = Movies::find($id)->entities->where("roles_id", 1);

        foreach ($actorsObject as $actor) {
            array_push($actors, $actor->name);
        }

        $directorsObject = Movies::find($id)->entities->where("roles_id", 2);

        foreach ($directorsObject as $director) {
            array_push($directors, $director->name);
        }

        $writtersObject = Movies::find($id)->entities->where("roles_id", 3);

        foreach ($writtersObject as $writter) {
            array_push($writters, $writter->name);
        }

        $commentsObject = Movies::find($id)->comment;

        foreach ($commentsObject as $comment) {
            array_push($comments, $comment);
        }

        $likesObject = Movies::find($id)->likes;

        foreach ($likesObject as $like) {
            $likes++;
        }

        $data = [
            "categories" => $categories,
            "actors" => $actors,
            "directors" => $directors,
            "writters" => $writters,
            "comments" => $comments,
            "likes" => $likes
        ];

        return $data;

    }

    public function category() {
        return $this->belongsToMany(Categories::class);
    }

    public function entitiesActors() {
        return $this->belongsToMany(Entities::class);
    }

    public function entitiesDirectors() {
        return $this->belongsToMany(Entities::class);
    }

    public function entitiesWritters() {
        return $this->belongsToMany(Entities::class);
    }

    public function list(){
        return $this->belongsToMany(Lists::class);
    }

    public function comments(){
        return $this->morphMany("App\Models\Movies", "commentable");
    }

    public function likes() {
        return $this->hasMany(MoviesLikes::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setImageAttribute($value)
    {
        $attribute_name = "image";
        $disk = "public";
        $destination_path = "/movie_images";

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);

        // return $this->attributes[{$attribute_name}]; // uncomment if this is a translatable field
    }
}
