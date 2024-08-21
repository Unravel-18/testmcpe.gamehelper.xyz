<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Extensions\SessionHandler;
use Illuminate\Support\Facades\Session;
use \App\Models\Skin;
use \App\Models\Api;
use \App\Models\Help;
use \App\Models\Category;
use \App\Models\Language;
use \App\Models\CategoryLanguage;
use \App\Models\SkinLanguage;
use \App\Models\HelpLanguage;
use DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Session::extend('session', function ($app) {
            return new SessionHandler;
        });
        
        Skin::creating(function ($obj) {
            $obj->sort = Skin::max('sort') + 1;
            $obj->sortapi = Skin::max('sortapi') + 1;
        });
        
        CategoryLanguage::creating(function ($obj) {
            $obj->sort = CategoryLanguage::max('sort') + 1;
        });
        
        SkinLanguage::creating(function ($obj) {
            $obj->sort = SkinLanguage::max('sort') + 1;
        });
        
        HelpLanguage::creating(function ($obj) {
            $obj->sort = HelpLanguage::max('sort') + 1;
        });
        
        Api::creating(function ($obj) {
            $obj->sort = Api::max('sort') + 1;
        });
        
        Help::creating(function ($obj) {
            $obj->sort = Help::max('sort') + 1;
        });
        
        Category::creating(function ($obj) {
            $obj->sort = Category::max('sort') + 1;
        });
        
        Language::creating(function ($obj) {
            $obj->sort = Language::max('sort') + 1;
        });
        
        Category::deleting(function ($obj) {
            DB::table('category_languages')->where('category_id', '=', $obj->id)->delete();
            
            DB::table('skins')
                ->where('category_id', '=', $obj->id)
                ->update(['category_id' => null]);
            
            if ($obj->icon && file_exists(public_path() . '/images/category/' . $obj->icon)) {
                unlink(public_path() . '/images/category/' . $obj->icon);
            }
        });
        
        Skin::deleting(function ($obj) {
            DB::table('skin_languages')->where('skin_id', '=', $obj->id)->delete();
            
            if ($obj->file && file_exists(public_path() . '/files/skin/' . $obj->file)) {
                unlink(public_path() . '/files/skin/' . $obj->file);
            }
            
            foreach (explode(';', $obj->images) as $key => $image) {
                if ($image && file_exists(public_path() . '/images/skin/' . $image)) {
                    unlink(public_path() . '/images/skin/' . $image);
                }
            }
        });
        
        Help::deleting(function ($obj) {
            DB::table('help_languages')->where('help_id', '=', $obj->id)->delete();
        });
        
        Language::deleting(function ($obj) {
            DB::table('category_languages')->where('language_id', '=', $obj->id)->delete();
            DB::table('skin_languages')->where('language_id', '=', $obj->id)->delete();
            DB::table('help_languages')->where('language_id', '=', $obj->id)->delete();
            
            if ($obj->flag && file_exists(public_path() . '/images/' . $obj->flag)) {
                unlink(public_path() . '/images/' . $obj->flag);
            }
        });
        
        Api::deleting(function ($obj) {
            DB::table('skins')
                ->where('api_id', '=', $obj->id)
                ->update(['api_id' => null]);
            DB::table('helps')
                ->where('api_id', '=', $obj->id)
                ->update(['api_id' => null]);
                
            if ($obj->img && file_exists(public_path() . '/images/api/' . $obj->img)) {
                unlink(public_path() . '/images/api/' . $obj->img);
            }
        });
        
        Skin::deleted(function ($obj) {
            if ($obj->screenshot) {
                if ($obj->screenshot && file_exists(public_path() . '/screenshots/' . $obj->screenshot)) {
                    unlink(public_path() . '/screenshots/' . $obj->screenshot);
                }
            }
            if ($obj->sound) {
                if ($obj->sound && file_exists(public_path() . '/sounds/' . $obj->sound)) {
                    unlink(public_path() . '/sounds/' . $obj->sound);
                }
            }
        });
        
        Language::deleted(function ($obj) {
            if ($obj->flag) {
                if ($obj->flag && file_exists(public_path() . '/images/' . $obj->flag)) {
                    unlink(public_path() . '/images/' . $obj->flag);
                }
            }
        });
    }
}
