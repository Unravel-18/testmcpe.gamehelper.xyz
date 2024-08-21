<?php

use Illuminate\Support\Facades\Route;

Route::get('/', ['as' => 'main.index', 'uses' => 'MainController@index']);
Route::get('/adminfiles/{api_shortcode}/{file}', ['as' => 'main.adminfile', 'uses' => 'MainController@adminfile', 'middleware' => 'filter:auth_admin']);
Route::get('/files/{api_shortcode}/{file}', ['as' => 'main.file', 'uses' => 'MainController@file']);
Route::get('/skinfile/{skin_id}', ['as' => 'main.skinfile', 'uses' => 'MainController@skinfile']);
