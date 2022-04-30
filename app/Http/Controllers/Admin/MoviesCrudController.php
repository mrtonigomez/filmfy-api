<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ItemsRequest;
use App\Http\Requests\MoviesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MoviesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MoviesCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Movies::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/movies');
        CRUD::setEntityNameStrings('movies', 'movies');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setValidation(ItemsRequest::class);

        CRUD::field('title')->tab("Información básica");
        CRUD::field('description')->tab("Información básica");
        CRUD::field('release_date')->tab("Información básica");
        CRUD::field('runtime')->tab("Información básica");
        CRUD::field('status')->tab("Información básica");
        CRUD::field('trailer')->tab("Información básica");
        CRUD::field('category_id')->tab("Información básica");

        $this->crud->addField([
            'name' => 'actors',
            'label' => 'Actor',
            'type'  => 'select_multiple',
            'tab'  => 'Personas involucradas',

            'attribute' => 'name', // foreign key attribute that is shown to user
            'model'     => "App\Models\Actors", // related model
            'pivot'     => true,
            'multiple' => true
        ]);

        $this->crud->addField([
            'name' => 'productors',
            'label' => 'Productor',
            'type'  => 'select_multiple',
            'tab'  => 'Personas involucradas',

            'attribute' => 'name', // foreign key attribute that is shown to user
            'model'     => "App\Models\Productors", // related model
            'pivot'     => true,
            'multiple' => true
        ]);

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
        CRUD::setValidation(MoviesRequest::class);

        $this->crud->addField([
            'name' => 'item',
            'label' => 'Item',
            'type'  => 'select',

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
