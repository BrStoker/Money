<?php

namespace App\Traits;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait AdminController {

    public static function fieldsRender(Array $settings = []) {

        if(isset($settings['types']) == true && isset($settings['data']) == true && empty($settings['data']) == false && is_array($settings['data']) == true) {

            foreach($settings['data'] as $group) {
                
                if(isset($group['fields']) == true && empty($group['fields']) == false && is_array($group['fields']) == true) {

                    CRUD::addField([
                        'name' => "html_group" . $group['id'],
                        'type'  => 'custom_html',
                        'value' => '<h4>' . $group['title'] . '</h4>'
                    ]);

                    foreach($group['fields'] as $field) {

                        if(isset($settings['types'][$field['type']]) == true) {

                            $params = [
                                'name' => 'fields['.$field['id'].']',
                                'label' => $field['title'],
                                'type' => $settings['types'][$field['type']]
                            ];

                            if(isset($field['value']) == true) {
                                $params['value'] = $field['value'];
                            }
            
                            if($params['type'] == 'select2_from_array') {
            
                                $params['options'] = [ ];

                                if(isset($field['options']) == true && empty($field['options']) == false) {

                                    if(isset($field['optionsCallback']) == false) {

                                        $field['options'] = array_filter($field['options'], function($item){
                                            return isset($item['value']) == true && !empty($item['value']);
                                        });

                                        $options = [];

                                        if(empty($field['options']) == false) {
                                            foreach($field['options'] as $item) {
                                                $options[$item['id']] = $item['value'];
                                            }
                                        }

                                        $params['options'] = $options;

                                    } else {

                                        $params['options'] = $field['valuesCallback']($field['values']);

                                    }
                                }

                                if(isset($field['multiply']) && empty($field['multiply']) == false ) {
                                    $params['allows_multiple'] = true;
                                }
            
                            }
            
                            CRUD::addField($params);
            
                        }
            
                    }

                }

            }

        }

    }

    public static function fieldRender(Array $settings = []) {

        if(isset($settings['types']) == true && isset($settings['field']) == true && empty($settings['field']) == false && is_array($settings['field']) == true) {

            $params = [
                'title' => 'Значение',
                'name' => 'fields['.$settings['field']['id'].']',
                'type' => $settings['types'][$settings['field']['type']]
            ];

            switch($params['type']) {

                case 'select2_from_array':

                    $count = 1;
                    $values = [];

                    if(isset($settings['count']) == true && $settings['count'] > 0) {
                        $count = $settings['count'];
                    }

                    if(isset($settings['values']) == true && is_array($settings['values']) == true) {
                        $values = $settings['values'];
                    }

                    CRUD::addField([
                        'name' => "html_group" . $settings['field']['id'].'[]',
                        'type'  => 'custom_html',
                        'value' => '<h4>Значения</h4>'
                    ]);

                    CRUD::addField([
                        'name' => 'values',
                        'label' => '',
                        'type' => 'multitext',
                        'count' => $count,
                        'values' => $values
                    ]);
                
                break;

                case 'text':
                case 'number':
                case 'date':
                case 'time':
                #case 'datatime':
                case 'email':
                case 'switch':

                    CRUD::addField([
                        'name' => $params['name'],
                        'label' => $params['title'],
                        'type' => $params['type'],
                        'value' => ($settings['values'] && $settings['values']->exists() == true ? $settings['values']->first()->value: '')
                    ]);
                

                break;

            }

        }


    }
}