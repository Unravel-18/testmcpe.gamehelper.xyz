<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([], function()
{
    Route::get('/settings', ['as' => 'api.settings', 'uses' => 'ApiController@settings']);
    
    Route::post('/like-{skin_id}', ['as' => 'api.skin.like', 'uses' => 'ApiController@likeSkin']);
    //Route::get('/like-{skin_id}', ['as' => 'api.skin.like', 'uses' => 'ApiController@likeSkin']);
    Route::post('/nolike-{skin_id}', ['as' => 'api.skin.nolike', 'uses' => 'ApiController@noLikeSkin']);
    Route::get('/{api_shortcode}/categories', ['as' => 'api.categories.skins', 'uses' => 'ApiController@categoriesSkins']);
    Route::get('/{api_shortcode}/list', ['as' => 'api.skins', 'uses' => 'ApiController@skins']);
    Route::get('/{api_shortcode}/{skin_shortcode}', ['as' => 'api.skin', 'uses' => 'ApiController@skin']);
});
