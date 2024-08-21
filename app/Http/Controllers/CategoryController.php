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
use Illuminate\Validation\Rule;
use \App\Models\Api;
use \App\Models\Skin;
use \App\Models\Help;
use \App\Models\Category;
use \App\Models\Group;
use \App\Models\Language;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if (isset($_GET['sort'])) {
            $this->params['sort'] = $_GET['sort'];
        } else {
            $this->params['sort'] = 'sort';
        }

        $sort = preg_replace("#[^a-zA-Z_]#", '', $this->params['sort']);

        $query = Category::select('categories.*');

        $query->orderBy('categories.'.$sort, stripos($this->params['sort'], '-') === 0 ? 'desc' :
            'asc');

        $language = Language::where('shortcode', '=', 'en')->first();
        
        if (!$language) {
            $language = Language::first();
        }
        
        if ($language) {
            $query->leftJoin('category_languages', function ($join) use ($language) {
                $join->on('categories.id', '=', 'category_languages.category_id')
                    ->where('category_languages.language_id', '=', $language->id);
            });
            
            $query->select('categories.*', 'category_languages.name');
        }
        
        if (is_array($request->search)) {
            foreach ($request->search as $key => $value) {
                $value = trim($value);

                if (empty($value) && $value != '0') {
                    continue;
                }

                switch ($key) {
                    case 'category':
                        $query->where('categories.'.$key, 'like', '%' . urldecode($value) . '%');

                        break;
                    default:
                        $query->where('categories.'.$key, '=', urldecode($value));

                        break;
                }
            }
        }

        $query->with(['api']);

        $this->params['items'] = $query->paginate(800);
        $this->params['apis'] = Api::orderBy('sort', 'asc')->limit(800)->get();

        return $this->view('categories.index');
    }

    public function item(Request $request)
    {
        $this->params['item'] = null;

        if ($request->id) {
            $this->params['item'] = Category::where('id', '=', $request->id)->firstOrFail();
        }

        $this->params['apis'] = Api::orderBy('sort', 'asc')->limit(800)->get();
        $this->params['languages'] = Language::orderBy('sort', 'asc')->limit(800)->get();

        return $this->view('categories.item');
    }

    public function save(Request $request)
    {
        $this->params['item'] = null;

        if ($request->id) {
            $this->params['item'] = Category::where('id', '=', $request->id)->firstOrFail();
        } else {
            $this->params['item'] = new Category;
        }

        $dataitem = request('dataitem');

        if (!is_array($dataitem)) {
            $dataitem = [];
        }

        $rules = [];

        if (isset($dataitem['shortcode'])) {
            $dataitem['shortcode'] = \App\Helpers\Helper::translit($dataitem['shortcode']);
            
            if ($this->params['item']) {
                $rules['shortcode'] = [
                    'required',
                    Rule::unique('categories')->where(function ($query) use ($request, $dataitem) {
                        return $query->where(function ($query) use ($request, $dataitem) {
                             return $query->where('shortcode', $dataitem['shortcode'])
                                 ->where('api_id', $dataitem['api_id']);
                        })->where("id", "!=", $this->params['item']->id);
                    })
                ];
            } else {
                $rules['shortcode'] = [
                    'required',
                    Rule::unique('categories')->where(function ($query) use ($request, $dataitem) {
                        return $query->where('shortcode', $dataitem['shortcode'])
                                 ->where('api_id', $dataitem['api_id']);
                    })
                ];
            }
        }

        $validation = Validator::make($dataitem, $rules);

        if ($validation->passes()) {
            foreach ($dataitem as $key => $value) {
                switch ($key) {
                    default:
                        $this->params['item']->{$key} = $value;
                        break;
                }
            }
            
            if (isset($_FILES['icon']) && $_FILES['icon']['error'] == '0') {
                if ($this->params['item']->icon && file_exists(public_path() . '/images/category/' . $this->
                    params['item']->icon)) {
                    unlink(public_path() . '/images/category/' . $this->params['item']->icon);

                    $this->params['item']->icon = '';
                }

                $extension = 'jpg';

                $pathinfo = pathinfo($_FILES['icon']['name']);

                if (isset($pathinfo['extension'])) {
                    if ($pathinfo['extension'] == 'jpeg' || $pathinfo['extension'] == 'jpg' || $pathinfo['extension'] ==
                        'png' || $pathinfo['extension'] == 'gif') {
                        $extension = $pathinfo['extension'];

                    } else {
                        $extension = 'txt';
                    }
                }

                do {
                    $filename = \Str::random(30) . '.' . $extension;
                } while (file_exists(public_path() . '/images/category/' . $filename));

                if (file_exists($_FILES['icon']['tmp_name'])) {
                    if (move_uploaded_file($_FILES['icon']['tmp_name'], public_path() .
                        '/images/category/' . $filename)) {
                        $this->params['item']->icon = $filename;
                    }
                }
            }

            $this->params['item']->save();
            
            if (request('names') && is_array(request('names'))) {
                foreach (request('names') as $language_id => $name) {
                    $this->params['item']->categoryLanguagesByLanguageId($language_id, $name);
                }
            }
            
            return Redirect::route('categories.index', []);
        }

        if ($this->params['item']->id) {
            return Redirect::route('categories.edit', ['id' => $this->params['item']->id])->
                withInput()->withErrors($validation)->with('message',
                'Некоторые поля заполнены не верно.');
        } else {
            return Redirect::route('categories.add', [])->
                withInput()->withErrors($validation)->with('message',
                'Некоторые поля заполнены не верно.');
        }
    }

    public function delete(Request $request)
    {
        $this->params['item'] = Category::where('id', '=', $request->id)->firstOrFail();
        $this->params['item']->delete();
        return Redirect::route('categories.index');
    }
    
    public function displaceSort(Request $request)
    {
        $thisItem = Category::where('id', '=', $request->item_id)->first();

        if ($thisItem) {
            if ($request->item_to_id > 0) {
                $selectItem = Category::where('id', '=', $request->item_to_id)->first();
                
                if ($selectItem) {
                    $nextItem = Category::where('sort', '>', $selectItem->sort)->where('id', '!=', $selectItem->id)->orderBy('sort', 'asc')->first();
                    
                    if ($nextItem) {
                        $thisItem->sort = (floatval($selectItem->sort)+floatval($nextItem->sort))/2;
                    } else {
                        $thisItem->sort = floatval($selectItem->sort) + 0.1;
                    }
                    
                    $thisItem->save();
                }
            } else {
                $prevItem = Category::where('sort', '<', $thisItem->sort)->where('id', '!=', $thisItem->id)->orderBy('sort', 'desc')->first();
                $nextItem = Category::where('sort', '>', $thisItem->sort)->where('id', '!=', $thisItem->id)->orderBy('sort', 'asc')->first();
           
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

    public function deleteIcon(Request $request)
    {
        $this->params['item'] = Category::where('id', '=', $request->id)->firstOrFail();

        if ($this->params['item']->icon && file_exists(public_path() . '/images/category/' . $this->
            params['item']->icon)) {
            unlink(public_path() . '/images/category/' . $this->params['item']->icon);
        }
        
        $this->params['item']->icon = '';

        $this->params['item']->save();

        return Response::json(['status' => 1]);
    }

    public function getJson(Request $request)
    {
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
        
        if ($request->api_id) {
            $categories = $query->where("api_id", "=", $request->api_id);
        }
        
        $categories = $query->limit(1000)->get();
        
        return Response::json(['status' => 1, "categories" => $categories->toArray()]);
    }
}
