<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;

class HelpLanguage extends Model
{
    protected $guarded = [];
    
    public function help()
    {
        return $this->hasOne('App\Models\Help', 'id', 'help_id');
    }
    
    public function language()
    {
        return $this->hasOne('App\Models\Language', 'id', 'language_id');
    }
}
