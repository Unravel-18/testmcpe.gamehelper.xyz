<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;

class Category extends Model
{
    protected $guarded = [];
    
    public function api()
    {
        return $this->hasOne('App\Models\Api', 'id', 'api_id');
    }

    public function category_languages()
    {
        return $this->hasMany('App\Models\CategoryLanguage', 'category_id', 'id');
    }

    public function categoryLanguagesByLanguageId($language_id, $name = null)
    {
        $categoryLanguage = null;
        
        foreach ($this->category_languages as $category_language) {
            if ($category_language->language_id == $language_id) {
                $categoryLanguage = $category_language;
                
                break;
            }
        }
        
        if ($name) {
            if (!$categoryLanguage) {
                $categoryLanguage = new CategoryLanguage;
                
                $categoryLanguage->category_id = $this->id;
                $categoryLanguage->language_id = $language_id;
            }
            
            $categoryLanguage->name = $name;
            
            $categoryLanguage->save();
        }
        
        return $categoryLanguage;
    }
}
