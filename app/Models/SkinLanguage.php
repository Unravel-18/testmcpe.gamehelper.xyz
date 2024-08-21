<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;

class SkinLanguage extends Model
{
    protected $guarded = [];
    
    public function skin()
    {
        return $this->hasOne('App\Models\Skin', 'id', 'skin_id');
    }
    
    public function language()
    {
        return $this->hasOne('App\Models\Language', 'id', 'language_id');
    }
}
