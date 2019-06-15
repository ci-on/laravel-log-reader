<?php

Route::group(['prefix' => config('logreader.prefix', 'logreader'), 'middleware' => config('logreader.middleware', 'auth')], function () {
    Route::get('/', 'Cion\LaravelLogReader\Reader\LogReaderController@index');
});
