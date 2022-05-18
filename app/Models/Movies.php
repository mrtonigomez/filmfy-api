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
