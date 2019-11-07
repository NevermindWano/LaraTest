<?php
Route::middleware('auth', 'admin')->group(function () {
    Route::group(['namespace' => 'Messages'], function() {
        // views
        Route::group(['prefix' => 'messages'], function() {
            Route::view('/', 'messages.index')->middleware('permission:moderate_message');
        });
    });
    // api
    Route::group(['namespace' => 'Admin', 'prefix' => 'api/messages'], function() {
        Route::get('/get', 'MessageController@get');
        Route::post('/status/{message_id}', 'MessageController@setStatus');
    });
});
