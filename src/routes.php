<?php

use Mohamed\LaravelLogReader\Reader\LogReader;

Route::group(['prefix' => config('logreader.prefix')], function () {
    Route::get('/', 'Mohamed\LaravelLogReader\Reader\LogReaderController@index');
});
