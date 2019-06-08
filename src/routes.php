<?php

use Cion\LaravelLogReader\Reader\LogReader;

Route::group(['prefix' => config('logreader.prefix', 'logreader')], function () {
    Route::get('/', 'Cion\LaravelLogReader\Reader\LogReaderController@index');
});
