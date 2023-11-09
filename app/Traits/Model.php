<?php

namespace App\Traits;

trait Model {

    public static $activities = [
        0 => 'Нет',
        1 => 'Да'
    ];

    public static $statuses = [
        0 => 'Заблокирован',
        1 => 'На модерации',
        2 => 'Активен'
    ];

    public static $fieldTypes = [
        0 => 'Строка',
        1 => 'Число',
        2 => 'Текст',
        3 => 'Html',
        4 => 'Список',
        5 => 'Дата',
        6 => 'Время',
        7 => 'Дата/время',
        8 => 'Email',
        9 => 'Файл',
        10 => 'Да/Нет',
        11 => 'Счетчик'
    ];

    public static $fieldTypesView = [
        0 => 'text',
        1 => 'number',
        2 => 'textarea',
        3 => 'summernote',
        4 => 'select2_from_array',
        5 => 'date',
        6 => 'time',
        #7 => 'datatime',
        8 => 'email',
        9 => 'upload',
        10 => 'switch',
        11 => 'custom_html'
    ];

    public static $objectType = [
        0 => 'Пользователь',
        1 => 'Статья'
    ];

    public static $metaType = [
        0 => 'Заголовок',
        1 => 'Описание',
        2 => 'Ключевые слова'
    ];

    public static $articleActionsType = [
        0 => 'Лайк',
        1 => 'Дизлайк'
    ];

    public static $gender = [
        0 => 'Не указано',
        1 => 'Мужской',
        2 => 'Женский'
    ];

    public static $notification = [
        0 => 'popup',
        1=> 'inline'
    ];

    public static function arrayForSelect($callback = NULL, $where = []) {

        $arResult = [];

        $result;

        if(is_array($where) == true && empty($where) == false) {
            
            $query = NULL;

            foreach($where as $index => $item) {
                if($query == NULL) {
                    $query = self::where($index, $item);
                } else {
                    $query->where($index, $item);
                }
            }

            if($query){
                $result = $query->get();
            }

        } else {
            $result = self::all();
        }

        if(empty($result) == false)
        {
            foreach($result as $item)
            {
                if($callback <> NULL) {

                    $items = $callback($item);

                    if(isset($items) == true && empty($items) == false) {
                        foreach($items as $index => $item) {
                            $arResult[$index] = $item;
                        }
                    }

                } else {

                    $arResult[$item->id] = $item->value;

                }
                
            }
        }

        return $arResult;

    }

}