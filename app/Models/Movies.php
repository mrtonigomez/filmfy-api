<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

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


    public function category()
    {
        return $this->belongsToMany(Categories::class);
    }

    public function entities()
    {
        return $this->belongsToMany(Entities::class);
    }

    //TODO: Changes required on DB to refactor this
    public function entitiesActors()
    {
        return $this->belongsToMany(Entities::class);
    }

    public function entitiesDirectors()
    {
        return $this->belongsToMany(Entities::class);
    }

    public function entitiesWritters()
    {
        return $this->belongsToMany(Entities::class);
    }

    public function list()
    {
        return $this->belongsToMany(Lists::class);
    }

    public function comment()
    {
        return $this->morphMany(Comments::class, "commentable");
    }

    public function likes()
    {
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

    public function setImageAttribute($value)
    {
        $attribute_name = "image";
        $disk = "public";
        $destination_path = "/movie_images";

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);

        // return $this->attributes[{$attribute_name}]; // uncomment if this is a translatable field
    }
}
