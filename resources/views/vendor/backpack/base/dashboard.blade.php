@extends(backpack_view('blank'))

@php

    /*if (config('backpack.base.show_getting_started')) {
        $widgets['before_content'][] = [
            'type'        => 'view',
            'view'        => 'backpack::inc.getting_started',
        ];
    } else {
        $widgets['before_content'][] = [
            'type'        => 'jumbotron',
            'heading'     => trans('backpack::base.welcome'),
            'content'     => trans('backpack::base.use_sidebar'),
            'button_link' => backpack_url('logout'),
            'button_text' => trans('backpack::base.logout'),
        ];
    }*/

    $widgets['before_content'][] = [
        'type'        => 'chart',
        'controller' => \App\Http\Controllers\Admin\Charts\WeeklyUsersChartController::class,
        //'heading'     => trans('backpack::base.welcome'),
        //'content'     => trans('backpack::base.use_sidebar'),
        //'button_link' => backpack_url('logout'),
        //'button_text' => trans('backpack::base.logout'),// 'class'   => 'card mb-2',
        'wrapper' => ['class'=> 'col-md-12'] ,
        'content' => [
            'header' => 'Пользователи', 
            'body'   => '',
        ],
    ];

    $widgets['before_content'][] = [
        'type'        => 'chart',
        'controller' => \App\Http\Controllers\Admin\Charts\WeeklyArticleChartController::class,
        //'heading'     => trans('backpack::base.welcome'),
        //'content'     => trans('backpack::base.use_sidebar'),
        //'button_link' => backpack_url('logout'),
        //'button_text' => trans('backpack::base.logout'),// 'class'   => 'card mb-2',
        'wrapper' => ['class'=> 'col-md-12'] ,
        'content' => [
            'header' => 'Статьи', 
            'body'   => '',
        ],
    ];

@endphp

@section('content')
@endsection
