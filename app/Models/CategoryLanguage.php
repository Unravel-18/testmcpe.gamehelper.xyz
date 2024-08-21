<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;

class CategoryLanguage extends Model
{
    protected $guarded = [];
    
    public function category()
    {
        return $this->hasOne('App\Models\Category', 'id', 'category_id');
    }
    
    public function language()
    {
        return $this->hasOne('App\Models\Language', 'id', 'language_id');
    }
}
