<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::group(['namespace' => 'Frontend'], function () {
    Route::get('/', 'MessagesController@index');
    Route::get('/create', 'MessagesController@create')->name('create');
    Route::post('/store', 'MessagesController@store')->name('store');

    Route::get('/edit/{message_id}', 'MessagesController@edit')->name('edit');
    Route::post('/update/{message_id}', 'MessagesController@update')->name('update');

    Route::get('/reply/{message_id}', 'MessagesController@reply')->name('reply_check');
    Route::post('/replystore/{message_id}', 'MessagesController@storeReply')->name('reply_store');

});


Auth::routes();

Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    require __DIR__ . '/profile/profile.php';
    require __DIR__ . '/users/users.php';
    require __DIR__ . '/roles/roles.php';
    require __DIR__ . '/roles/permissions.php';
    require __DIR__ . '/modules/modules.php';
    require __DIR__ . '/messages/messages.php';
});
