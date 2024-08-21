<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;

class Help extends Model
{
    protected $guarded = [];
    
    public function api()
    {
        return $this->hasOne('App\Models\Api', 'id', 'api_id');
    }

    public function help_languages()
    {
        return $this->hasMany('App\Models\HelpLanguage', 'help_id', 'id');
    }

    public function helpLanguagesByLanguageId($language_id, $name = null, $text = null)
    {
        $helpLanguage = null;
        
        foreach ($this->help_languages as $help_language) {
            if ($help_language->language_id == $language_id) {
                $helpLanguage = $help_language;
                
                break;
            }
        }
        
        if ($name || $text) {
            if (!$helpLanguage) {
                $helpLanguage = new HelpLanguage;
                
                $helpLanguage->help_id = $this->id;
                $helpLanguage->language_id = $language_id;
            }
            
            $helpLanguage->name = $name;
            $helpLanguage->text = $text;
            
            $helpLanguage->save();
        }
        
        return $helpLanguage;
    }
}
