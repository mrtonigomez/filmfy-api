<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ItemsRequest;
use App\Http\Requests\MoviesRequest;
use App\Models\Entities;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;

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
        CRUD::column('id');
        CRUD::column('title');
        CRUD::column('description');
        CRUD::column('release_date');
        CRUD::column('trailer');
        CRUD::column('category');

    }

    protected function setupShowOperation()
    {
        CRUD::column('id');
        CRUD::column('title');
        CRUD::column('description');
        CRUD::column('release_date');
        CRUD::column('runtime');
        CRUD::column('status');
        CRUD::column('trailer');
        CRUD::column('category');
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

        CRUD::field('title')->tab("Información básica");
        CRUD::field('description')->tab("Información básica");
        CRUD::field('release_date')->tab("Información básica");
        CRUD::field('image')->tab("Información básica");
        CRUD::field('runtime')->tab("Información básica");
        CRUD::field('status')->tab("Información básica");
        CRUD::field('trailer')->tab("Información básica")->events([
            'saving' => function ($entry) {
                $data = [
                    "movies_id" => $entry->id,
                    "entities_id"
                ];
                DB::table("movies_entities_roles")->insert();
            },
        ]);;

        $this->crud->addField([
            'name' => 'category',
            'label' => 'Category',
            'type' => 'select_multiple',
            'tab' => 'Información básica',

            'model' => 'App\Models\Categories',
            'attribute' => 'name', // foreign key attribute that is shown to user
            'pivot' => true,
            'multiple' => true,
        ]);

        $this->crud->addField([
            'name' => 'entities',
            'label' => 'Entidades',
            'type' => 'select_multiple',
            'tab' => 'Personas involucradas',

            'model' => 'App\Models\Entities',
            'attribute' => 'name', // foreign key attribute that is shown to user
            'pivot' => true,
            'multiple' => true,
        ]);

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
