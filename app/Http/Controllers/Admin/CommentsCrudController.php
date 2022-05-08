<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CommentsRequest;
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

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Comments::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/comments');
        CRUD::setEntityNameStrings('comments', 'comments');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('movies_id');
        CRUD::column('users_id');
        CRUD::column('title');
        CRUD::column('body');
        CRUD::column('moderated');
        CRUD::column('status');
        CRUD::column('likes');
        CRUD::column('created_at');
        CRUD::column('updated_at');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(CommentsRequest::class);

        $this->crud->addField([
            'name' => 'users',
            'label' => 'Users',
            'type' => 'select',

            'model' => "App\Models\User", // related model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'options'   => (function ($query) {
                return $query->orderBy('name', 'ASC')->get();
            })

        ]);
        $this->crud->addField([
            'name' => 'movies',
            'label' => 'Movies',
            'type' => 'select',

            'model' => "App\Models\Movies", // related model
            'attribute' => 'title', // foreign key attribute that is shown to user
            'options'   => (function ($query) {
                return $query->orderBy('title', 'ASC')->get();
            })

        ]);
        CRUD::field('title');
        $this->crud->addField([
            'name' => 'body',
            'label' => 'Body',

            'type' => 'summernote', // foreign key attribute that is shown to user

        ]);
        CRUD::field('moderated');
        CRUD::field('status');
        CRUD::field('likes');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
