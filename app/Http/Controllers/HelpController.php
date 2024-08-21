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
use \App\Models\Skin;
use \App\Models\Api;
use \App\Models\Help;
use \App\Models\Category;
use \App\Models\Language;

class HelpController extends Controller
{
    public function index(Request $request)
    {
        if (isset($_GET['sort'])) {
            $this->params['sort'] = $_GET['sort'];
        } else {
            $this->params['sort'] = 'sort';
        }

        $sort = preg_replace("#[^a-zA-Z_]#", '', $this->params['sort']);

        $query = Help::select('helps.*');
        
        $language = Language::where('shortcode', '=', 'en')->first();
        
        if (!$language) {
            $language = Language::first();
        }
        
        if ($language) {
            $query->leftJoin('help_languages', function ($join) use ($language) {
                $join->on('helps.id', '=', 'help_languages.help_id')
                    ->where('help_languages.language_id', '=', $language->id);
            });
            
            $query->select('helps.*', 'help_languages.name', 'help_languages.text');
        }
        
        switch ($sort) {
            case 'api_id':
                $query->leftJoin('apis', 'helps.api_id', '=', 'apis.id');
                $sort = 'apis.name';
                break;
            default:
                $sort = 'helps.' . $sort;
                break;
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
                    case 'description':
                    case 'name':
                        $query->where($key, 'like', '%' . urldecode($value) . '%');

                        break;
                    default:
                        $query->where($key, '=', urldecode($value));

                        break;
                }
            }
        }
        
        $query->with(['api']);

        $this->params['items'] = $query->paginate(800);
        
        $this->params['apis'] = Api::orderBy('sort', 'asc')->limit(800)->get();
        $this->params['categories'] = Category::orderBy('sort', 'asc')->limit(800)->get();
        $this->params['languages'] = Language::orderBy('sort', 'asc')->limit(800)->get();

        return $this->view('helps.index');
    }

    public function item(Request $request)
    {
        $this->params['item'] = null;

        if ($request->id) {
            $this->params['item'] = Help::where('id', '=', $request->id)->firstOrFail();
        }

        $this->params['apis'] = Api::orderBy('sort', 'asc')->limit(800)->get();
        $this->params['categories'] = Category::orderBy('sort', 'asc')->limit(800)->get();
        $this->params['languages'] = Language::orderBy('sort', 'asc')->limit(800)->get();

        return $this->view('helps.item');
    }

    public function save(Request $request)
    {
        $this->params['item'] = null;

        if ($request->id) {
            $this->params['item'] = Help::where('id', '=', $request->id)->firstOrFail();
        } else {
            $this->params['item'] = new Help;
        }

        $dataitem = request('dataitem');

        if (!is_array($dataitem)) {
            $dataitem = [];
        }

        $rules = [];

        if (isset($dataitem['shortcode'])) {
            $dataitem['shortcode'] = \App\Helpers\Helper::translit($dataitem['shortcode']);

            $rules['shortcode'] = 'required|unique:helps,shortcode' . ($this->params['item'] ?
                ',' . $this->params['item']->id : '');
        }

        $validation = Validator::make($dataitem, $rules);

        if ($validation->passes()) {
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
            
            $this->params['item']->save();
            
            if (request('languagedataitem') && is_array(request('languagedataitem'))) {
                foreach (request('languagedataitem') as $language_id => $languageitem) {
                    if (is_array($languageitem)) {
                        $this->params['item']->helpLanguagesByLanguageId($language_id, $languageitem['name'], $languageitem['text']);
                    }
                }
            }
            
            return Redirect::route('helps.index', []);
        }

        if ($this->params['item']->id) {
            return Redirect::route('helps.edit', ['id' => $this->params['item']->id])->
                withInput()->withErrors($validation)->with('message',
                'Некоторые поля заполнены не верно.');
        } else {
            return Redirect::route('helps.add', [])->
                withInput()->withErrors($validation)->with('message',
                'Некоторые поля заполнены не верно.');
        }
    }

    public function delete(Request $request)
    {
        $this->params['item'] = Help::where('id', '=', $request->id)->firstOrFail();
        $this->params['item']->delete();
        return Redirect::route('helps.index');
    }
    
    public function displaceSort(Request $request)
    {
        $thisItem = Help::where('id', '=', $request->item_id)->first();

        if ($thisItem) {
            if ($request->item_to_id > 0) {
                $selectItem = Help::where('id', '=', $request->item_to_id)->first();
                
                if ($selectItem) {
                    $nextItem = Help::where('sort', '>', $selectItem->sort)->where('id', '!=', $selectItem->id)->orderBy('sort', 'asc')->first();
                    
                    if ($nextItem) {
                        $thisItem->sort = (floatval($selectItem->sort)+floatval($nextItem->sort))/2;
                    } else {
                        $thisItem->sort = floatval($selectItem->sort) + 0.1;
                    }
                    
                    $thisItem->save();
                }
            } else {
                $prevItem = Help::where('sort', '<', $thisItem->sort)->where('id', '!=', $thisItem->id)->orderBy('sort', 'desc')->first();
                $nextItem = Help::where('sort', '>', $thisItem->sort)->where('id', '!=', $thisItem->id)->orderBy('sort', 'asc')->first();
           
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

    public function copySelect(Request $request)
    {
        $items = Help::whereIn('id', $request->items_id)->get();

        if (is_array($request->new_api_id)) {
            $language_ids = $request->new_api_id;
        } else {
            $language_ids = explode(',', $request->new_api_id);
        }

        if (in_array('all', $language_ids)) {
            $languages = Language::get();
        } else {
            $languages = Language::whereIn('id', $language_ids)->get();
        }

        if (is_array($request->new_category_id)) {
            $category_ids = $request->new_category_id;
        } else {
            $category_ids = explode(',', $request->new_category_id);
        }

        if (in_array('all', $language_ids)) {
            $categories = Category::get();
        } else {
            $categories = Category::whereIn('id', $category_ids)->get();
        }

        foreach ($items as $item) {
            foreach ($languages as $language) {
                $new_item = new Help();

                $newitemtitle = $item->title;

                $new_item->title = $newitemtitle . ' [copy1]';

                for ($i = 2; $i < 500 && DB::table('helps')->where('title', '=', $new_item->
                    title)->count() > 0; $i++) {
                    $new_item->title = $newitemtitle . ' [copy' . $i . ']';
                }

                $new_item->language_id = $language->id;
                $new_item->category_id = $item->category_id;
                $new_item->description = $item->description;
                $new_item->api_id = $item->api_id;

                $new_item->save();
            }
            
            foreach ($categories as $category) {
                $new_item = new Help();

                $newitemtitle = $item->title;

                $new_item->title = $newitemtitle . ' [copy1]';

                for ($i = 2; $i < 500 && DB::table('helps')->where('title', '=', $new_item->
                    title)->count() > 0; $i++) {
                    $new_item->title = $newitemtitle . ' [copy' . $i . ']';
                }

                $new_item->category_id = $category->id;
                $new_item->language_id = $item->language_id;
                $new_item->description = $item->description;
                $new_item->api_id = $item->api_id;

                $new_item->save();
            }

        }

        return Response::json(['status' => true]);
    }
}
