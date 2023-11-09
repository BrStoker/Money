<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminCityRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class CityCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\City::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/cities');
        CRUD::setEntityNameStrings('', 'Города');

    }

    protected function setupListOperation()
    {

        CRUD::denyAccess('show');

        CRUD::setColumns([
            [
                'name' => 'id',
                'label' => '#'
            ],
            [
                'name' => 'country_id',
                'label' => 'Страна',
                'type'  => 'boolean',
                'options' => \App\Models\Country::arrayForSelect()
            ],
            [
                'name' => 'value',
                'label' => 'Название'
            ]
        ]);

    }

    protected function setupCreateOperation()
    {

        CRUD::setValidation(AdminCityRequest::class);

        $entity = CRUD::getCurrentEntry();

        if(isset($entity->id))
        {
            CRUD::addField([
                'name' => "html_id",
                'type'  => 'custom_html',
                'value' => '<b>#' . $entity->id . '</b>'
            ]);
        }

        if($entity)
        {   

            if($entity->created_at)
            {
                CRUD::addField([
                    'name' => "html_created_at",
                    'type'  => 'custom_html',
                    'label' => 'Дата создания',
                    'value' => '<b>Дата создания: '. $entity->created_at . '</b>'
                ]);
            }
    
            if($entity->updated_at)
            {
                CRUD::addField([
                    'name' => "html_updated_at",
                    'type'  => 'custom_html',
                    'value' => '<b>Дата обновления: '. $entity->updated_at . '</b>'
                ]);
            }

            if($entity->deleted_at)
            {
                CRUD::addField([
                    'name' => "html_deleted_at",
                    'type'  => 'custom_html',
                    'value' => '<b>Дата удаления: '. $entity->deleted_at . '</b>'
                ]);
            }

        }

        CRUD::addField([
            'name' => "country_id",
            'label' => 'Cтрана',
            'type'        => 'select2_from_array',
            'allows_multiple' => false,
            'options'     => \App\Models\Country::arrayForSelect(),
            'allows_null' => false
        ]);

        CRUD::addField([
            'name' => "value",
            'label' => 'Название',
            'type' => 'text'
        ]);


    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

}
