<?php

    use Illuminate\Support\Facades\Route;

    Route::get('/', function () {
        return redirect('/feed');
    });

    Route::controller(\App\Http\Controllers\Auth\LoginController::class)->group(function () {
        Route::match(['get', 'post'], '/login', 'login')->name('login');
        Route::match(['get', 'post'], '/logout', 'logout')->name('logout');
        Route::post('/support-link', 'getSupportLink')->name('support-link');
    });
    Route::middleware('auth')->get('/partners', [\App\Http\Controllers\PartnersController::class, 'index'])->name('partners');
    Route::get('/courses', [\App\Http\Controllers\CoursesController::class, 'index'])->name('courses');
    Route::get('/income', [\App\Http\Controllers\IncomeController::class, 'index'])->name('income');

    Route::controller(\App\Http\Controllers\Auth\RegisterController::class)->group(function () {
        Route::match(['get', 'post'], '/register', 'register')->name('register');
        Route::post('/register/confirm', 'confirm')->name('register-confirm');
    });

    Route::controller(\App\Http\Controllers\TrafficController::class)->group(function () {
        Route::get('/traffic', 'index')->name('traffic');


    });


Route::controller(\App\Http\Controllers\UserController::class)->group(function () {
        Route::get('/ref{user_id}', 'setCookie')->name('user-reflink');
        Route::get('/id{user_id}', 'detail')->name('user-detail');
        Route::middleware('auth')->post('/user/interests', 'store_interests')->name('store-intrersts');
        Route::middleware('auth')->get('/user/edit', 'edit')->name('edit-profile');
        Route::middleware('auth')->match(['get', 'post'], '/user/profile/', 'profile')->name('user-profile');
        Route::middleware('auth')->post('/user/image', 'image')->name('user-image');
        Route::get('/getcode', 'getCode')->name('get-code');

        Route::middleware('auth')->match(['get', 'post'], '/user/article', 'add_article')->name('article-add');
        Route::middleware('auth')->post('/subscribe', 'add_subscribe')->name('subscribe');
        Route::middleware('auth')->post('/complain-user', 'complainUser')->name('complain-user');
        Route::middleware('auth')->post('/user/delete', 'userDelete')->name('user-delete');

        Route::post('/profile/forgot', 'forgot_password')->name('profile-forgot');
        Route::post('/changepassword', 'change_password')->name('change-password');

    });

    Route::controller(\App\Http\Controllers\InstrumentsController::class)->group(function () {
        Route::get('/instruments', 'index')->name('instruments');

    });

    Route::controller(\App\Http\Controllers\PeopleController::class)->group(function () {
        Route::get('/people', 'index')->name('people');
        Route::post('/people/search', 'search')->name('people-search');
        Route::middleware('auth')->post('/write-message', 'writeMessage')->name('write-message');

    });

    Route::controller(\App\Http\Controllers\FeedController::class)->group(function () {
        #Route::match(['get', 'post'], '/', 'index')->name('index');
        Route::match(['get', 'post'], '/feed', 'index')->name('index');
        Route::post('/article/search', 'search')->name('article-search');
        Route::post('/article/getinfo', 'get_info')->name('article-getinfo');

        Route::middleware('auth')->match(['get', 'post'], '/article/favorite', 'favorite')->name('add-favorite');
        Route::middleware('auth')->post('/get-favorite', 'getFavorite')->name('get-favorite');
        Route::middleware('auth')->match(['get', 'post'], '/article/edit/{id}', 'article_edit')->name('article-edit');
        Route::middleware('auth')->match(['get', 'post'], '/article/delete/{id}', 'article_delete')->name('article-delete');
        Route::middleware('auth')->match(['get', 'post'],'/article/comment/', 'add_comment')->name('add-comment');
        Route::middleware('auth')->post('/article/image', 'article_image')->name('article-image');
        Route::middleware('auth')->post('/category/add', 'add_category')->name('category-add');
        Route::middleware('auth')->post('/score/add', 'score_add')->name('score-add');
        Route::middleware('auth')->post('/score/remove', 'score_remove')->name('score-remove');
        Route::middleware('auth')->post('/get-score', 'getScore')->name('get-score');
        Route::middleware('auth')->post('/comment/like', 'setLike')->name('set-like');
        Route::middleware('auth')->post('/comment/dislike', 'setDisLike')->name('set-dislike');
        Route::middleware('auth')->post('/comment/get-like', 'getLike')->name('get-like');
        Route::middleware('auth')->post('/upload-image', 'uploadImage')->name('upload-image');
        Route::get('/{slug}', 'show_article')->name('article');
    });



    Route::controller(\App\Http\Controllers\NotificationController::class)->group(function(){
        Route::middleware('auth')->get('/user/notification', 'index')->name('user-notification');
    });

    Route::controller(\App\Http\Controllers\ChatController::class)->group(function () {
        Route::middleware('auth')->post('/getuser', 'getUser')->name('get-user');
        Route::middleware('auth')->post('/save-audio', 'saveAudio')->name('save-audio');
        Route::middleware('auth')->post('/set-read-message', 'setReadMessage')->name('set-read-message');
        Route::middleware('auth')->post('/add-folder', 'addFolder')->name('add-folder');
        Route::middleware('auth')->post('/user-info', 'getUserInfo')->name('user-info');
        Route::middleware('auth')->post('/get-user', 'getUserInformation')->name('get-user-info');
        Route::middleware('auth')->post('/delete-history', 'deleteHistory')->name('delete-history');
        Route::middleware('auth')->post('/folder-edit', 'folderEdit')->name('folder-edit');
        Route::middleware('auth')->post('/change-folder-name', 'changeFolderName')->name('change-folder-name');
        Route::middleware('auth')->post('/contact-to-move', 'contactToMove')->name('folder-to-move');
        Route::middleware('auth')->post('/get-user-folders', 'getUserFolders')->name('get-user-folders');
        Route::middleware('auth')->post('/get-data-del-folder', 'getDataDelFolder')->name('get-data-del-folder');
        Route::middleware('auth')->post('/detele-folder', 'deteleFolder')->name('detele-folder');
        Route::middleware('auth')->post('/block-user', 'blockUser')->name('block-user');
        Route::middleware('auth')->post('/set-reaction', 'setReaction')->name('set-reaction');
        Route::middleware('auth')->post('/delete-message', 'deleteMessage')->name('delete-message');
        Route::middleware('auth')->post('/get-users-to-forward', 'getUsersToForward')->name('get-users-to-forward');
        Route::middleware('auth')->post('/get-users-to-create', 'getUsersToCreate')->name('get-users-to-create');
        Route::middleware('auth')->post('/forward-message', 'forwardMessage')->name('forward-message');
        Route::middleware('auth')->post('/count-messages', 'countMessages')->name('count-messages');
        Route::middleware('auth')->post('/search-user', 'searchUser')->name('search-user');
        Route::middleware('auth')->post('/reload-users', 'reloadUsers')->name('reload-users');
        Route::middleware('auth')->post('/find-user', 'findUser')->name('find-user');
        Route::middleware('auth')->post('/copy-message', 'copyMessage')->name('copy-message');
        Route::middleware('auth')->post('/refresh-tab', 'refreshTab')->name('refresh-tab');
        Route::middleware('auth')->post('/read-messages', 'readMessages')->name('read-messages');
        Route::middleware('auth')->post('/set-user-settings', 'setUserSettings')->name('set-user-settings');
        Route::middleware('auth')->post('/updateContacts', 'updateContacts')->name('updateContacts');


    });

    Route::post('/country', [\App\Http\Controllers\CountryController::class, 'get'])->name('country-get');
    Route::post('/city', [\App\Http\Controllers\CityController::class, 'get'])->name('city-get');

