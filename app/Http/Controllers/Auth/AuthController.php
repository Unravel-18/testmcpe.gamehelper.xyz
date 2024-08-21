<?php

namespace App\Http\Controllers\Auth;

use DB;
use Auth;
use Socialite;
use Redirect;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\Helper;
use Session;
use View;
use \App\Models\Api;
use \App\Models\Skin;
use \App\Models\Help;
use \App\Models\Category;
use \App\Models\Group;
use \App\Models\Language;

class AuthController extends Controller
{
    public function logout(Request $request)
    {
        Session::forget('auth');

        return Redirect::route('apis.index');
    }

    public function login(Request $request)
    {
        return View::make('auth.login');
    }

    public function auth(Request $request)
    {
        if ($request->login == env('ADMIN_LOGIN') && $request->password == env('ADMIN_PASSWORD')) {
            Session::put('auth', '1');

            return Redirect::route('apis.index');
        }

        return Redirect::route('auth.login', [])->withInput();
    }
}
