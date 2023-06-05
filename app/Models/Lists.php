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

    public function comment(){
        return $this->morphMany(Comments::class, "commentable");
    }

    public function likes(){
        return $this->morphMany(Likes::class, "likeable");
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
