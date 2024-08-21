<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Arr;
use \App\Models\Skin;
use \App\Models\Api;
use \App\Models\Help;
use \App\Models\Category;
use \App\Models\Language;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $params = [];

    protected function view($template)
    {
        if (!isset($this->params['apis'])) {
            $this->params['apis'] = Api::orderBy('sort', 'asc')->limit(800)->get();
        }
        
        return view($template, $this->params);
    }

    protected function authHeader()
    {
        if (\App\Helpers\Setting::value('app_api_auth:')) {
            if (Arr::get(getallheaders(), 'app-id') === \App\Helpers\Setting::value('app_id:')) {
                return true;
            } else {
                abort(404);
            }
        }
    }
}
