<?php

namespace App\Http\Controllers;

use Session;
use Validator;
use Redirect;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Storage;
use Carbon\Carbon;
use File;
use Response;
use DB;
use \App\Models\Skin;
use \App\Models\Api;
use \App\Models\Help;
use \App\Models\Category;
use \App\Models\Language;

class SkinController extends Controller
{
    public function index(Request $request)
    {
        if (Session::get('import_count_all')) {
            $this->params['import_count_all'] = Session::pull('import_count_all');
        }
        if (Session::get('import_count_success')) {
            $this->params['import_count_success'] = Session::pull('import_count_success');
        }
        
        //echo Helper::translate('text string', 'en', 'ru');exit;

        Session::put('skins_sort', $request->sort);
        Session::put('skins_search', $request->search);

        $this->params['itemapi'] = null;

        if (isset($_GET['sort'])) {
            $this->params['sort'] = $_GET['sort'];
        } else {
            $this->params['sort'] = 'sort';
        }

        $sort = preg_replace("#[^a-zA-Z_]#", '', $this->params['sort']);

        $query = Skin::select('skins.*');

        switch ($sort) {
            case 'api_id':
                $query->leftJoin('apis', 'skins.api_id', '=', 'apis.id');
                $sort = 'apis.name';
                break;
            case 'category_id':
                $query->leftJoin('categories', 'skins.category_id', '=', 'categories.id');
                $sort = 'categories.sort';
                break;
            case 'language_id':
                $query->leftJoin('languages', 'skins.language_id', '=', 'languages.id');
                $sort = 'languages.language';
                break;
            default:
                $sort = 'skins.' . $sort;
                break;
        }

        $language = Language::where('shortcode', '=', 'en')->first();

        if (!$language) {
            $language = Language::first();
        }

        if ($language) {
            $query->leftJoin('skin_languages', function ($join)use ($language)
            {
                $join->on('skins.id', '=', 'skin_languages.skin_id')->where('skin_languages.language_id',
                    '=', $language->id); }
            );

            $query->select('skins.*', 'skin_languages.name', 'skin_languages.description');
        }

        $query->orderBy($sort, stripos($this->params['sort'], '-') === 0 ? 'desc' :
            'asc');

        if (is_array($request->search)) {
            foreach ($request->search as $key => $value) {
                $value = trim($value);

                if (empty($value) && $value != '0') {
                    continue;
                }

                switch ($key) {
                    case 'name':
                    case 'img':
                    case 'type_name':
                        $query->where($key, 'like', '%' . urldecode($value) . '%');

                        break;
                    default:
                        $query->where($key, '=', urldecode($value));

                        break;
                }
            }
        }

        $query->with(['api', 'category']);

        $this->params['items'] = $query->paginate(800);

        $this->params['apis'] = Api::orderBy('sort', 'asc')->limit(800)->get();
        $query = Category::orderBy('categories.sort', 'asc');
        $language = Language::where('shortcode', '=', 'en')->first();
        if (!$language) {
            $language = Language::first();
        }
        if ($language) {
            $query->leftJoin('category_languages', function ($join)use ($language)
            {
                $join->on('categories.id', '=', 'category_languages.category_id')->where('category_languages.language_id',
                    '=', $language->id); }
            );

            $query->select('categories.*', 'category_languages.name');
        }
        $this->params['categories'] = $query->limit(800)->get();
        $this->params['languages'] = Language::orderBy('sort', 'asc')->limit(800)->get();

        return $this->view('skins.index');
    }

    public function indexApis(Request $request)
    {
        $api = Api::where('id', '=', $request->id)->firstOrFail();

        if (Session::get('import_count_all')) {
            $this->params['import_count_all'] = Session::pull('import_count_all') . "";
        }
        if (Session::get('import_count_success')) {
            $this->params['import_count_success'] = Session::pull('import_count_success') . "";
        }

        Session::put('skins_sort', $request->sort);
        Session::put('skins_search', $request->search);

        $this->params['itemapi'] = Api::where('id', '=', $request->id)->firstOrFail();

        if (isset($_GET['sort'])) {
            $this->params['sort'] = $_GET['sort'];
        } else {
            $this->params['sort'] = 'sortapi';
        }

        $sort = preg_replace("#[^a-zA-Z_]#", '', $this->params['sort']);

        $query = Skin::select('skins.*');

        $query->where("skins.api_id", "=", $api->id);

        switch ($sort) {
            case 'api_id':
                $query->leftJoin('apis', 'skins.api_id', '=', 'apis.id');
                $sort = 'apis.name';
                break;
            case 'category_id':
                $query->leftJoin('categories', 'skins.category_id', '=', 'categories.id');
                $sort = 'categories.sort';
                break;
            case 'language_id':
                $query->leftJoin('languages', 'skins.language_id', '=', 'languages.id');
                $sort = 'languages.language';
                break;
            default:
                $sort = 'skins.' . $sort;
                break;
        }

        $language = Language::where('shortcode', '=', 'en')->first();

        if (!$language) {
            $language = Language::first();
        }

        if ($language) {
            $query->leftJoin('skin_languages', function ($join)use ($language)
            {
                $join->on('skins.id', '=', 'skin_languages.skin_id')->where('skin_languages.language_id',
                    '=', $language->id); }
            );

            $query->select('skins.*', 'skin_languages.name', 'skin_languages.description');
        }

        $query->orderBy($sort, stripos($this->params['sort'], '-') === 0 ? 'desc' :
            'asc');

        if (is_array($request->search)) {
            foreach ($request->search as $key => $value) {
                $value = trim($value);

                if (empty($value) && $value != '0') {
                    continue;
                }

                switch ($key) {
                    case 'name':
                    case 'img':
                    case 'type_name':
                        $query->where($key, 'like', '%' . urldecode($value) . '%');

                        break;
                    default:
                        $query->where($key, '=', urldecode($value));

                        break;
                }
            }
        }

        $query->with(['api', 'category']);

        $this->params['items'] = $query->paginate(800);

        $this->params['apis'] = Api::orderBy('sort', 'asc')->limit(800)->get();
        $query = Category::orderBy('categories.sort', 'asc');
        $language = Language::where('shortcode', '=', 'en')->first();
        if (!$language) {
            $language = Language::first();
        }
        if ($language) {
            $query->leftJoin('category_languages', function ($join)use ($language)
            {
                $join->on('categories.id', '=', 'category_languages.category_id')->where('category_languages.language_id',
                    '=', $language->id); }
            );

            $query->select('categories.*', 'category_languages.name');
        }
        $query->whereIn("categories.id", Skin::select('skins.category_id')->where("api_id", "=", $api->id)->groupBy("skins.category_id")->get());
        $this->params['categories'] = $query->limit(800)->get();
        $this->params['languages'] = Language::orderBy('sort', 'asc')->limit(800)->get();

        return $this->view('skins.index-api');
    }

    public function item(Request $request)
    {
        $this->params['itemapi'] = Api::where('id', '=', $request->api_id)->first();

        $this->params['item'] = null;

        if ($request->id) {
            $this->params['item'] = Skin::where('id', '=', $request->id)->firstOrFail();
        }

        $this->params['apis'] = Api::orderBy('sort', 'asc')->limit(800)->get();

        $route_params = [];
        if (Session::get('skins_sort')) {
            $route_params['sort'] = Session::get('skins_sort');
        }
        if (is_array(Session::get('skins_search'))) {
            foreach (Session::get('skins_search') as $key => $value) {
                $route_params['search[' . $key . ']'] = $value;
            }
        }
        
        if ($this->params['itemapi']) {
            $this->params['urlback'] = route('apis.skins', array_merge($route_params, ['id' => $this->params['itemapi']->id]));
        } else {
            $this->params['urlback'] = route('skins.index', array_merge($route_params, []));
        }

        $query = Category::orderBy('categories.sort', 'asc');
        $language = Language::where('shortcode', '=', 'en')->first();
        if (!$language) {
            $language = Language::first();
        }
        if ($language) {
            $query->leftJoin('category_languages', function ($join)use ($language)
            {
                $join->on('categories.id', '=', 'category_languages.category_id')->where('category_languages.language_id',
                    '=', $language->id); }
            );

            $query->select('categories.*', 'category_languages.name');
        }
        
        if ($this->params['item'] && $this->params['item']->api_id) {
            $this->params['categories'] = $query->where("api_id", "=", $this->params['item']->api_id)->limit(1000)->get();
        } elseif ($this->params['itemapi']) {
            $this->params['categories'] = $query->where("api_id", "=", $this->params['itemapi']->id)->limit(1000)->get();
        } else {
            $this->params['categories'] = $query->limit(1000)->get();
        }

        $this->params['languages'] = Language::orderBy('sort', 'asc')->limit(800)->get();

        return $this->view('skins.item');
    }

    public function save(Request $request)
    {
        $this->params['itemapi'] = Api::where('id', '=', $request->api_id)->first();

        $this->params['item'] = null;

        if ($request->id) {
            $this->params['item'] = Skin::where('id', '=', $request->id)->firstOrFail();
        } else {
            $this->params['item'] = new Skin;
        }

        $dataitem = request('dataitem');

        if (!is_array($dataitem)) {
            $dataitem = [];
        }

        $rules = [];

        if (isset($dataitem['shortcode'])) {
            $dataitem['shortcode'] = \App\Helpers\Helper::translit($dataitem['shortcode']);

            $rules['shortcode'] = 'required|unique:skins,shortcode' . ($this->params['item'] ?
                ',' . $this->params['item']->id : '');
        }

        $validation = Validator::make($dataitem, $rules);

        $route_params = [];
        if (Session::get('skins_sort')) {
            $route_params['sort'] = Session::get('skins_sort');
        }
        if (is_array(Session::get('skins_search'))) {
            foreach (Session::get('skins_search') as $key => $value) {
                $route_params['search[' . $key . ']'] = $value;
            }
        }
        
        if ($this->params['itemapi']) {
            $this->params['urlback'] = route('apis.skins', array_merge($route_params, ['id' => $this->params['itemapi']->id]));
        } else {
            $this->params['urlback'] = route('skins.index', array_merge($route_params, []));
        }

        if ($validation->passes()) {
            $old_file_link = $this->params['item']->file_link;
            
            foreach ($dataitem as $key => $value) {
                switch ($key) {
                    case 'api_id':
                    case 'category_id':
                    case 'language_id':
                        $this->params['item']->{$key} = $value ? intval($value) : null;
                        break;
                    default:
                        $this->params['item']->{$key} = $value;
                        break;
                }
            }

            if ($old_file_link != $this->params['item']->file_link) {
                $this->params['item']->file_link_size = $this->params['item']->getSizeFileLinkCurl();
            }

            $this->params['item']->save();

            if (isset($_FILES['file']) && $_FILES['file']['error'] == '0') {
                $dir = public_path() . '/apifiles/'.$this->params['item']->api->shortcode.'/';
                
                if ($this->params['item']->file && file_exists($dir . $this->params['item']->file)) {
                    unlink($dir . $this->params['item']->file);

                    $this->params['item']->file = '';
                }

                $extension = 'jpg';

                $pathinfo = pathinfo($_FILES['file']['name']);

                if (isset($pathinfo['extension'])) {
                    if ($pathinfo['extension'] == 'php') {
                        $extension = 'txt';
                    } else {
                        $extension = $pathinfo['extension'];
                    }
                }

                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }

                do {
                    $filename = \Str::random(30) . '-' . $this->params['item']->id . '.' . $extension;
                } while (file_exists($dir . $filename));

                if (file_exists($_FILES['file']['tmp_name'])) {
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $dir . $filename)) {
                        $this->params['item']->file = $filename;
                        $this->params['item']->file_size = intval(filesize($dir . $filename));
                        
                        $this->params['item']->file_link = asset('/files/'. $this->params['item']->api->shortcode.'/' .  $this->params['item']->file);
                        $this->params['item']->file_link_size = $this->params['item']->file_size;
                    }
                }
            }

            if (isset($_FILES['images']) && is_array($_FILES['images'])) {
                if (is_array($_FILES['images']['error'])) {
                    foreach ($_FILES['images']['error'] as $key => $error) {
                        if ($error == '0') {
                            $this->params['item']->addImg($_FILES['images']['name'][$key], $_FILES['images']['tmp_name'][$key]);
                        }
                    }
                }
            }

            if (request('languagedataitem') && is_array(request('languagedataitem'))) {
                foreach (request('languagedataitem') as $language_id => $languageitem) {
                    if (is_array($languageitem)) {
                        $this->params['item']->skinLanguagesByLanguageId($language_id, $languageitem['name'],
                            $languageitem['description']);
                    }
                }
            }

            $skinid = '';

            $language = Language::where('shortcode', '=', 'en')->first();

            if ($language) {
                if (request('languagedataitem') && is_array(request('languagedataitem'))) {
                    foreach (request('languagedataitem') as $language_id => $languageitem) {
                        if ($language->id == $language_id) {
                            $skinid = Helper::translit($languageitem['name'], '-');
                        }
                    }
                }
            }

            if (!$skinid) {
                $skinid = 'skin';
            }

            if (!$this->params['item']->skinid) {
                $this->params['item']->skinid = $skinid . '-001';
            }

            $i = 1;

            while (DB::table('skins')->where('skinid', '=', $this->params['item']->skinid)->
                where('id', '!=', $this->params['item']->id)->count() > 0) {
                $i++;

                if ($i < 10) {
                    $this->params['item']->skinid = $skinid . '-00' . $i;
                } elseif ($i < 100) {
                    $this->params['item']->skinid = $skinid . '-0' . $i;
                } else {
                    $this->params['item']->skinid = $skinid . '-' . $i;
                }
            }

            $this->params['item']->save();

            if ($this->params['itemapi']) {
                return Redirect::route('skins.edit', ['id' => $this->params['item']->id, 'api_id' => $this->params['itemapi']->id]);
                return Redirect::route('apis.skins', array_merge($route_params, ['id' => $this->params['itemapi']->id]));
            } else {
                return Redirect::route('skins.edit', ['id' => $this->params['item']->id]);
                return Redirect::route('skins.index', array_merge($route_params, []));
            }
        }

        if ($this->params['item']->id) {
            if ($this->params['itemapi']) {
                return Redirect::route('skins.edit', array_merge($route_params, ['id' => $this->
                    params['item']->id, 'api_id' => $this->params['itemapi']->id]))->withInput()->
                    withErrors($validation)->with('message', 'Некоторые поля заполнены не верно.');
            } else {
                return Redirect::route('skins.edit', array_merge($route_params, ['id' => $this->
                    params['item']->id]))->withInput()->withErrors($validation)->with('message',
                    'Некоторые поля заполнены не верно.');
            }
        } else {
            if ($this->params['itemapi']) {
                return Redirect::route('skins.add', array_merge($route_params, ['api_id' => $this->
                    params['itemapi']->id]))->withInput()->withErrors($validation)->with('message',
                    'Некоторые поля заполнены не верно.');
            } else {
                return Redirect::route('skins.add', array_merge($route_params, []))->withInput()->
                    withErrors($validation)->with('message', 'Некоторые поля заполнены не верно.');
            }
        }
    }

    public function delete(Request $request)
    {
        $this->params['itemapi'] = Api::where('id', '=', $request->api_id)->first();

        $this->params['item'] = Skin::where('id', '=', $request->id)->firstOrFail();
        $this->params['item']->delete();

        $route_params = [];
        if (Session::get('skins_sort')) {
            $route_params['sort'] = Session::get('skins_sort');
        }
        if (is_array(Session::get('skins_search'))) {
            foreach (Session::get('skins_search') as $key => $value) {
                $route_params['search[' . $key . ']'] = $value;
            }
        }

        if ($this->params['itemapi']) {
            return Redirect::route('apis.skins', array_merge($route_params, ['id' => $this->
                params['itemapi']->id]));
        } else {
            return Redirect::route('skins.index', array_merge($route_params, []));
        }

    }

    public function displaceSort(Request $request)
    {
        $thisItem = Skin::where('id', '=', $request->item_id)->first();

        if ($thisItem) {
            if ($request->item_to_id > 0) {
                $selectItem = Skin::where('id', '=', $request->item_to_id)->first();

                if ($selectItem) {
                    $nextItem = Skin::where('sort', '>', $selectItem->sort)->where('id', '!=', $selectItem->
                        id)->orderBy('sort', 'asc')->first();

                    if ($nextItem) {
                        $thisItem->sort = (floatval($selectItem->sort) + floatval($nextItem->sort)) / 2;
                    } else {
                        $thisItem->sort = floatval($selectItem->sort) + 0.1;
                    }

                    $thisItem->save();
                }
            } else {
                $prevItem = Skin::where('sort', '<', $thisItem->sort)->where('id', '!=', $thisItem->
                    id)->orderBy('sort', 'desc')->first();
                $nextItem = Skin::where('sort', '>', $thisItem->sort)->where('id', '!=', $thisItem->
                    id)->orderBy('sort', 'asc')->first();

                //exit(($prevItem?$prevItem->sort:0).'-'.($thisItem?$thisItem->sort:0).'-'.($nextItem?$nextItem->sort:0));

                switch ($request->type) {
                    case 'up':
                        if ($prevItem) {
                            $tmpsort = $prevItem->sort;
                            $prevItem->sort = $thisItem->sort;
                            $thisItem->sort = $tmpsort;

                            $prevItem->save();
                            $thisItem->save();
                        }

                        break;
                    case 'down':
                        if ($nextItem) {
                            $tmpsort = $nextItem->sort;
                            $nextItem->sort = $thisItem->sort;
                            $thisItem->sort = $tmpsort;

                            $nextItem->save();
                            $thisItem->save();
                        }

                        break;
                }
            }
        }

        return Response::json(['status' => 1]);
    }

    public function displaceSort2(Request $request)
    {
        $api = Api::where('id', '=', $request->api_id)->firstOrFail();

        $thisItem = Skin::where('api_id', '=', $api->id)->where('id', '=', $request->
            item_id)->first();

        if ($thisItem) {
            if ($request->item_to_id > 0) {
                $selectItem = Skin::where('api_id', '=', $api->id)->where('id', '=', $request->
                    item_to_id)->first();

                if ($selectItem) {
                    $nextItem = Skin::where('api_id', '=', $api->id)->where('sortapi', '>', $selectItem->
                        sortapi)->where('id', '!=', $selectItem->id)->orderBy('sortapi', 'asc')->first();

                    if ($nextItem) {
                        $thisItem->sortapi = (floatval($selectItem->sortapi) + floatval($nextItem->
                            sortapi)) / 2;
                    } else {
                        $thisItem->sortapi = floatval($selectItem->sortapi) + 0.1;
                    }

                    $thisItem->save();
                }
            } else {
                $prevItem = Skin::where('api_id', '=', $api->id)->where('sortapi', '<', $thisItem->
                    sortapi)->where('id', '!=', $thisItem->id)->orderBy('sortapi', 'desc')->first();
                $nextItem = Skin::where('api_id', '=', $api->id)->where('sortapi', '>', $thisItem->
                    sortapi)->where('id', '!=', $thisItem->id)->orderBy('sortapi', 'asc')->first();

                //exit(($prevItem ? ($prevItem->id . '|' . $prevItem->sortapi) : '0') . '-' . ($thisItem ? ($thisItem->id . '|' . $thisItem->sortapi) : '0') . '-' . ($nextItem ? ($nextItem->id . '|' . $nextItem->sortapi) : '0'));

                switch ($request->type) {
                    case 'up':
                        if ($prevItem) {
                            $tmpsortapi = $prevItem->sortapi;
                            $prevItem->sortapi = $thisItem->sortapi;
                            $thisItem->sortapi = $tmpsortapi;

                            $prevItem->save();
                            $thisItem->save();
                        }

                        break;
                    case 'down':
                        if ($nextItem) {
                            $tmpsortapi = $nextItem->sortapi;
                            $nextItem->sortapi = $thisItem->sortapi;
                            $thisItem->sortapi = $tmpsortapi;

                            $nextItem->save();
                            $thisItem->save();
                        }

                        break;
                }
            }
        }

        return Response::json(['status' => 1]);
    }

    public function deleteImg(Request $request)
    {
        $this->params['item'] = Skin::where('id', '=', $request->id)->firstOrFail();

        $this->params['item']->delImg($request->image);

        return Response::json(['status' => 1]);
    }

    public function deleteFile(Request $request)
    {
        $this->params['item'] = Skin::where('id', '=', $request->id)->firstOrFail();

        if ($this->params['item']->file && file_exists(public_path() . '/files/skin/' .
            $this->params['item']->file)) {
            unlink(public_path() . '/files/skin/' . $this->params['item']->file);
        }

        $this->params['item']->file = '';

        $this->params['item']->save();

        return Response::json(['status' => 1]);
    }

    public function copySelect(Request $request)
    {
        $items = Skin::whereIn('id', $request->items_id)->get();

        if (is_array($request->new_api_id)) {
            $api_ids = $request->new_api_id;
        } else {
            $api_ids = explode(',', $request->new_api_id);
        }

        if (in_array('all', $api_ids)) {
            $apis = Api::get();
        } else {
            $apis = Api::whereIn('id', $api_ids)->get();
        }

        if (is_array($request->new_category_id)) {
            $category_ids = $request->new_category_id;
        } else {
            $category_ids = explode(',', $request->new_category_id);
        }

        if (is_array($request->new_language_id)) {
            $language_ids = $request->new_language_id;
        } else {
            $language_ids = explode(',', $request->new_language_id);
        }

        if (in_array('all', $category_ids)) {
            $categories = Category::get();
        } else {
            $categories = Category::whereIn('id', $category_ids)->get();
        }

        if (in_array('all', $language_ids)) {
            $languages = Language::get();
        } else {
            $languages = Language::whereIn('id', $language_ids)->get();
        }

        foreach ($items as $item) {
            foreach ($apis as $api) {
                $new_item = new Skin();

                $newitemname = $item->name;

                $new_item->name = $newitemname . ' [copy1]';

                for ($i = 2; $i < 500 && DB::table('skins')->where('name', '=', $new_item->name)->
                    count() > 0; $i++) {
                    $new_item->name = $newitemname . ' [copy' . $i . ']';
                }

                $new_item->api_id = $api->id;
                $new_item->category_id = $item->category_id;
                $new_item->language_id = $item->language_id;

                $new_item->save();
            }

            foreach ($categories as $category) {
                $new_item = new Skin();

                $newitemname = $item->name;

                $new_item->name = $newitemname . ' [copy1]';

                for ($i = 2; $i < 500 && DB::table('skins')->where('name', '=', $new_item->name)->
                    count() > 0; $i++) {
                    $new_item->name = $newitemname . ' [copy' . $i . ']';
                }

                $new_item->category_id = $category->id;
                $new_item->api_id = $item->api_id;
                $new_item->language_id = $item->language_id;
                $new_item->phone = $item->phone;
                $new_item->screenshot = $item->screenshot;
                $new_item->video = $item->video;
                $new_item->description = $item->description;

                if ($new_item->screenshot) {
                    if (!file_exists(public_path() . '/screenshots/' . $new_item->screenshot)) {
                        $new_item->screenshot = '';
                    }
                }

                if ($new_item->screenshot) {
                    $path_parts = pathinfo($new_item->screenshot);

                    if (isset($path_parts['filename']) && isset($path_parts['extension'])) {
                        $filenew = \Str::random(20) . '.copy.' . $path_parts['extension'];

                        copy(public_path() . '/screenshots/' . $new_item->screenshot, public_path() .
                            '/screenshots/' . $filenew);

                        $new_item->screenshot = $filenew;
                    }
                }

                $new_item->save();
            }

            foreach ($languages as $language) {
                $new_item = new Skin();

                $newitemname = $item->name;

                $new_item->name = $newitemname . ' [copy1]';

                for ($i = 2; $i < 500 && DB::table('skins')->where('name', '=', $new_item->name)->
                    count() > 0; $i++) {
                    $new_item->name = $newitemname . ' [copy' . $i . ']';
                }

                $new_item->language_id = $language->id;
                $new_item->category_id = $item->category_id;
                $new_item->api_id = $item->api_id;
                $new_item->phone = $item->phone;
                $new_item->screenshot = $item->screenshot;
                $new_item->video = $item->video;
                $new_item->description = $item->description;
                $new_item->type = $item->type;
                $new_item->code = $item->code;

                if ($new_item->screenshot) {
                    if (!file_exists(public_path() . '/screenshots/' . $new_item->screenshot)) {
                        $new_item->screenshot = '';
                    }
                }

                if ($new_item->screenshot) {
                    $path_parts = pathinfo($new_item->screenshot);

                    if (isset($path_parts['filename']) && isset($path_parts['extension'])) {
                        $filenew = \Str::random(20) . '.copy.' . $path_parts['extension'];

                        copy(public_path() . '/screenshots/' . $new_item->screenshot, public_path() .
                            '/screenshots/' . $filenew);

                        $new_item->screenshot = $filenew;
                    }
                }

                $new_item->save();
            }
        }

        return Response::json(['status' => true]);
    }

    public function deleteSelect(Request $request)
    {
        $items = Skin::whereIn('id', $request->items_id)->get();

        foreach ($items as $item) {
            $item->delete();
        }

        return Response::json(['status' => true]);
    }

    public function set(Request $request)
    {
        Skin::where('type_name', '=', null)->chunk(100, function ($products)
        {
            foreach ($products as $product) {
                $product->type_name = \App\Helpers\Helper::translit(($product->api ? $product->
                    api->shortcode . '_' : '') . $product->name); $product->save(); }
        }
        );
    }

    public function copySVSelect(Request $request)
    {
        $items = Skin::whereIn('id', $request->items_id)->get();

        if (is_array($request->new_api_id)) {
            $api_ids = $request->new_api_id;
        } else {
            $api_ids = $request->new_api_id ? explode(',', $request->new_api_id) : [];
        }

        if (is_array($request->new_category_id)) {
            $category_ids = $request->new_category_id;
        } else {
            $category_ids = $request->new_category_id ? explode(',', $request->
                new_category_id) : [];
        }

        if (is_array($request->new_language_id)) {
            $language_ids = $request->new_language_id;
        } else {
            $language_ids = $request->new_language_id ? explode(',', $request->
                new_language_id) : [];
        }

        $max_id = intval(Skin::max('id'));

        foreach ($items as $item) {
            $query = Skin::where('type_name', '=', $item->type_name)->where('id', '<=', $max_id)->
                where('id', '!=', $item->id);

            if (!empty($api_ids) && !in_array('all', $api_ids)) {
                $query->whereIn('api_id', $api_ids);
            }

            if (!empty($category_ids) && !in_array('all', $category_ids)) {
                $query->whereIn('category_id', $category_ids);
            }

            if (!empty($language_ids) && !in_array('all', $language_ids)) {
                $query->whereIn('language_id', $language_ids);
            }

            $query->chunk(100, function ($skins)use ($item)
            {
                foreach ($skins as $skin) {
                    $skin->screenshot = $item->screenshot; $skin->video = $item->video; $skin->save
                        (); }
            }
            );
        }

        return Response::json(['status' => true]);
    }

    public function import(Request $request)
    {
        set_time_limit(1800);

        $thisapi = null;

        if ($request->api_id) {
            $thisapi = Api::where('id', '=', $request->api_id)->first();
        }

        $language = Language::where('shortcode', '=', 'en')->first();
        
        $count_all = 0;
        $count_success = 0;

        if ($language && isset($_FILES['file']) && $_FILES['file']['error'] == '0') {
            $pathinfo = pathinfo($_FILES['file']['name']);

            if (isset($pathinfo['extension'])) {
                if ($pathinfo['extension'] == 'csv') {
                    $content = file_get_contents($_FILES['file']['tmp_name']);
                    
                    $content = iconv(mb_detect_encoding($content, 'auto'), "UTF-8//IGNORE", $content);
                    
                    $content = preg_replace_callback("#;\".*?\";#sui", function ($matches) {
                        return str_replace("\n", "/n\\", $matches[0]);
                    }, $content);
                    
                    $rows = explode("\n", $content);
                    
                    foreach ($rows as $key => $row) {
                        $rows[$key] = trim(str_replace("/n\\", "\n", $row));
                        
                        if (empty($rows[$key])) {
                            unset($rows[$key]);
                        }
                    }
                    
                    $rows = array_map(function ($item) {
                        return str_getcsv($item, ';'); 
                    }, $rows);

                    if (count($rows)) {
                        $fields = [];

                        foreach ($rows as $key => $value) {
                            if ($key == 0) {
                                foreach ($value as $k => $v) {
                                    $fields[$k] = Helper::translit($v);
                                }

                                unset($rows[$key]);
                            } else {
                                $row = [];
                                foreach ($value as $k => $v) {
                                    $row[$fields[$k]] = trim($v);
                                }
                                $rows[$key] = $row;
                            }
                        }
                        
                        //echo "<pre>";print_r($rows);exit;

                        foreach ($rows as $key => $row) {
                            $count_all++;
                            
                            $row['category'] = Helper::translit($row['category']);

                            if (isset($row['name']) && $row['name'] && 
                                isset($row['category']) && $row['category']
                                //isset($row['img']) && $row['img'] && 
                                //isset($row['file']) && $row['file']
                            ) {
                                $row['name'] = preg_replace("/[^0-9a-z ]/i", "", $row['name']);
                                
                                if (isset($row['api'])) {
                                    $api = Api::where('shortcode', '=', $row['api'])->first();
                                } else {
                                    $api = $thisapi;
                                }

                                if ($api) {
                                    $category = Category::where('api_id', '=', $api->id)->where('shortcode', '=', $row['category'])->first();

                                    if (!$category) {
                                        continue;
                                        
                                        $category = new Category;

                                        $category->api_id = $api->id;
                                        $category->shortcode = $row['category'];

                                        $category->save();

                                        $category->categoryLanguagesByLanguageId($language->id, $row['category']);
                                    }

                                    if ($category && isset($row['name']) && $row['name']) {
                                        if (isset($row['file']) && !empty($row['file'])) {
                                            if (!file_exists(storage_path('import') . '/files/' . $row['file'])) {
                                                continue;
                                            }
                                        }
                                        
                                        if (isset($row['img']) && !empty($row['img'])) {
                                            if (!file_exists(storage_path('import') . '/img/' . $row['img'])) {
                                                continue;
                                            }
                                        }
                                        
                                        if (
                                            Skin::where("api_id", "=", $api->id)
                                                ->leftJoin("skin_languages", "skins.id", "=", "skin_languages.skin_id")
                                                ->where("category_id", "=", $category->id)
                                                ->where(function ($query) use ($row) {
                                                    $query->where("skin_languages.name", "=", $row['name']);
                                                    
                                                    if (isset($row['file']) && !empty($row['file'])) {
                                                        //$query->orWhere("skins.file", "=", $row["file"]);
                                                    }
                                                    
                                                    if (isset($row['img']) && !empty($row['img'])) {
                                                        //$query->orWhere("skins.images", "like", $row["img"]);
                                                    }
                                                })
                                                ->count() == "0"
                                        ) {
                                            $skin = new Skin();

                                            $skin->api_id = $api->id;
                                            $skin->category_id = $category->id;

                                            $skin->skinid = Helper::translit($row['name'], '-') . '-001';

                                            $i = 1;

                                            while (DB::table('skins')->where('skinid', '=', $skin->skinid)->count() > 0) {
                                                $i++;

                                                if ($i < 10) {
                                                    $skin->skinid = Helper::translit($row['name'], '-') . '-00' . $i;
                                                } elseif ($i < 100) {
                                                    $skin->skinid = Helper::translit($row['name'], '-') . '-0' . $i;
                                                } else {
                                                    $skin->skinid = Helper::translit($row['name'], '-') . '-' . $i;
                                                }
                                            }

                                            if (isset($row['min version'])) {
                                                $skin->min_version = $row['min version'];
                                            }
                                            if (isset($row['min_version'])) {
                                                $skin->min_version = $row['min_version'];
                                            }
                                            if (isset($row['downloads'])) {
                                                $skin->downloads = $row['downloads'];
                                            }
                                            if (isset($row['price']) && $row['price'] > 0) {
                                                $skin->price = $row['price'];
                                            }
                                            if (isset($row['likes'])) {
                                                $skin->likes = $row['likes'];
                                            }
                                            if (isset($row['views'])) {
                                                $skin->views = $row['views'];
                                            }
                                            
                                            $mapdir = null;
                                            $urlmapfile = null;
                                            $sizemapfile = null;
                                            
                                            $importmapdir = null;
                                            $importmapfile = null;
                                            $imporsizemapfile = null;
                                            
                                            if (
                                                isset($row['mapfolder']) && 
                                                is_dir(storage_path('import') . "/maps/" . $row['mapfolder']) &&
                                                file_exists(storage_path('import') . "/maps/" . $row['mapfolder'] . "/" . $row['mapfolder'] . ".mcworld")
                                            ) {
                                                $importmapdir = storage_path('import') . "/maps/" . $row['mapfolder'];
                                                $importmapfile = storage_path('import') . "/maps/" . $row['mapfolder'] . "/" . $row['mapfolder'] . ".mcworld";
                                                $imporsizemapfile = intval(filesize(storage_path('import') . "/maps/" . $row['mapfolder'] . "/" . $row['mapfolder'] . ".mcworld"));
                                            } elseif (isset($row['mapfolder'])) {
                                                $mapdir = "https://files.gamehelper.xyz/maps/".$row['mapfolder'];
                                                
                                                $urlmapfile = "https://files.gamehelper.xyz/maps/".$row['mapfolder']."/".$row['mapfolder'].".mcworld";
                                                
                                                $headers = get_headers($urlmapfile, true);
                                                foreach ($headers as $key => $value) {
                                                    unset($headers[$key]);
                
                                                    $headers[strtolower($key)] = $value;
                                                }
                                                
                                                if (stripos($headers[0], "200 OK")) {
                                                    if (isset($headers['content-length'])) {
                                                        $sizemapfile = $headers['content-length'];
                                                    } else {
                                                        continue;
                                                    }
                                                } else {
                                                    continue;
                                                }
                                            }
                                            
                                            $transportdir = null;
                                            $urltransportfile = null;
                                            $sizetransportfile = null;
                                            
                                            $importtransportdir = null;
                                            $importtransportfile = null;
                                            $imporsizetransportfile = null;
                                            
                                            //exit(storage_path('import') . "/transports/" . $row['transportfolder']);
                                            
                                            if (
                                                isset($row['transportfolder']) && 
                                                is_dir(storage_path('import') . "/transports/" . $row['transportfolder']) &&
                                                file_exists(storage_path('import') . "/transports/" . $row['transportfolder'] . "/" . $row['transportfolder'] . ".mcaddon")
                                            ) {
                                                $importtransportdir = storage_path('import') . "/transports/" . $row['transportfolder'];
                                                $importtransportfile = storage_path('import') . "/transports/" . $row['transportfolder'] . "/" . $row['transportfolder'] . ".mcaddon";
                                                $imporsizetransportfile = intval(filesize(storage_path('import') . "/transports/" . $row['transportfolder'] . "/" . $row['transportfolder'] . ".mcaddon"));
                                            } elseif (isset($row['transportfolder'])) {
                                                $transportdir = "https://files.gamehelper.xyz/transports/".$row['transportfolder'];
                                                
                                                $urltransportfile = "https://files.gamehelper.xyz/transports/".$row['transportfolder']."/".$row['transportfolder'].".mcaddon";
                                                
                                                $headers = get_headers($urltransportfile, true);
                                                foreach ($headers as $key => $value) {
                                                    unset($headers[$key]);
                
                                                    $headers[strtolower($key)] = $value;
                                                }
                                                
                                                if (stripos($headers[0], "200 OK")) {
                                                    if (isset($headers['content-length'])) {
                                                        $sizetransportfile = $headers['content-length'];
                                                    } else {
                                                        continue;
                                                    }
                                                } else {
                                                    continue;
                                                }
                                            }
                                            
                                            $weapondir = null;
                                            $urlweaponfile = null;
                                            $sizeweaponfile = null;
                                            
                                            $importweapondir = null;
                                            $importweaponfile = null;
                                            $imporsizeweaponfile = null;
                                            
                                            if (
                                                isset($row['weaponfolder']) && 
                                                is_dir(storage_path('import') . "/weapons/" . $row['weaponfolder']) &&
                                                file_exists(storage_path('import') . "/weapons/" . $row['weaponfolder'] . "/" . $row['weaponfolder'] . ".mcaddon")
                                            ) {
                                                $importweapondir = storage_path('import') . "/weapons/" . $row['weaponfolder'];
                                                $importweaponfile = storage_path('import') . "/weapons/" . $row['weaponfolder'] . "/" . $row['weaponfolder'] . ".mcaddon";
                                                $imporsizeweaponfile = intval(filesize(storage_path('import') . "/weapons/" . $row['weaponfolder'] . "/" . $row['weaponfolder'] . ".mcaddon"));
                                            } elseif (isset($row['weaponfolder'])) {
                                                $weapondir = "https://files.gamehelper.xyz/weapons/".$row['weaponfolder'];
                                                
                                                $urlweaponfile = "https://files.gamehelper.xyz/weapons/".$row['weaponfolder']."/".$row['weaponfolder'].".mcaddon";
                                                
                                                $headers = get_headers($urlweaponfile, true);
                                                foreach ($headers as $key => $value) {
                                                    unset($headers[$key]);
                
                                                    $headers[strtolower($key)] = $value;
                                                }
                                                
                                                if (stripos($headers[0], "200 OK")) {
                                                    if (isset($headers['content-length'])) {
                                                        $sizeweaponfile = $headers['content-length'];
                                                    } else {
                                                        continue;
                                                    }
                                                } else {
                                                    continue;
                                                }
                                            }

                                            $skin->save();
                                            
                                            $count_success++;
                                            
                                            if ($importmapdir) {
                                                $dir = public_path() . '/apifiles/'.$skin->api->shortcode.'/';
                
                                                if ($skin->file && file_exists($dir . $skin->file)) {
                                                    unlink($dir . $skin->file);

                                                    $skin->file = '';
                                                }

                                                $extension = 'jpg';

                                                $pathinfo = pathinfo($importmapfile);

                                                if (isset($pathinfo['extension'])) {
                                                    if ($pathinfo['extension'] == 'php') {
                                                        $extension = 'txt';
                                                    } else {
                                                        $extension = $pathinfo['extension'];
                                                    }
                                                }

                                                if (!is_dir($dir)) {
                                                    mkdir($dir, 0777, true);
                                                }

                                                do {
                                                    $filename = \Str::random(30) . '-' . $skin->id . '.' . $extension;
                                                } while (file_exists($dir . $filename));
                                                
                                                if (copy($importmapfile, $dir . $filename)) {
                                                    $skin->file = $filename;
                                                    $skin->file_size = intval(filesize($dir . $filename));
                                                    
                                                    $skin->file_link = asset('/files/'. $skin->api->shortcode.'/' .  $skin->file);
                                                    $skin->file_link_size = $skin->file_size;
                                                }
                                                
                                                if (is_dir($importmapdir . "/Screenshot")) {
                                                    foreach (scandir($importmapdir . "/Screenshot") as $file) {
                                                        if ($file != "." && $file != "..") {
                                                            $skin->addImg($file, $importmapdir . "/Screenshot" . "/" . $file);
                                                        }
                                                    }
                                                }
                                            }
                                            
                                            if ($mapdir) {
                                                $skin->file_link = $urlmapfile;
                                                $skin->file_link_size = $sizemapfile;
                                                
                                                for ($i = 1; $i <= 10; $i++) {
                                                    $url_img = $mapdir . "/Screenshot/Screenshot_".$i.".png";
                                                    if (!$skin->addImg($url_img, $url_img)) {
                                                        break;
                                                    }
                                                }
                                            }
                                            
                                            if ($importtransportdir) {
                                                $dir = public_path() . '/apifiles/'.$skin->api->shortcode.'/';
                
                                                if ($skin->file && file_exists($dir . $skin->file)) {
                                                    unlink($dir . $skin->file);

                                                    $skin->file = '';
                                                }

                                                $extension = 'jpg';

                                                $pathinfo = pathinfo($importtransportfile);

                                                if (isset($pathinfo['extension'])) {
                                                    if ($pathinfo['extension'] == 'php') {
                                                        $extension = 'txt';
                                                    } else {
                                                        $extension = $pathinfo['extension'];
                                                    }
                                                }

                                                if (!is_dir($dir)) {
                                                    mkdir($dir, 0777, true);
                                                }

                                                do {
                                                    $filename = \Str::random(30) . '-' . $skin->id . '.' . $extension;
                                                } while (file_exists($dir . $filename));
                                                
                                                if (copy($importtransportfile, $dir . $filename)) {
                                                    $skin->file = $filename;
                                                    $skin->file_size = intval(filesize($dir . $filename));
                        
                                                    $skin->file_link = asset('/files/'. $skin->api->shortcode.'/' .  $skin->file);
                                                    $skin->file_link_size = $skin->file_size;
                                                }
                                                
                                                if (is_dir($importtransportdir . "/Screenshot")) {
                                                    foreach (scandir($importtransportdir . "/Screenshot") as $file) {
                                                        if ($file != "." && $file != "..") {
                                                            $skin->addImg($file, $importtransportdir . "/Screenshot" . "/" . $file);
                                                        }
                                                    }
                                                }
                                            }
                                            
                                            if ($transportdir) {
                                                $skin->file_link = $urltransportfile;
                                                $skin->file_link_size = $sizetransportfile;
                                                
                                                for ($i = 1; $i <= 10; $i++) {
                                                    $url_img = $transportdir . "/Screenshot/Screenshot_".$i.".png";
                                                    if (!$skin->addImg($url_img, $url_img)) {
                                                        break;
                                                    }
                                                }
                                            }
                                            
                                            //exit($importweapondir);
                                            
                                            if ($importweapondir) {
                                                $dir = public_path() . '/apifiles/'.$skin->api->shortcode.'/';
                
                                                if ($skin->file && file_exists($dir . $skin->file)) {
                                                    unlink($dir . $skin->file);

                                                    $skin->file = '';
                                                }

                                                $extension = 'jpg';

                                                $pathinfo = pathinfo($importweaponfile);

                                                if (isset($pathinfo['extension'])) {
                                                    if ($pathinfo['extension'] == 'php') {
                                                        $extension = 'txt';
                                                    } else {
                                                        $extension = $pathinfo['extension'];
                                                    }
                                                }

                                                if (!is_dir($dir)) {
                                                    mkdir($dir, 0777, true);
                                                }

                                                do {
                                                    $filename = \Str::random(30) . '-' . $skin->id . '.' . $extension;
                                                } while (file_exists($dir . $filename));
                                                
                                                if (copy($importweaponfile, $dir . $filename)) {
                                                    $skin->file = $filename;
                                                    $skin->file_size = intval(filesize($dir . $filename));
                        
                                                    $skin->file_link = asset('/files/'. $skin->api->shortcode.'/' .  $skin->file);
                                                    $skin->file_link_size = $skin->file_size;
                                                }
                                                
                                                if (is_dir($importweapondir . "/Screenshot")) {
                                                    foreach (scandir($importweapondir . "/Screenshot") as $file) {
                                                        if ($file != "." && $file != "..") {
                                                            $skin->addImg($file, $importweapondir . "/Screenshot" . "/" . $file);
                                                        }
                                                    }
                                                }
                                            }
                                            
                                            if ($weapondir) {
                                                $skin->file_link = $urlweaponfile;
                                                $skin->file_link_size = $sizeweaponfile;
                                                
                                                for ($i = 1; $i <= 10; $i++) {
                                                    $url_img = $weapondir . "/Screenshot/Screenshot_".$i.".png";
                                                    if (!$skin->addImg($url_img, $url_img)) {
                                                        break;
                                                    }
                                                }
                                            }

                                            if (isset($row['img'])) {
                                                $pathinfo = pathinfo($row['img']);

                                                if (isset($pathinfo['basename'])) {
                                                    $skin->addImg($pathinfo['basename'], storage_path('import') . '/img/' . $row['img']);
                                                }
                                            }

                                            if (isset($row['file']) && $row['file']) {
                                                $pathinfo = pathinfo($row['file']);

                                                if (isset($pathinfo['basename'])) {

                                                    if (isset($pathinfo['extension']) && $pathinfo['extension'] == 'php') {
                                                        $extension = 'txt';
                                                    } else {
                                                        $extension = $pathinfo['extension'];
                                                    }
                                                
                                                    if (!is_dir(public_path() . '/apifiles/'.$api->shortcode.'/')) {
                                                        mkdir(public_path() . '/apifiles/'.$api->shortcode.'/', 0777, true);
                                                    }

                                                    do {
                                                        $filename = \Str::random(30) . '.' . $extension;
                                                    } while (file_exists(public_path() . '/apifiles/'.$api->shortcode.'/' . $filename));

                                                    if (file_exists(storage_path('import') . '/files/' . $row['file'])) {
                                                        if (copy(storage_path('import') . '/files/' . $row['file'], public_path() . '/apifiles/'.$api->shortcode.'/' . $filename)) {
                                                            $skin->file = $filename;
                                                        $skin->file_size = intval(filesize(public_path() . '/apifiles/'.$api->shortcode.'/' . $filename));
                                                        }
                                                    }
                                                }
                                            }

                                            $skin->skinLanguagesByLanguageId($language->id, isset($row['name']) ? $row['name'] : null,
                                                isset($row['description']) ? $row['description'] : null);

                                            $skin->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        $count_all;
        $count_success;
        
        Session::put('import_count_all', $count_all . " ");
        Session::put('import_count_success', $count_success . " ");

        $route_params = [];
        if (Session::get('skins_sort')) {
            $route_params['sort'] = Session::get('skins_sort');
        }
        if (is_array(Session::get('skins_search'))) {
            foreach (Session::get('skins_search') as $key => $value) {
                $route_params['search[' . $key . ']'] = $value;
            }
        }

        if ($thisapi) {
            return Redirect::route('apis.skins', array_merge($route_params, ['id' => $thisapi->
                id]));
        } else {
            return Redirect::route('skins.index', array_merge($route_params, []));
        }
    }

    public function translate(Request $request)
    {
        if (file_exists(__dir__ . "/update_log")) {
            unlink(__dir__ . "/update_log");
        }

        $command = "php " . __dir__ . "/exectranslate.php " . route("skins.translate_texts", ["translate_exists" => $request->translate_exists]);

        if (substr(php_uname(), 0, 7) == "Windows") {
            //windows
            pclose(popen("start /B " . $command . " 1> " . __dir__ . "/update_log 2>&1 &", "r"));
        } else {
            //linux
            shell_exec($command . " > /dev/null 2>&1 &");
        }

        return Response::json(['status' => 1]);
    }

    public function translateTexts(Request $request)
    {
        if (!Helper::startProcess()) {
            return;
        }
        
        set_time_limit(60 * 60 * 3);
        
        $language_en = Language::where('shortcode', '=', 'en')->first();

        if ($language_en) {
            $languages = Language::orderBy('sort', 'asc')->where('id', '!=', $language_en->
                id)->limit(800)->get();

            $offset = 0;
            $limit = 50;

            do {
                $skins = Skin::offset($offset)->limit($limit)->with('skin_languages')->get();

                foreach ($skins as $skin) {
                    $skin_language_en = null;

                    foreach ($skin->skin_languages as $skin_language) {
                        if ($skin_language->language_id == $language_en->id) {
                            $skin_language_en = $skin_language;
                            break;
                        }
                    }

                    if ($skin_language_en) {
                        foreach ($languages as $language) {
                            $isname = false;
                            $isdescription = false;

                            foreach ($skin->skin_languages as $skin_language) {
                                if ($skin_language->id == $language->id) {
                                    if ($skin_language->name) {
                                        $isname = true;
                                        break;
                                    }
                                    if ($skin_language->description) {
                                        $isdescription = true;
                                        break;
                                    }
                                }
                            }

                            if (!$isname || !$isdescription) {
                                $skinLanguage = $skin->skinLanguagesByLanguageId($language->id);

                                $name = '';
                                $description = '';

                                if ($skinLanguage) {
                                    $name = $skinLanguage->name;
                                    $description = $skinLanguage->description;
                                }

                                $is = false;

                                if ((!$name && $skin_language_en->name) || $request->translate_exists) {
                                    $is = true;
                                    $name = Helper::translate($skin_language_en->name, "en", $language->shortcode);
                                }
                                if ((!$description && $skin_language_en->description) || $request->translate_exists) {
                                    $is = true;
                                    $description = Helper::translate($skin_language_en->description, "en", $language->
                                        shortcode);
                                }

                                if ($is && ($name || $description)) {
                                    $skin->skinLanguagesByLanguageId($language->id, $name, $description);
                                }
                            }
                        }
                    }
                }

                $offset += $limit;
            } while ($skins->count() == $limit);
        }
        
        Helper::endProcess();
    }
}
