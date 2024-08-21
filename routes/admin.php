<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'filter:ip'], function()
{
    Route::get('/skins-set', ['as' => 'skins.set', 'uses' => 'SkinController@set']);
    
    Route::get('/login', ['as' => 'auth.login', 'uses' => 'Auth\AuthController@login']);
    Route::post('/auth', ['as' => 'auth.auth', 'uses' => 'Auth\AuthController@auth']);
    Route::get('/logout', ['as' => 'auth.logout', 'uses' => 'Auth\AuthController@logout']);
    
    Route::group(['middleware' => 'filter:auth_admin'], function()
    {
        Route::get('/', ['as' => 'apis.index.0', 'uses' => 'ApisController@index']);
        Route::get('/apis', ['as' => 'apis.index', 'uses' => 'ApisController@index']);
        Route::get('/apis/add', ['as' => 'apis.add', 'uses' => 'ApisController@item']);
        Route::post('/apis/store', ['as' => 'apis.store', 'uses' => 'ApisController@save']);
        Route::get('/apis/{id}/edit', ['as' => 'apis.edit', 'uses' => 'ApisController@item']);
        Route::post('/apis/{id}/update', ['as' => 'apis.update', 'uses' => 'ApisController@save']);
        Route::get('/apis/{id}/delete', ['as' => 'apis.delete', 'uses' => 'ApisController@delete']);
        Route::post('/apis/displace_sort', ['as' => 'apis.displace_sort', 'uses' => 'ApisController@displaceSort']);
        Route::get('/apis/{id}/contents', ['as' => 'apis.skins', 'uses' => 'SkinController@indexApis']);
        Route::post('/apis/delete_img', ['as' => 'apis.delete_img', 'uses' => 'ApisController@deleteImg']);
        
        Route::get('/contents', ['as' => 'skins.index', 'uses' => 'SkinController@index']);
        Route::get('/contents/add', ['as' => 'skins.add', 'uses' => 'SkinController@item']);
        Route::post('/skins/store', ['as' => 'skins.store', 'uses' => 'SkinController@save']);
        Route::get('/contents/{id}/edit', ['as' => 'skins.edit', 'uses' => 'SkinController@item']);
        Route::post('/skins/{id}/update', ['as' => 'skins.update', 'uses' => 'SkinController@save']);
        Route::get('/contents/{id}/delete', ['as' => 'skins.delete', 'uses' => 'SkinController@delete']);
        Route::post('/skins/displace_sort', ['as' => 'skins.displace_sort', 'uses' => 'SkinController@displaceSort']);
        Route::post('/skins/displace_sort_2', ['as' => 'skins.displace_sort_2', 'uses' => 'SkinController@displaceSort2']);
        Route::post('/skins/delete_img', ['as' => 'skins.delete_img', 'uses' => 'SkinController@deleteImg']);
        Route::post('/skins/delete_file', ['as' => 'skins.delete_file', 'uses' => 'SkinController@deleteFile']);
        Route::post('/skins/copy-select', ['as' => 'skins.copy_select', 'uses' => 'SkinController@copySelect']);
        Route::post('/skins/copy-sv-select', ['as' => 'skins.copy_sv_select', 'uses' => 'SkinController@copySVSelect']);
        Route::post('/skins/delete-select', ['as' => 'skins.delete_select', 'uses' => 'SkinController@deleteSelect']);
        Route::post('/skins/import', ['as' => 'skins.import', 'uses' => 'SkinController@import']);
        Route::post('/skins/translate', ['as' => 'skins.translate', 'uses' => 'SkinController@translate']);
        
        
        Route::get('/helps', ['as' => 'helps.index', 'uses' => 'HelpController@index']);
        Route::get('/helps/add', ['as' => 'helps.add', 'uses' => 'HelpController@item']);
        Route::post('/helps/store', ['as' => 'helps.store', 'uses' => 'HelpController@save']);
        Route::get('/helps/{id}/edit', ['as' => 'helps.edit', 'uses' => 'HelpController@item']);
        Route::post('/helps/{id}/update', ['as' => 'helps.update', 'uses' => 'HelpController@save']);
        Route::get('/helps/{id}/delete', ['as' => 'helps.delete', 'uses' => 'HelpController@delete']);
        Route::post('/helps/displace_sort', ['as' => 'helps.displace_sort', 'uses' => 'HelpController@displaceSort']);
        Route::post('/helps/copy-select', ['as' => 'helps.copy_select', 'uses' => 'HelpController@copySelect']);
        
        Route::get('/categories', ['as' => 'categories.index', 'uses' => 'CategoryController@index']);
        Route::get('/categories/add', ['as' => 'categories.add', 'uses' => 'CategoryController@item']);
        Route::post('/categories/store', ['as' => 'categories.store', 'uses' => 'CategoryController@save']);
        Route::get('/categories/{id}/edit', ['as' => 'categories.edit', 'uses' => 'CategoryController@item']);
        Route::post('/categories/{id}/update', ['as' => 'categories.update', 'uses' => 'CategoryController@save']);
        Route::get('/categories/{id}/delete', ['as' => 'categories.delete', 'uses' => 'CategoryController@delete']);
        Route::post('/categories/displace_sort', ['as' => 'categories.displace_sort', 'uses' => 'CategoryController@displaceSort']);
        Route::post('/categories/delete_icon', ['as' => 'categories.delete_icon', 'uses' => 'CategoryController@deleteIcon']);
        Route::post('/categories/get_json', ['as' => 'categories.get_json', 'uses' => 'CategoryController@getJson']);
        
        Route::get('/languages', ['as' => 'languages.index', 'uses' => 'LanguageController@index']);
        Route::get('/languages/add', ['as' => 'languages.add', 'uses' => 'LanguageController@item']);
        Route::post('/languages/store', ['as' => 'languages.store', 'uses' => 'LanguageController@save']);
        Route::get('/languages/{id}/edit', ['as' => 'languages.edit', 'uses' => 'LanguageController@item']);
        Route::post('/languages/{id}/update', ['as' => 'languages.update', 'uses' => 'LanguageController@save']);
        Route::get('/languages/{id}/delete', ['as' => 'languages.delete', 'uses' => 'LanguageController@delete']);
        Route::post('/languages/displace_sort', ['as' => 'languages.displace_sort', 'uses' => 'LanguageController@displaceSort']);
        Route::post('/languages/delete_flag', ['as' => 'languages.delete_flag', 'uses' => 'LanguageController@deleteFlag']);
        
        Route::get('/setting', ['as' => 'setting.index', 'uses' => 'SettingController@index']);
        Route::post('/setting', ['as' => 'setting.save', 'uses' => 'SettingController@save']);
    });
});

Route::get('/translate_texts/sdf3dfew4342ef', ['as' => 'skins.translate_texts', 'uses' => 'SkinController@translateTexts']);
