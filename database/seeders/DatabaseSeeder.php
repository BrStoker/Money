<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{

    public function translit($value)
    {
        $converter = [
            'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
            'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
            'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
            'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
            'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
            'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
            'э' => 'e',    'ю' => 'yu',   'я' => 'ya',
    
            'А' => 'A',    'Б' => 'B',    'В' => 'V',    'Г' => 'G',    'Д' => 'D',
            'Е' => 'E',    'Ё' => 'E',    'Ж' => 'Zh',   'З' => 'Z',    'И' => 'I',
            'Й' => 'Y',    'К' => 'K',    'Л' => 'L',    'М' => 'M',    'Н' => 'N',
            'О' => 'O',    'П' => 'P',    'Р' => 'R',    'С' => 'S',    'Т' => 'T',
            'У' => 'U',    'Ф' => 'F',    'Х' => 'H',    'Ц' => 'C',    'Ч' => 'Ch',
            'Ш' => 'Sh',   'Щ' => 'Sch',  'Ь' => '',     'Ы' => 'Y',    'Ъ' => '',
            'Э' => 'E',    'Ю' => 'Yu',   'Я' => 'Ya',
        ];
    
        return strtr($value, $converter);
    }

    public function run() {

        DB::table('user_group')->insert([ [
            'active' => 1,
            'slug' => 'user',
            'title' => 'Пользователь'
        ]]);

        DB::table('user_group')->insert([ [
            'active' => 1,
            'slug' => 'admin',
            'title' => 'Администратор'
        ]]);


        for($i = 1; $i < 10; $i++) {

            DB::table('users')->insert([ [
                'user_group_id' => 1,
                'status' => 2,
                'first_name' => 'Иван ' . $i,
                'last_name' => 'Иванов ' . $i,
                'second_name' => 'Иванович ' . $i,
                'email' => 'admin@admin.admin'. $i,
                'phone' => '8999999999'. $i,
                'password' => Hash::make('adminadmin'. $i)
            ]]);

        }

        $countrys = ['Азербайджан', 'Армения', 'Белоруссия', 'Казахстан', 'Киргизия', 'Молдавия', 'Россия', 'Таджикистан', 'Узбекистан'];

        if(isset($countrys) == true && empty($countrys) ==false && is_array($countrys) == true) {
            foreach($countrys as $country) { DB::table('country')->insert([ [ 'value' => $country ] ]); }
        }

    }

}
