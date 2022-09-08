<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Lists extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'lists';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ["users_id", 'title', 'description', 'is_private', 'status',
        'likes'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function moviesInformation($id) {
        $movies_info = [];
        $movies_count = 0;

        $moviesObject = Lists::find($id)->movies;
        foreach ($moviesObject as $movie) {
            array_push($movies_info, $movie);
            $movies_count++;

            $m_categories = Movies::returnExtraInformation($movie->id);
            $movie["categories"] = $m_categories["categories"];
            }

        return [$movies_info, $movies_count];
    }

    public static function userInformation($id) {
        $user_data = [];
        $user = Lists::find($id)->users;
        $user_data["name"] = $user["name"];
        $user_data["profile_image"] = $user["profile_image"];

        return $user_data;
    }

    public static function listLikes($id) {

         return DB::table("lists as l")
             ->select(DB::raw('COUNT(ll.id) as count') )
             ->leftJoin('lists_likes as ll', 'll.lists_id', '=', 'l.id')
             ->where('l.id', '=', $id)
             ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function users(){
        return $this->belongsTo(User::class);
    }

    public function movies(){
        return $this->belongsToMany(Movies::class);
    }

    public function likes() {
        return $this->hasMany(ListsLikes::class);
    }

    public function comment(){
        return $this->morphMany(Comments::class, "commentable");
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
