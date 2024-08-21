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

class ApiController extends Controller
{
    public function settings(Request $request)
    {
        $this->authHeader();

        $obj = new \StdClass;

        $obj->list_update = \App\Helpers\Setting::value('list_update:');
        $obj->ads_d = \App\Helpers\Setting::value('ads_d:');
        $obj->ads_c = \App\Helpers\Setting::value('adspro_c:');
        $obj->pro_c = \App\Helpers\Setting::value('pro_c:');
        $obj->pro_d = \App\Helpers\Setting::value('pro_sd:');

        $query = Language::select('languages.*')
            ->join('skin_languages', 'skin_languages.language_id', '=', 'languages.id')
            ->distinct('languages.id')
            ->orderBy('sort');

        $obj->langs = [];

        foreach ($query->get() as $languagekey => $language) {
            $obj->langs[$languagekey] = new \StdClass;

            $obj->langs[$languagekey]->lng = $language->shortcode;
            $obj->langs[$languagekey]->description = $language->description;
            $obj->langs[$languagekey]->flag = $language->flag ? asset('/images/' . $language->flag) : '';
        }

        return Response::json($obj, 200, [], JSON_HEX_TAG);
    }

    public function likeSkin(Request $request)
    {
        $this->authHeader();

        $skin = Skin::where('skinid', '=', $request->skin_id)->first();

        if (!$skin) {
            return Response::json(['status' => 0], 200, [], JSON_HEX_TAG);
        }

        $skin->likes = $skin->likes + 1;

        $skin->save();

        return Response::json(['status' => 1], 200, [], JSON_HEX_TAG);
    }

    public function noLikeSkin(Request $request)
    {
        $this->authHeader();

        $skin = Skin::where('skinid', '=', $request->skin_id)->first();

        if (!$skin) {
            return Response::json(['status' => 0], 200, [], JSON_HEX_TAG);
        }

        $skin->likes = $skin->likes - 1;

        if ($skin->likes < 0) {
            $skin->likes = 0;
        }

        $skin->save();

        return Response::json(['status' => 1], 200, [], JSON_HEX_TAG);
    }

    public function categoriesSkins(Request $request)
    {
        $response = new \StdClass;

        $this->authHeader();

        $api = Api::where('shortcode', '=', $request->api_shortcode)->first();

        if (!$api) {
            $response->status = 0;

            return Response::json($response, 200, [], JSON_HEX_TAG);
        }

        $response = new \StdClass;

        $response->status = 1;
        $response->helps = [];
        $response->categories = [];

        $helps = Help::with(['help_languages', 'help_languages.language'])
            ->where('api_id', '=', $api->id)
            ->orderBy('sort', 'asc')
            ->limit(1000)
            ->get();

        foreach ($helps as $help) {
            foreach ($help->help_languages as $help_language) {
                if ($help_language->language) {
                    $obj = new \StdClass;

                    $obj->name = $help_language->name;
                    $obj->text = $help_language->text;

                    $response->helps[$help_language->language->shortcode][] = $obj;
                }
            }
        }

        $categories = Category::with(['category_languages', 'category_languages.language'])
            ->where('api_id', '=', $api->id)
            ->orderBy('sort', 'asc')
            ->limit(1000)
            ->get();

        foreach ($categories as $category) {
            $obj = new \StdClass;

            $obj->catid = $category->shortcode;
            $obj->img = $category->icon ? asset('/images/category/' . $category->icon) : '';

            $obj->names = [];

            foreach ($category->category_languages as $category_language) {
                if ($category_language->language && $category_language->language->shortcode) {
                    $obj->names[$category_language->language->shortcode] = $category_language->name;
                }
            }

            $response->categories[] = $obj;
        }

        return Response::json($response, 200, [], JSON_HEX_TAG);
    }

    public function skins(Request $request)
    {
        $response = new \StdClass;

        $this->authHeader();

        $api = Api::where('shortcode', '=', $request->api_shortcode)->first();

        if (!$api) {
            $response->status = 0;

            return Response::json($response, 200, [], JSON_HEX_TAG);
        }

        $response->status = 1;
        $response->contents = [];

        $skins = Skin::with(['api', 'category', 'skin_languages', 'skin_languages.language'])
            ->where('api_id', '=', $api->id)
            ->orderBy('sort', 'asc')
            ->limit(2000)
            ->get();

        foreach ($skins as $skin) {
            if ($skin->category) {
                $obj = new \StdClass;

                $obj->id = $skin->skinid;
                $obj->category = $skin->category->shortcode;

                $obj->names = [];

                foreach ($skin->skin_languages as $skin_language) {
                    if ($skin_language->language && $skin_language->language->shortcode) {
                        $obj->names[$skin_language->language->shortcode] = $skin_language->name;
                    }
                }

                $obj->img = $skin->images ? $skin->assetImage($skin->firstImage()) : '';
                $obj->likes = $skin->likes;
                $obj->downloads = $skin->downloads;
                $obj->views = $skin->views;
                $obj->min_version = $skin->min_version;

                $obj->file = $skin->getFileLink();

                $obj->file_size = $skin->getSizeFile(false);

                $response->contents[] = $obj;
            }
        }

        return Response::json($response, 200, [], JSON_HEX_TAG);
    }

    public function skin(Request $request)
    {
        $response = new \StdClass;

        $this->authHeader();

        $api = Api::where('shortcode', '=', $request->api_shortcode)->first();

        if (!$api) {
            $response->status = 0;

            return Response::json($response, 200, [], JSON_HEX_TAG);
        }

        $skin = Skin::where('skinid', '=', $request->skin_shortcode)
            ->with(['category', 'skin_languages', 'skin_languages.language'])
            ->first();

        if (!$skin) {
            $response->status = 0;

            return Response::json($response, 200, [], JSON_HEX_TAG);
        }

        $response->status = 1;
        $response->content = new \StdClass;

        if ($skin->category) {
            $response->content->id = $skin->skinid;
            $response->content->category = $skin->category->shortcode;
            $response->content->min_version = $skin->min_version;

            $response->content->names = [];
            $response->content->descriptions = [];
            $response->content->images = [];

            foreach ($skin->skin_languages as $skin_language) {
                if ($skin_language->language && $skin_language->language->shortcode) {
                    $response->content->names[$skin_language->language->shortcode] = $skin_language->name;
                    $response->content->descriptions[$skin_language->language->shortcode] = $skin_language->description;
                }
            }

            foreach ($skin->getImages() as $image) {
                $response->content->images[] = $skin->assetImage($image);
            }

            $response->content->file = $skin->getFileLink();
            $response->content->file_size = $skin->getSizeFile(false);
            $response->content->downloads = $skin->downloads;
            $response->content->likes = $skin->likes;
            $response->content->views = $skin->views;
        }

        $skin->views = $skin->views + 1;
        $skin->save();

        return Response::json($response, 200, [], JSON_HEX_TAG);
    }
}
