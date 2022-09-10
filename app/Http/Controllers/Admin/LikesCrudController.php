<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LikesRequest;
use App\Models\Comments;
use App\Models\Likes;
use App\Models\Lists;
use App\Models\Movies;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;


class LikesCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;


    public function setup()
    {
        CRUD::setModel(\App\Models\Likes::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/likes');
        CRUD::setEntityNameStrings('likes', 'likes');

        Likes::saving(function ($item) {
            $rq = $this->crud->getRequest();

            if ($rq->movies !== null) {
                $movie = Movies::find($rq->movies);
                $item->likeable()->associate($movie);
            } else if ($rq->lists !== null) {
                $list = Lists::find($rq->lists);
                $item->likeable()->associate($list);
            }
        });
    }


    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('users_id');
        CRUD::column('likeable_type');
        CRUD::column('likeable_id');
        CRUD::column('created_at');
        CRUD::column('updated_at');


    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(LikesRequest::class);

        CRUD::field('users_id');
        $this->crud->addField([
            "name" => "movies",
            "type" => "select",
            "label" => "Movie",
            "attribute" => "title",
            'model' => "App\Models\Movies",
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            "name" => "lists",
            "type" => "select",
            "label" => "List",
            "attribute" => "title",
            'model' => "App\Models\Lists",
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

    }

    protected function setupUpdateOperation()
    {
        CRUD::setValidation(LikesRequest::class);

        CRUD::field('users_id');
        $item = Likes::find(\Route::current()->parameter('id'));
        if ($item->likeable_type === "App\Models\Movies") {
            $this->crud->addField([
                "name" => "movies",
                "type" => "select",
                "label" => "Movies",
                "attribute" => "title",
                'model' => "App\Models\Movies"
            ]);
        } else {
            $this->crud->addField([
                "name" => "lists",
                "type" => "select",
                "label" => "Lists",
                "attribute" => "title",
                'model' => "App\Models\Lists"
            ]);
        }
    }
}
