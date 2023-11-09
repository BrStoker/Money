<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ComplainsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class ComplainCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Complain::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/complain');
        CRUD::setEntityNameStrings('', 'Жалобы');
    }

    protected function setupListOperation()
    {

        CRUD::denyAccess('show');

        CRUD::setColumns([
            [
                'name' => 'id',
                'label' => '#'
            ], [
                'name' => 'request',
                'label' => 'Текст'
            ]
        ]);
       
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(ComplainsRequest::class);

        $entity = CRUD::getCurrentEntry();
        $values = [];

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
            'name' => "user_id",
            'label' => 'Пользователь',
            'type'        => 'select2_from_array',
            'options'     => \App\Models\User::arrayForSelect(function($item){

                $fio = implode(' ',[
                    $item->first_name, $item->last_name, $item->second_name
                ]);

                return [$item->id => '#' . $item->id . ' ('.$fio.', '. $item->email .', '.$item->phone.')'];

            }),
            'allows_null' => false
        ]);

        CRUD::addField([
            'name' => "type",
            'label' => 'Тип',
            'type'        => 'select2_from_array',
            'options'     => \App\Models\Complain::$objectType,
            'allows_null' => false
        ]);

        if(isset($entity->type))
        {

            if($entity->type == 0) {

                CRUD::addField([
                    'name' => "user_id",
                    'label' => 'Пользователь',
                    'type'        => 'select2_from_array',
                    'options'     => \App\Models\User::arrayForSelect(function($item){
        
                        $fio = implode(' ',[
                            $item->first_name, $item->last_name, $item->second_name
                        ]);
        
                        return [$item->id => '#' . $item->id . ' ('.$fio.', '. $item->email .', '.$item->phone.')'];
        
                    }),
                    'allows_null' => false
                ]);

            } else if($entity->type == 1) {

                CRUD::addField([
                    'name' => "article_id",
                    'label' => 'Статья',
                    'type'        => 'select2_from_array',
                    'options'     => \App\Models\Article::arrayForSelect(function($item){
                        return [$item->id => $item->title];
                    }),
                    'allows_null' => false
                ]);

            }
            

        }

        CRUD::addField([
            'name' => "request",
            'label' => 'Запрос',
            'type' => 'summernote'
        ]);

        CRUD::addField([
            'name' => "response",
            'label' => 'Ответ',
            'type' => 'summernote'
        ]);

    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
