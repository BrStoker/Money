<?php

namespace App\Traits;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait Fields {

    protected static function getEntityFields(Object $entity, String $entityName, String $fieldModel, String $groupModel = '') {

        $properties = [];

        if($entity) {

            $groups = $groupModel::where('active', 1)->get();

            if($groups->count() > 0) {

                foreach($groups as $group) {

                    $fields = $group->fields;

                    if($fields->count() > 0) {

                        $properties[$group->id] = $group->toArray();

                        if(isset($properties[$group->id]['fields']) == true) {
                            unset($properties[$group->id]['fields']);
                        }

                        foreach($fields as $field) {

                            $arField = $field->toArray();

                            if($entity->values()->count()) {

                                $fieldValues = $entity->values->where($entityName.'_field_id', $field->id);

                                if($fieldValues->count()) {

                                    $value = $fieldValues->first()->value;

                                    $tmpValue = json_decode($value);

                                    if (is_object($tmpValue) == true || is_array($tmpValue) == true) {
                                        $arField['value'] = $tmpValue;
                                    } else {
                                        $arField['value'] = $value;
                                    }
                                }

                                if(\App\Models\User::$fieldTypesView[$field['type']] == 'select2_from_array') {
                                    $arField['options'] = $field->values->toArray();
                                }

                                $properties[$group->id]['fields'][$field->id] = $arField;

                            }
                            # else {

                            #    $arField = $field->toArray();

                            #    $fields_model = $fieldModel::where('active', 1)->where($entityName. '_field_group_id', $group->id)->get()->toArray();

                            #    foreach($fields_model as $option){
                            #        $arField['value'] = null;
                            #    }
                            #    if(\App\Models\User::$fieldTypesView[$field['type']] == 'select2_from_array') {
                            #        $arField['options'] = $field->values->toArray();
                            #    }
                            #    $properties[$group->id]['fields'][$field->id] = $arField;

                            #}

                            unset($arField);

                        }

                    }

                    unset($group);

                }

            }

        }
//        dd($properties);
        return $properties;

    }

    protected static function setEntityFields(Object $entity, String $entryName, String $fieldModel, String $groupModel = '') {
        
        $request = CRUD::getRequest();
    
        if($request->has('fields')) {
                    
            if(empty($request->get('fields')) == false && is_array($request->get('fields')) == true) {

                foreach($request->get('fields') as $index => $value) {

                    $field;


                    if(is_numeric($index) == true){
                        $field = $fieldModel::where('id', $index);
                    } else {
                        $field = $fieldModel::where('slug', $index);
                    }

                    if($field) {

                        $field = $field->first();

                        if($field) {

                            $element = $entity->values()->where($entryName . '_field_id', $field->id)->first();
    
                            if($element) {
                
                                $element->value = $value;
                                $element->save();
                                                    
                            } else {
            
                                $entity->values()->create([
                                    $entryName . '_field_id' => $field->id,
                                    'value' => $value
                                ]);
                                                    
                            }

                        }

                    }
    
                }

            }
    
        }

    }

    protected static function setEntityField(Object $entity, String $entryName, String $fieldModel, String $groupModel = '') {

        $request = CRUD::getRequest();
    
        if($request->has('fields')) {
                    
            if(empty($request->get('fields')) == false && is_array($request->get('fields')) == true) {

                foreach($request->get('fields') as $index => $value) {

                    $field;

                    if(is_numeric($index) == true){
                        $field = $fieldModel::where('id', $index);
                    } else {
                        $field = $fieldModel::where('slug', $index);
                    }

                    if($field->exists()) {

                        $field = $field->first();

                        $element = $entity->values()->where($entryName . '_id', $field->id);
    
                        if($element->exists()) {

                            $element = $element->first();
                            $element->value = $value;
                            $element->save();
                                                    
                        } else {
            
                            $entity->values()->create([
                                $entryName . '_id' => $field->id,
                                'value' => $value
                            ]);
                                                    
                        }

                    }
    
                }

            }
    
        }

    }
    
}