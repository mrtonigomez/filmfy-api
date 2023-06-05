<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MoviesRequest;
use App\Http\Services\MoviesService;
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
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    protected MoviesService $moviesService;

    public function __construct(MoviesService $moviesService)
    {
        parent::__construct();
        $this->moviesService = $moviesService;
    }

    public function setup()
    {
        CRUD::setModel(\App\Models\Movies::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/movies');
        CRUD::setEntityNameStrings('movies', 'movies');
    }

    public function store()
    {
        $response = $this->traitStore();
        $rq = $this->crud->getRequest();

        $this->moviesService->executeStoreMoviesEntities($rq);

        return $response;

    }

    public function update()
    {
        $response = $this->traitUpdate();
        $rq = $this->crud->getRequest();

        $this->moviesService->executeStoreMoviesEntities($rq);

        return $response;
    }

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
        CRUD::column('description')->limit(2000);
        CRUD::column('release_date');
        CRUD::column('runtime');
        CRUD::column('status');
        CRUD::column('trailer')->limit(255);
        CRUD::column('category');
        $this->crud->addColumn([
            'label' => 'Entities',
            'type' => 'select_multiple',
            'name' => 'entities',
            'attribute' => 'name',
            'model' => 'App\Models\Entities',
        ]);
        $this->crud->addColumn([
            'label' => 'Comments',
            'type' => 'select',
            'name' => 'comment',
            'attribute' => 'title',
            'model' => 'App\Models\Comments',
        ])->limit(10000);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(MoviesRequest::class);

        CRUD::field('title')->tab("Información básica");
        CRUD::field('description')->tab("Información básica");
        $this->crud->addField([
            'name' => 'category',
            'label' => 'Category',
            'type' => 'select_multiple',
            'tab' => 'Información básica',

            'model' => 'App\Models\Categories',
            'attribute' => 'name',
            'attributes' => [
                'class' => 'form-select',
                'multiple' => 'multiple'
            ],
            'pivot' => true,
            'multiple' => true,
        ]);
        CRUD::field('release_date')->tab("Información básica");

        $this->crud->addField([
            'name' => 'image',
            'label' => 'Image',
            'type' => 'upload',
            'upload' => true,
            'tab' => 'Información básica'
        ]);

        CRUD::field('runtime')->tab("Información básica");
        CRUD::field('status')->tab("Información básica");
        CRUD::field('trailer')->tab("Información básica");

        $this->crud->addField([
            'name' => 'entitiesActors',
            'label' => 'Actores',
            'type' => 'select_multiple',
            'tab' => 'Personas involucradas',

            'attribute' => 'name',
            'pivot' => true,
            'multiple' => true,
            'options' => (function ($query) {
                return $query
                    ->where("roles_id", 1)
                    ->orderBy('name', 'ASC')->get();
            }),
        ]);

        $this->crud->addField([
            'name' => 'entitiesDirectors',
            'label' => 'Directors',
            'type' => 'select_multiple',
            'tab' => 'Personas involucradas',

            'attribute' => 'name',
            'pivot' => true,
            'multiple' => true,
            'options' => (function ($query) {
                return $query
                    ->where("roles_id", 2)
                    ->orderBy('name', 'ASC')->get();
            }),
        ]);

        $this->crud->addField([
            'name' => 'entitiesWritters',
            'label' => 'Escritores',
            'type' => 'select_multiple',
            'tab' => 'Personas involucradas',

            'attribute' => 'name',
            'pivot' => true,
            'multiple' => true,
            'options' => (function ($query) {
                return $query
                    ->where("roles_id", 3)
                    ->orderBy('name', 'ASC')->get();
            }),
        ]);

    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

}
