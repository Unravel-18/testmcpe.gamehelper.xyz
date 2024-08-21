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

class MainController extends Controller
{
    public function index()
    {
        return $this->view('main.index');
    }

    public function file(Request $request)
    {
        $dir = public_path() . '/apifiles/';
        
        if (file_exists($dir.$request->api_shortcode.'/'.$request->file)) {
            $pathinfo = pathinfo($dir.$request->api_shortcode.'/'.$request->file);
            
            if (isset($pathinfo['filename'])) {
                $tmp = explode("-", $pathinfo['filename']);
                
                if (count($tmp) == 2 && is_numeric($tmp[1])) {
                    $item = Skin::where('id', '=', $tmp[1])->first();
                    
                    if ($item) {
                        $item->downloads = intval($item->downloads) + 1;
                        
                        $item->save();
                    }
                }
            }
            
            // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
            // если этого не сделать файл будет читаться в память полностью!
            if (ob_get_level()) {
                ob_end_clean();
            }
            // заставляем браузер показать окно сохранения файла
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($dir.$request->api_shortcode.'/'.$request->file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($dir.$request->api_shortcode.'/'.$request->file));
            // читаем файл и отправляем его пользователю
            if ($fd = fopen($dir.$request->api_shortcode.'/'.$request->file, 'rb')) {
                while (!feof($fd)) {
                    print fread($fd, 1024);
                }
                fclose($fd);
            }

            exit;
        }
        
        abort(404);
    }

    public function adminfile(Request $request)
    {
        $dir = public_path() . '/apifiles/';
        
        if (file_exists($dir.$request->api_shortcode.'/'.$request->file)) {
            // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
            // если этого не сделать файл будет читаться в память полностью!
            if (ob_get_level()) {
                ob_end_clean();
            }
            // заставляем браузер показать окно сохранения файла
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($dir.$request->api_shortcode.'/'.$request->file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($dir.$request->api_shortcode.'/'.$request->file));
            // читаем файл и отправляем его пользователю
            if ($fd = fopen($dir.$request->api_shortcode.'/'.$request->file, 'rb')) {
                while (!feof($fd)) {
                    print fread($fd, 1024);
                }
                fclose($fd);
            }

            exit;
        }
        
        abort(404);
    }

    public function skinfile(Request $request)
    {
        $item = Skin::where('id', '=', $request->skin_id)->first();
        
        if ($item) {
            if ($item->file_link) {
                if (stripos($item->file_link, "http://") === 0 || stripos($item->file_link, "https://") === 0) {
                    $item->downloads = intval($item->downloads) + 1;
                    $item->save();
                    
                    header("Location: " . $item->file_link);
                    exit;
                }
            }
        }
        
        abort(404);
    }
}
