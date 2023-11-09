<?php

namespace App\Traits;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait AdminFields {

    public static function setEntityFieldsTypeSelect($entity) {

        $request = CRUD::getRequest();

        if($request->has('current_values')) {

            if(empty($request->get('current_values')) == false && is_array($request->get('current_values')) == true) {

                $values = array_filter($request->get('current_values'), function($value){ return !empty($value); });

                if(empty($values) == false) {

                    foreach($values as $index => $value) {

                        $element = $entity->values()->find($index);

                        if($element) {

                            if($value == 'del') {
                                $element->delete();
                            } else {
                                $element->value = $value;
                                $element->save();
                            }
  
                        }

                    }

                }

            }

        }

        

        if($request->has('values')) {

            if(empty($request->get('values')) == false && is_array($request->get('values')) == true) {

                $values = array_filter($request->get('values'), function($value){
                    return !empty($value);
                });

                $values = array_map(function($value){
                    return ['value' => $value];
                },$values);

                if(empty($values) == false) {
                    $entity->values()->createMany($values);
                }

            }

        }

        #dd(111);


    }
    
}