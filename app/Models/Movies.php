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

        $categoriesObject = DB::table("categories_movies as cm")
            ->select("c.name")
            ->join("categories as c", "c.id", "=", "cm.categories_id")
            ->where("movies_id", "=", $id)
            ->get();

        foreach ($categoriesObject as $category) {
            array_push($categories, $category->name);
        }

        $actorsObject = DB::table("entities_movies as em")
            ->select("e.name")
            ->join("entities as e", "e.id", "=", "em.entities_id")
            ->where("movies_id", "=", $id)
            ->where("e.roles_id", "=", 1)
            ->get();

        foreach ($actorsObject as $actor) {
            array_push($actors, $actor->name);
        }

        $directorsObject = DB::table("entities_movies as em")
            ->select("e.name")
            ->join("entities as e", "e.id", "=", "em.entities_id")
            ->where("movies_id", "=", $id)
            ->where("e.roles_id", "=", 2)
            ->get();

        foreach ($directorsObject as $director) {
            array_push($directors, $director->name);
        }

        $writtersObject = DB::table("entities_movies as em")
            ->select("e.name")
            ->join("entities as e", "e.id", "=", "em.entities_id")
            ->where("movies_id", "=", $id)
            ->where("e.roles_id", "=", 3)
            ->get();

        foreach ($writtersObject as $writter) {
            array_push($writters, $writter->name);
        }

        $commentsObject = DB::table("comments")
            ->select("*")
            ->where("movies_id", "=", $id)
            ->get();

        foreach ($commentsObject as $comment) {
            array_push($comments, $comment);
        }

        $data = [
            "categories" => $categories,
            "actors" => $actors,
            "directors" => $directors,
            "writters" => $writters,
            "comments" => $comments
        ];

        return $data;

    }

    public function category() {
        return $this->belongsToMany(Categories::class);
    }

    public function entities() {
        return $this->belongsToMany(Entities::class);
    }

    public function list(){
        return $this->belongsToMany(Lists::class);
    }

    public function comment(){
        return $this->hasMany(Comments::class);
    }

    public function characters(){
        return $this->hasMany(Characters::class);
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
}
