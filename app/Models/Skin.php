<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;

class Skin extends Model
{
    protected $guarded = [];

    public function api()
    {
        return $this->hasOne('App\Models\Api', 'id', 'api_id');
    }

    public function category()
    {
        return $this->hasOne('App\Models\Category', 'id', 'category_id');
    }

    public function skin_languages()
    {
        return $this->hasMany('App\Models\SkinLanguage', 'skin_id', 'id');
    }

    public function skinLanguagesByLanguageId($language_id, $name = null, $description = null)
    {
        $skinLanguage = null;

        foreach ($this->skin_languages as $skin_language) {
            if ($skin_language->language_id == $language_id) {
                $skinLanguage = $skin_language;

                break;
            }
        }

        if ($name || $description) {
            if (!$skinLanguage) {
                $skinLanguage = new SkinLanguage;

                $skinLanguage->skin_id = $this->id;
                $skinLanguage->language_id = $language_id;
            }

            $skinLanguage->name = $name;
            $skinLanguage->description = $description;

            $skinLanguage->save();
        }

        return $skinLanguage;
    }

    public function addImg($imgname, $imgtmp)
    {
        $imgname = trim($imgname);
        $imgtmp = trim($imgtmp);

        if ($this->images) {
            $images = explode(';', $this->images . '');
        } else {
            $images = [];
        }

       if (stripos($imgtmp, "http://") === 0 || stripos($imgtmp, "https://") === 0) {
            $headers = get_headers($imgtmp, true);
            foreach ($headers as $key => $value) {
                unset($headers[$key]);
                
                $headers[strtolower($key)] = $value;
            }
            
            if (stripos($headers[0], "200 OK")) {
                if (isset($headers['content-length'])) {
                    if ($imgtmp && !in_array($imgtmp, $images)) {
                        $images[] = $imgtmp;
                    }
                    
                    $this->images = implode(';', $images);
            
                    $this->save();
                    
                    return true;
                }
            }
        } elseif ($imgname && file_exists($imgtmp)) {
            if ($imgname && !in_array($imgname, $images)) {
                $extension = 'jpg';

                $pathinfo = pathinfo($imgname);

                if (isset($pathinfo['extension'])) {
                    if ($pathinfo['extension'] == 'jpeg' || $pathinfo['extension'] == 'jpg' || $pathinfo['extension'] ==
                        'png' || $pathinfo['extension'] == 'gif') {
                        $extension = $pathinfo['extension'];

                    } else {
                        $extension = 'txt';
                    }
                }
                
                $dir = public_path() . '/images/'.$this->api->shortcode.'/';
                $dirsmall = public_path() . '/images/'.$this->api->shortcode.'/small/';
                
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                
                if (!is_dir($dirsmall)) {
                    mkdir($dirsmall, 0777, true);
                }

                do {
                    $filename = \Str::random(30) . '-' . $this->id . '.' . $extension;
                } while (file_exists($dir . $filename));

                if (file_exists($imgtmp)) {
                    if (copy($imgtmp, $dir . $filename)) {
                        $images[] = $filename;

                        Helper::saveSmallImg($dir . $filename, $dirsmall . $filename, $extension, 64, 64);
                    }
                }
            }

            $this->images = implode(';', $images);
            
            $this->save();
            
            return true;
        }
        
        return false;
    }

    public function delImg($img)
    {
        $img = trim($img);

        if ($this->images) {
            $images = explode(';', $this->images . '');
        } else {
            $images = [];
        }

        foreach ($images as $key => $image) {
            if ($image == $img) {
                unset($images[$key]);

                if (file_exists(public_path() . '/images/skin/' . $image)) {
                    unlink(public_path() . '/images/skin/' . $image);
                }

                if (file_exists(public_path() . '/images/skin/small/' . $image)) {
                    unlink(public_path() . '/images/skin/small/' . $image);
                }
            }
        }

        $this->images = implode(';', $images);
        
        $this->save();
    }

    public function firstImage()
    {
        if ($this->images) {
            $images = explode(';', $this->images . '');
        } else {
            $images = [];
        }
        
        foreach ($images as $key => $image) {
            return $image;
        }
    }

    public function assetImage($img)
    {
        if (stripos($img, "http://") === 0 || stripos($img, "https://") === 0) {
            return $img;
        }
        return asset('/images/'.$this->api->shortcode.'/' . $img);
    }

    public function assetImageSmall($img)
    {
        if (stripos($img, "http://") === 0 || stripos($img, "https://") === 0) {
            return $img;
        }
        return asset('/images/'.$this->api->shortcode.'/small/' . $img);
    }

    public function getImages()
    {
        if ($this->images) {
            $images = explode(';', $this->images . '');
        } else {
            $images = [];
        }
        
        return $images;
    }

    public function getfilesize($format = true)
    {
        if (!$format) {
            return $this->file_size;
        }
        
        if ($this->file_size > 0) {
            if ($this->file_size > 1024 * 1024) {
                return round(($this->file_size/1024)/1024, 1) . ' mb';
            }
            
            if ($this->file_size > 1024) {
                return round($this->file_size/1024, 1) . ' kb';
            }
            
            return $this->file_size . ' byte';
        }
        
        return '';
    }

    public function getFileLink()
    {
        if ($this->file_link) {
            return route("main.skinfile", ["skin_id" => $this->id]);
            
            return $this->file_link;
        }
        
        return asset('/files/'.$this->api->shortcode.'/' . $this->file);
    }

    public function getSizeFileLink($format = true)
    {
        if (!$format) {
            return $this->file_link_size;
        }
        
        if ($this->file_link_size > 0) {
            if ($this->file_link_size > 1024 * 1024) {
                return round(($this->file_link_size/1024)/1024, 1) . ' mb';
            }
            
            if ($this->file_link_size > 1024) {
                return round($this->file_link_size/1024, 1) . ' kb';
            }
            
            return $this->file_link_size . ' byte';
        }
        
        return '0kb';
    }

    public function getSizeFile($format = true)
    {
        if ($this->file_link) {
            return $this->getSizeFileLink($format);
        } else {
            return $this->getfilesize($format);
        }
    }

    public function getSizeFileLinkCurl()
    {
        if ($this->file_link) {
            try {
                $headers = get_headers($this->file_link, true);
                foreach ($headers as $key => $value) {
                    unset($headers[$key]);
                
                    $headers[strtolower($key)] = $value;
                }
                
                if (isset($headers['content-length'])) {
                    return $headers['content-length'];
                }
            } catch(\Exception $e) {
            }
        }
        
        return null;
    }
}
