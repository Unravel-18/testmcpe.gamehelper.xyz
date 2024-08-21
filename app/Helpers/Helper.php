<?php

namespace App\Helpers;

use \Storage;
use DB;

class Helper
{
    protected static $fplockProcess = null;
    protected static $isProcess = null;
    
    public static function isProcess()
    {
        if (is_null(self::$isProcess)) {
            self::$isProcess = false;
            
            if (self::isLockProcess()) {
                self::$isProcess = true;
            }
        }
        
        return self::$isProcess;
    }
    
    public static function isLockProcess()
    {
        $res = false;
        
        if (file_exists(__dir__  . "/process.lock")) {
            $fplock = fopen(__dir__  . "/process.lock", 'r+');
                    
            if(!flock($fplock, LOCK_EX | LOCK_NB)) {
                $res = true;
            }
                
            fclose($fplock);
        }
        
        return $res;
    }
    
    public static function startProcess()
    {
        if (!file_exists(__dir__ . "/process.lock")) {
            file_put_contents(__dir__ . "/process.lock", "");
        }
        
        self::$fplockProcess = fopen(__dir__ . "/process.lock", "r+");
        
        return flock(self::$fplockProcess, LOCK_EX | LOCK_NB);
    }
    
    public static function endProcess()
    {
        if (self::$fplockProcess) {
            fclose(self::$fplockProcess);
        }
        
        if (__dir__ . "/process.lock") {
            unlink(__dir__ . "/process.lock");
        }
    }
    
    public static function cmdexec($command)
    {
        if (file_exists(__dir__ . "/exec_log")) {
            unlink(__dir__ . "/exec_log");
        }

        if (substr(php_uname(), 0, 7) == "Windows") {
            //windows
            pclose(popen("start /B " . $command . " 1> " . __dir__ . "/exec_log 2>&1 &",
                "r"));
        } else {
            //linux
            shell_exec($command . " > /dev/null 2>&1 &");
        }
    }
    
    public static function translate($text, $source = "en", $target = "ru")
    {
        $url = 'https://www.googleapis.com/language/translate/v2?key=' . env('GOOGLE_TRANSLATION_KEY') .
            '&q=' . rawurlencode($text) . '&source=' . $source . '&target=' . $target;

        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($handle);
        $responseDecoded = json_decode($response, true);
        curl_close($handle);
        
        //print_r($response);exit;                

        if ($responseDecoded && is_array($responseDecoded) && isset($responseDecoded['data']) &&
            is_array($responseDecoded['data']) && isset($responseDecoded['data']['translations']) &&
            is_array($responseDecoded['data']['translations']) && isset($responseDecoded['data']['translations'][0]) &&
            is_array($responseDecoded['data']['translations'][0]) && isset($responseDecoded['data']['translations'][0]['translatedText'])) {
            return $responseDecoded['data']['translations'][0]['translatedText'];
        }

        return null;
    }

    public static function saveSmallImg($file, $newfile, $type = 'webp', $max_width =
        400, $max_height = 300, $ratio = null)
    {
        if (file_exists($file)) {
            $pthinfo = pathinfo($file);

            list($width, $height) = getimagesize($file);

            $dst_x = 0;
            $dst_y = 0;
            $src_x = 0;
            $src_y = 0;

            $dst_w = $width;
            $dst_h = $height;
            $src_w = $width;
            $src_h = $height;

            if ($ratio) {
                if ($width > $height) {
                    $src_w = round($src_h * $ratio);
                    $dst_w = round($dst_h * $ratio);
                } else {
                    $src_h = round($src_w * $ratio);
                    $dst_h = round($dst_w * $ratio);
                }
            }

            if ($dst_w > $max_width) {
                $percent = $max_width / $dst_w;

                $dst_w = round($dst_w * $percent);
                $dst_h = round($dst_h * $percent);
            }

            if ($dst_h > $max_height) {
                $percent = $max_height / $dst_h;

                $dst_w = round($dst_w * $percent);
                $dst_h = round($dst_h * $percent);
            }

            $src_x = floor(($width - $src_w) / 2);
            $src_y = floor(($height - $src_h) / 2);

            $image = self::imageCreateFromAny($file);

            if ($image) {
                $image_p = imagecreatetruecolor($dst_w, $dst_h);

                if (strtolower($pthinfo['extension']) == 'png') {
                    imagepalettetotruecolor($image_p);
                    imagealphablending($image_p, true);
                    imagesavealpha($image_p, true);

                    $trans_colour = imagecolorallocatealpha($image_p, 0, 0, 0, 127);
                    imagefill($image_p, 0, 0, $trans_colour);
                }

                imagecopyresampled($image_p, $image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h,
                    $src_w, $src_h);

                if (file_exists($newfile)) {
                    unlink($newfile);
                }

                $is = false;

                switch ($type) {
                    case 'jpg':
                    case 'jpeg':
                        $is = imagejpeg($image_p, $newfile, 100);

                        break;
                    case 'gif':
                        $is = imagegif($image_p, $newfile);

                        break;
                    case 'png':
                        $is = imagepng($image_p, $newfile, 9);

                        break;
                    case 'bmp':
                        $is = imagewbmp($image_p, $newfile);

                        break;
                    case 'webp':
                        $is = imagewebp($image_p, $newfile, 100);

                        break;
                    default:
                        $is = imagejpeg($image_p, $newfile, 100);

                        break;
                }

                imagedestroy($image_p);

                return $is;
            }
        }

        return null;
    }

    public static function imageCreateFromAny($filepath)
    {
        $type = \exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize()
        $allowedTypes = array(
            1, // [] gif
            2, // [] jpg
            3, // [] png
            6 // [] bmp
                );
        if (!in_array($type, $allowedTypes)) {
            return false;
        }
        switch ($type) {
            case 1:
                $im = imageCreateFromGif($filepath);
                break;
            case 2:
                $im = imageCreateFromJpeg($filepath);
                break;
            case 3:
                $im = imageCreateFromPng($filepath);
                break;
            case 6:
                $im = imageCreateFromBmp($filepath);
                break;
            case 15:
                $im = imagecreateFromWebP($filepath);
                break;
        }
        return $im;
    }

    public static function crypt($value, $encryption_method, $encryption_key)
    {
        switch ($encryption_method) {
            case '1';
                return self::cryptMethod1Md5($value, $encryption_key);
                break;
            case '2';
                return self::cryptMethod2Md5($value, $encryption_key);
                break;
            case '3';
                return self::cryptMethod3Md5($value, $encryption_key);
                break;
            case '4';
                return self::cryptMethod4Md5($value, $encryption_key);
                break;
            case '5';
                return self::cryptMethod5Md5($value, $encryption_key);
                break;
            default;
                return self::cryptMd5($value, $encryption_key);
                break;
        }
    }

    public static function cryptMethod1Md5($value, $key)
    {
        $result = '';

        for ($i = 0; $i < mb_strlen($key); $i++) {
            $result = $result . $value;
        }

        for ($i = 0; $i < floor(mb_strlen($key) / 2); $i++) {
            $result = str_replace(mb_substr($key, $i, 1), mb_substr($key, $i + floor(mb_strlen
                ($key) / 2), 1), $result);
        }

        //echo $result . '|||||||';

        return md5($result);
    }

    public static function cryptMethod2Md5($value, $key)
    {
        $result = '';

        for ($i = 0; $i < mb_strlen($value) && $i < mb_strlen($key); $i++) {
            $char1 = mb_substr($value, $i, 1);
            $char2 = mb_substr($key, $i, 1);

            if ($i % 2 == '0') {
                $result = $result . $char1 . $char2;
            } else {
                $result = $result . $char2 . $char1;
            }
        }

        //echo $result . '|||||||';

        return md5($result);
    }

    public static function cryptMethod3Md5($value, $key)
    {
        $result = '';

        $result = $value . (mb_substr(strrev($key), 0, -1)) . (mb_substr(strrev($value),
            0, -1)) . $key;

        //echo $result . '|||||||';

        return md5($result);
    }

    public static function cryptMethod4Md5($value, $key)
    {
        $result = '';

        for ($i = 0; $i < mb_strlen($value); $i++) {
            $char = mb_substr($value, $i, 1);

            $result = $result . $char;

            if ($i % 3 == '0') {
                $result = $result . $key;
            }
        }

        //echo $result . '|||||||';

        return md5($result);
    }

    public static function cryptMethod5Md5($value, $key)
    {
        $result = '';

        $result = $value;

        for ($i = 0; $i < mb_strlen($key); $i++) {
            $char = mb_substr($key, $i, 1);

            if (mb_strpos($value, $char) === false) {
                $result = $result . $char;
            } else {
                $result = $char . $result;
            }
        }

        //echo $result . '|||||||';

        return md5($result);
    }

    public static function cryptMd5($value, $key)
    {
        $result = '';

        $result .= $value;
        $result .= $key;

        return md5($result);
    }

    public static function prepareName($value)
    {
        $value = preg_replace("#[^a-zA-Z0-9АаБбВвГгҐґДдЕеЄєЭэЖжЗзИиІіЇїЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЮюЯяЬьЪъёЫы_\-.\s/]#",
            ' ', $value);

        $value = preg_replace("#\s{2,}#", ' ', $value);

        return $value;
    }

    public static function translit($s, $del = '_')
    {
        $s = (string )$s; // преобразуем в строковое значение

        $s = mb_convert_encoding($s, "UTF-8");

        $s = strip_tags($s); // убираем HTML-теги
        $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
        $s = trim($s); // убираем пробелы в начале и конце строки
        $s = function_exists('mb_strtolower') ? mb_strtolower($s):
        strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
        $s = strtr($s, array(
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'e',
            'ж' => 'zh',
            'з' => 'z',
            'и' => 'i',
            'й' => 'y',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'h',
            'ц' => 'c',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'shch',
            'ы' => 'y',
            'э' => 'e',
            'ю' => 'yu',
            'я' => 'ya',
            'ъ' => '',
            'ь' => '',
            "і" => "i",
            "ї" => "i",
            "є" => "ie"));

        $s = preg_replace("/[^0-9a-z]/i", $del, $s); // очищаем строку от недопустимых символов

        $s = preg_replace("#" . ($del == '.' ? '\.' : $del) . "{2,}#", $del, $s);

        $s = trim($s, $del);

        return $s; // возвращаем результат
    }

    public static function saveEventImage($new_filename, $path_tmp_image, $path_old_image = null,
        $flag_del_tmp_img = true, $extension = null)
    {
        $result = [];

        if (file_exists($path_tmp_image)) {
            if (!$extension) {
                $pathinfo = pathinfo($path_tmp_image);

                if (isset($pathinfo['extension'])) {
                    if ($pathinfo['extension'] == 'jpeg' || $pathinfo['extension'] == 'jpg' || $pathinfo['extension'] ==
                        'png' || $pathinfo['extension'] == 'gif') {

                        $extension = $pathinfo['extension'];
                    }
                }
            }

            if ($extension && ($extension == 'jpeg' || $extension == 'jpg' || $extension ==
                'png' || $extension == 'gif')) {

                if (!is_dir(public_path() . '/images' . date('/Y/m/d/'))) {
                    mkdir(public_path() . '/images' . date('/Y/m/d/'), 0777, true);
                }

                $path_new_file_original = '/images' . date('/Y/m/d/') . $new_filename . '.' . $extension;
                $path_new_file_medium = '/images' . date('/Y/m/d/') . $new_filename . '-medium.' .
                    $extension;
                $path_new_file_small = '/images' . date('/Y/m/d/') . $new_filename . '-small.' .
                    $extension;

                list($width_orig, $height_orig, $imageType) = getimagesize($path_tmp_image);

                $sourceImage = null;

                switch ($imageType) {
                    case 1:
                        $sourceImage = imagecreatefromgif($path_tmp_image);
                        break;
                    case 2:
                        $sourceImage = imagecreatefromjpeg($path_tmp_image);
                        break;
                    case 3:
                        $sourceImage = imagecreatefrompng($path_tmp_image);
                        break;
                }

                if ($sourceImage) {
                    switch ($imageType) {
                        case 1:
                            if (imagegif($sourceImage, public_path() . $path_new_file_original)) {
                                $result['original'] = $path_new_file_original;
                            }

                            break;
                        case 2:
                            if (imagejpeg($sourceImage, public_path() . $path_new_file_original, 100)) {
                                $result['original'] = $path_new_file_original;
                            }

                            break;
                        case 3:
                            if (imagepng($sourceImage, public_path() . $path_new_file_original, 0)) {
                                $result['original'] = $path_new_file_original;
                            }

                            break;
                    }

                    $ratio_orig = $width_orig / $height_orig;

                    if ($width_orig > 450) {
                        $new_width = 450;
                        $new_height = ceil($new_width / $ratio_orig);

                        $destinationImage = imagecreatetruecolor($new_width, $new_height);

                        imagecopyresampled($destinationImage, $sourceImage, 0, 0, 0, 0, $new_width, $new_height,
                            $width_orig, $height_orig);

                        if ($destinationImage) {
                            switch ($imageType) {
                                case 1:
                                    if (imagegif($destinationImage, public_path() . $path_new_file_medium)) {
                                        $result['medium'] = $path_new_file_medium;
                                    }

                                    break;
                                case 2:
                                    if (imagejpeg($destinationImage, public_path() . $path_new_file_medium, 100)) {
                                        $result['medium'] = $path_new_file_medium;
                                    }

                                    break;
                                case 3:
                                    if (imagepng($destinationImage, public_path() . $path_new_file_medium, 0)) {
                                        $result['medium'] = $path_new_file_medium;
                                    }

                                    break;
                            }

                            imagedestroy($destinationImage);
                        }
                    } elseif (isset($result['original'])) {
                        $result['medium'] = $result['original'];
                    }

                    if ($width_orig > 100) {
                        $new_width = 100;
                        $new_height = ceil($new_width / $ratio_orig);

                        $destinationImage = imagecreatetruecolor($new_width, $new_height);

                        imagecopyresampled($destinationImage, $sourceImage, 0, 0, 0, 0, $new_width, $new_height,
                            $width_orig, $height_orig);

                        if ($destinationImage) {
                            switch ($imageType) {
                                case 1:
                                    if (imagegif($destinationImage, public_path() . $path_new_file_small)) {
                                        $result['small'] = $path_new_file_small;
                                    }

                                    break;
                                case 2:
                                    if (imagejpeg($destinationImage, public_path() . $path_new_file_small, 100)) {
                                        $result['small'] = $path_new_file_small;
                                    }

                                    break;
                                case 3:
                                    if (imagepng($destinationImage, public_path() . $path_new_file_small, 0)) {
                                        $result['small'] = $path_new_file_small;
                                    }

                                    break;
                            }

                            imagedestroy($destinationImage);
                        }
                    } elseif (isset($result['original'])) {
                        $result['small'] = $result['original'];
                    }

                    imagedestroy($sourceImage);
                }
            }

            if ($flag_del_tmp_img) {
                unlink($path_tmp_image);
            }
        }

        return $result;
    }
}
