<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminUserRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \App\Traits\AdminController;
    use \App\Traits\Fields;

    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/users');
        CRUD::setEntityNameStrings('','Пользователи');
    }

    protected function setupListOperation()
    {

        CRUD::denyAccess('show');

        CRUD::setColumns([
            [
                'name' => 'id',
                'label' => '#'
            ], [
                'name' => 'status',
                'label' => 'Статус',
                'type'  => 'select_from_array',
                'options' => \App\Models\User::$statuses
            ],[
                'name' => 'fio',
                'label' => 'ФИО',
                'type' => 'closure',
                'function' => function($entry) {
                    return $entry->fio;
                }
            ],[
                'name' => 'email',
                'label' => 'Email'
            ],[
                'name' => 'phone',
                'label' => 'Телефон'
            ], [
                'name' => 'online_at',
                'label' => 'Онлайн',
                'type' => 'closure',
                'function' => function($entry) {
                    if($entry->online_at) {
                        return 'Online on '.$entry->online_at;
                    }
                }
            ]
        ]);

        CRUD::addFilter([
            'name'  => 'id',
            'type'  => 'text',
            'label' => 'Ид'
        ], false, function ($value) {
            CRUD::addClause('where', 'id', $value);
        });

        CRUD::addFilter([
            'name'  => 'fio',
            'type'  => 'text',
            'label' => 'Фио'
        ], false, function ($value) {

            $query = CRUD::addClause(function(){ });

            $query->where('first_name', 'like', '%'.$value.'%')
                ->orWhere('last_name', 'like', '%'.$value.'%')
                ->orWhere('second_name', 'like', '%'.$value.'%');

        });

    }

    protected function setupCreateOperation()
    {

        CRUD::setValidation(AdminUserRequest::class);

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
            if($entity->scores)
            {
                CRUD::addField([
                    'name' => "html_scores",
                    'type'  => 'custom_html',
                    'value' => '<b>Оценок: '. $entity->scores->count() . '</b>'
                ]);

                CRUD::addField([
                    'name' => "html_scores_value",
                    'type'  => 'custom_html',
                    'value' => '<b>Рейтинг: '. ( $entity->scores->count() > 0 ? $entity->scores->sum('value') / $entity->scores->count()  : 0 ) . '</b>'
                ]);
            }

            if($entity->viewed)
            {
                CRUD::addField([
                    'name' => "html_viewed",
                    'type'  => 'custom_html',
                    'value' => '<b>Просмотров: '. $entity->viewed->count() . '</b>'
                ]);
            }

            if($entity->subscribers)
            {
                CRUD::addField([
                    'name' => "html_subscribers",
                    'type'  => 'custom_html',
                    'value' => '<b>Подписчиков: '. $entity->subscribers->count() . '</b>'
                ]);
            }

            if($entity->deleted_at)
            {
                CRUD::addField([
                    'name' => "html_online_at",
                    'type'  => 'custom_html',
                    'value' => '<b>Дата онлайн: '. $entity->online_at . '</b>'
                ]);
            }

            if($entity->created_at)
            {
                CRUD::addField([
                    'name' => "html_created_at",
                    'type'  => 'custom_html',
                    'label' => 'Дата создания',
                    'value' => '<b>Дата регистрации: '. $entity->created_at . '</b>'
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
            'name' => "user_group_id",
            'label' => 'Группа',
            'type'        => 'select2_from_array',
            'options'     => \App\Models\UserGroup::arrayForSelect(function($item){
                return [$item->id => $item->title];
            }),
            'allows_null' => false,
            'default' => 1
        ]);

        CRUD::addField([
            'name' => "status",
            'label' => 'Статус',
            'type'        => 'select2_from_array',
            'options'     => \App\Models\User::$statuses,
            'allows_null' => false,
            'allows_multiple' => false,
            'default' => 0
        ]);

        if(empty($entity->image) == false)
        {
            CRUD::addField([
                'name' => "html_image",
                'type'  => 'custom_html',
                'value' => "<img src='/storage/{$entity->image}' style='height:100px;' />"
            ]);
        }

        CRUD::addField([
            'name' => "image",
            'label' => 'Аватар',
            'type' => 'upload',
            'upload' => true,
            'disk' => 'public'
        ]);

        CRUD::addField([
            'name' => "first_name",
            'label' => 'Имя',
            'type' => 'text'
        ]);
        
        CRUD::addField([
            'name' => "last_name",
            'label' => 'Фамлия',
            'type' => 'text'
        ]);

        CRUD::addField([
            'name' => "second_name",
            'label' => 'Отчество',
            'type' => 'text'
        ]);

        CRUD::addField([
            'name' => "email",
            'label' => 'Email',
            'type' => 'text'
        ]);

        CRUD::addField([
            'name' => "phone",
            'label' => 'Телефон',
            'type' => 'text'
        ]);

        CRUD::addField([
            'name' => "gender",
            'label' => 'Пол',
            'type'        => 'select2_from_array',
            'options'     => \App\Models\User::$gender,
            'allows_null' => false
        ]);

        CRUD::addField([
            'name' => "birthday",
            'label' => 'Дата рождения',
            'type' => 'date'
        ]);

        CRUD::addField([
            'name' => "country_id",
            'label' => 'Страна',
            'type'        => 'select2_from_ajax',
            'data_source' => url("country"),
            'model' => "\App\Models\Country",
            'method' => 'POST',
            #'minimum_input_length' => 3,
            'include_all_form_fields' => true
        ]);

        CRUD::addField([
            'name' => "city_id",
            'label' => 'Город',
            'type'        => 'select2_from_ajax',
            'data_source' => url("city"),
            'model' => "\App\Models\City",
            'method' => 'POST',
            #'minimum_input_length' => 3,
            'include_all_form_fields' => true
        ]);

        if($entity) {

            $properties = static::getEntityFields($entity, 'user', '\App\Models\UserField', '\App\Models\UserFieldGroup');

            if(sizeof($properties) > 0) {

                self::fieldsRender([ 'types' => \App\Models\User::$fieldTypesView, 'data' => $properties ]);

                \App\Models\User::saved(function($entity) {

                    $entity = CRUD::getCurrentEntry();

                    static::setEntityFields($entity, 'user', '\App\Models\UserField', '\App\Models\UserFieldGroup');

                });

            }

        }

    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

}
