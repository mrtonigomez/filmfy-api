<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CommentsRequest;
use App\Models\Comments;
use App\Models\Lists;
use App\Models\Movies;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CommentsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CommentsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Comments::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/comments');
        CRUD::setEntityNameStrings('comments', 'comments');

        Comments::saving(function ($item) {
            $rq = $this->crud->getRequest();

            if ($rq->movies !== null) {
                $movie = Movies::find($rq->movies);
                $item->commentable()->associate($movie);
            } else if ($rq->lists !== null) {
                $list = Lists::find($rq->lists);
                $item->commentable()->associate($list);
            }
        });
    }

    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('users_id');
        CRUD::column("commentable_type");
        CRUD::column("commentable_id");
        CRUD::column('title');
        CRUD::column('body');
        CRUD::column('status');
        CRUD::column('likes');
        $this->crud->addColumn([
            "name" => "rating",
            "label" => "Rating",
        ]);


    }

    protected function setupShowOperation()
    {
        CRUD::column('id');
        CRUD::column('users_id');
        CRUD::column('title')->limit(10000);;
        CRUD::column('body')->limit(10000);;
        CRUD::column('status');
        CRUD::column('rating');
        CRUD::column('likes');
        CRUD::column('created_at');
        CRUD::column('updated_at');
    }


    protected function setupCreateOperation()
    {
        CRUD::setValidation(CommentsRequest::class);

        $this->crud->addField([
            'name' => 'users',
            'label' => 'Users',
            'type' => 'select',

            'model' => "App\Models\User", // related model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'options' => (function ($query) {
                return $query->orderBy('name', 'ASC')->get();
            })

        ]);

        CRUD::field('title')->limit(10000);;
        $this->crud->addField([
            'name' => 'body',
            'label' => 'Body',
            'type' => 'text',

        ])->limit(10000);;

        $this->crud->addField([
            "name" => "movies",
            "type" => "select",
            "label" => "Commentable movies",
            "attribute" => "title",
            'model' => "App\Models\Movies",
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            "name" => "lists",
            "type" => "select",
            "label" => "Commentable lists",
            "attribute" => "title",
            'model' => "App\Models\Lists",
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        CRUD::field('status');
        CRUD::field('likes');
        CRUD::field('rating')->type("text")->name("rating");

    }


    protected function setupUpdateOperation()
    {
        CRUD::setValidation(CommentsRequest::class);

        $this->crud->addField([
            'name' => 'users',
            'label' => 'Users',
            'type' => 'select',

            'model' => "App\Models\User", // related model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'options' => (function ($query) {
                return $query->orderBy('name', 'ASC')->get();
            })

        ]);

        CRUD::field('title')->limit(10000);;
        $this->crud->addField([
            'name' => 'body',
            'label' => 'Body',
            'type' => 'text',

        ])->limit(10000);;

        $item = Comments::find(\Route::current()->parameter('id'));
        if ($item->commentable_type === "App\Models\Movies") {
            $this->crud->addField([
                "name" => "movies",
                "type" => "select",
                "label" => "Commentable movies",
                "attribute" => "title",
                'model' => "App\Models\Movies"
            ]);
        } else {
            $this->crud->addField([
                "name" => "lists",
                "type" => "select",
                "label" => "Commentable lists",
                "attribute" => "title",
                'model' => "App\Models\Lists"
            ]);
        }

        CRUD::field('status');
        CRUD::field('likes');
        CRUD::field('rating')->type("text")->name("rating");
    }
}
