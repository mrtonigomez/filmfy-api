<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ListsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ListsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ListsCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Lists::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/lists');
        CRUD::setEntityNameStrings('lists', 'lists');
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
        CRUD::column('users_id');
        CRUD::column('title');
        CRUD::column('description');
        CRUD::column('is_private');
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
        CRUD::setValidation(ListsRequest::class);

        CRUD::field('users_id');
        CRUD::field('title');
        CRUD::field('description');
        CRUD::field('is_private');
        CRUD::field('status');
        CRUD::field('likes');
        $this->crud->addField([
            'name' => 'movies',
            'label' => 'Movies',
            'type' => 'select_multiple',

            'model' => 'App\Models\Movies',
            'attribute' => 'title', // foreign key attribute that is shown to user
            'pivot' => true,
            'multiple' => true,
            'options'   => (function ($query) {
                return $query->orderBy('title', 'ASC')->get();
            }), //  you can use this to filter the results show in the select

        ]);


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
