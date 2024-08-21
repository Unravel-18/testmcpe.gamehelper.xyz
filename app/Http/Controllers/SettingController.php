<?php

namespace App\Http\Controllers;

use Validator;
use Redirect;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Storage;
use Carbon\Carbon;
use File;
use Response;
use DB;
use App\Helpers\Setting;
use \App\Models\Api;
use \App\Models\Skin;
use \App\Models\Help;
use \App\Models\Category;
use \App\Models\Group;
use \App\Models\Language;

class SettingController extends Controller
{
    protected $api = null;

    public function index()
    { 
        return $this->view('setting.index');
    }
    
    public function serversSettings()
    { 
        return $this->view('setting.servers');
    }
    
    public function serversSettingsSave(Request $request)
    { 
        \App\Helpers\Setting::value('minute_not_available', $request->minute_not_available);
        
        return Redirect::route('servers.setting', []);
    }
    
    public function minuteNotAvailableSave(Request $request)
    {
        \App\Helpers\Setting::value($request->key, $request->minute_not_available);
    }
    
    public function save(Request $request)
    {
        \App\Helpers\Setting::value('app_api_auth:', $request->app_api_auth?true:false);
        \App\Helpers\Setting::value('app_id:', $request->app_id);
        
        \App\Helpers\Setting::value('list_update:', floatval($request->list_update));
        \App\Helpers\Setting::value('ads_d:', floatval($request->ads_d));
        \App\Helpers\Setting::value('adspro_c:', floatval($request->adspro_c));
        \App\Helpers\Setting::value('pro_c:', floatval($request->pro_c));
        \App\Helpers\Setting::value('pro_sd:', floatval($request->pro_sd));
        
        return Redirect::route('setting.index', []);
    }
}
