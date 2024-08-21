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
use \App\Models\Api;
use \App\Models\Skin;
use \App\Models\Help;
use \App\Models\Category;
use \App\Models\Group;
use \App\Models\Language;

class ApisController extends Controller
{
    public function index(Request $request)
    {
        if (isset($_GET['sort'])) {
            $this->params['sort'] = $_GET['sort'];
        } else {
            $this->params['sort'] = 'sort';
        }

        $sort = preg_replace("#[^a-zA-Z_]#", '', $this->params['sort']);

        $query = Api::select('apis.*');

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
                        $query->where($key, 'like', '%' . urldecode($value) . '%');

                        break;
                    default:
                        $query->where($key, '=', urldecode($value));

                        break;
                }
            }
        }

        $this->params['items'] = $query->paginate(800);

        return $this->view('apis.index');
    }

    public function item(Request $request)
    {
        $this->params['item'] = null;

        if ($request->id) {
            $this->params['item'] = Api::where('id', '=', $request->id)->firstOrFail();
        }

        return $this->view('apis.item');
    }

    public function save(Request $request)
    {
        $this->params['item'] = null;

        if ($request->id) {
            $this->params['item'] = Api::where('id', '=', $request->id)->firstOrFail();
        } else {
            $this->params['item'] = new Api;
        }

        $dataitem = request('dataitem');

        if (!is_array($dataitem)) {
            $dataitem = [];
        }

        $rules = [];

        if (isset($dataitem['shortcode'])) {
            $dataitem['shortcode'] = \App\Helpers\Helper::translit($dataitem['shortcode']);

            $rules['shortcode'] = 'required|unique:apis,shortcode' . ($this->params['item'] ?
                ',' . $this->params['item']->id : '');
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
            
            if (isset($_FILES['img']) && $_FILES['img']['error'] == '0') {
                if ($this->params['item']->img && file_exists(public_path() . '/images/api/' . $this->
                    params['item']->img)) {
                    unlink(public_path() . '/images/api/' . $this->params['item']->img);

                    $this->params['item']->img = '';
                }

                $extension = 'jpg';

                $pathinfo = pathinfo($_FILES['img']['name']);

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
                } while (file_exists(public_path() . '/images/api/' . $filename));

                if (file_exists($_FILES['img']['tmp_name'])) {
                    if (move_uploaded_file($_FILES['img']['tmp_name'], public_path() .
                        '/images/api/' . $filename)) {
                        $this->params['item']->img = $filename;
                    }
                }
            }

            $this->params['item']->save();

            return Redirect::route('apis.index', []);
        }

        if ($this->params['item']->id) {
            return Redirect::route('apis.edit', ['id' => $this->params['item']->id])->
                withInput()->withErrors($validation)->with('message',
                'Некоторые поля заполнены не верно.');
        } else {
            return Redirect::route('apis.add', [])->withInput()->withErrors($validation)->
                with('message', 'Некоторые поля заполнены не верно.');
        }
    }

    public function delete(Request $request)
    {
        $this->params['item'] = Api::where('id', '=', $request->id)->firstOrFail();
        $this->params['item']->delete();
        return Redirect::route('apis.index');
    }

    public function displaceSort(Request $request)
    {
        $thisItem = Api::where('id', '=', $request->item_id)->first();

        if ($thisItem) {
            if ($request->item_to_id > 0) {
                $selectItem = Api::where('id', '=', $request->item_to_id)->first();

                if ($selectItem) {
                    $nextItem = Api::where('sort', '>', $selectItem->sort)->where('id', '!=', $selectItem->
                        id)->orderBy('sort', 'asc')->first();

                    if ($nextItem) {
                        $thisItem->sort = (floatval($selectItem->sort) + floatval($nextItem->sort)) / 2;
                    } else {
                        $thisItem->sort = floatval($selectItem->sort) + 0.1;
                    }

                    $thisItem->save();
                }
            } else {
                $prevItem = Api::where('sort', '<', $thisItem->sort)->where('id', '!=', $thisItem->
                    id)->orderBy('sort', 'desc')->first();
                $nextItem = Api::where('sort', '>', $thisItem->sort)->where('id', '!=', $thisItem->
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

    public function deleteImg(Request $request)
    {
        $this->params['item'] = Api::where('id', '=', $request->id)->firstOrFail();

        if ($this->params['item']->img && file_exists(public_path() . '/images/api/' . $this->
            params['item']->img)) {
            unlink(public_path() . '/images/api/' . $this->params['item']->img);
        }
        
        $this->params['item']->img = '';

        $this->params['item']->save();

        return Response::json(['status' => 1]);
    }
}
