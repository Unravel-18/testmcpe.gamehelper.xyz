<?php

namespace App\Helpers;

use \Storage;
use DB;

class Setting
{
    private static $values = null;

    public static function value()
    {
        $args = func_get_args();
        
        if (is_null(self::$values)) {
            self::$values = [];
            
            foreach(DB::table('settings')->get() as $key => $value) {
            //foreach(DB::table('settings')->whereRaw('`index` is null')->orWhere('index', '=', '')->get() as $key => $value) {
                self::$values[$value->key] = json_decode($value->value);
            }
        }

        switch (count($args)) {
            case '1':
                /*
                if (!array_key_exists($args[0], self::$values)) {
                    self::$values[$args[0]] = null;
                    
                    $obj = DB::table('settings')->where('key', '=', $args[0])->first();
                    
                    if ($obj) {
                        self::$values[$args[0]] = json_decode($obj->value);
                    }
                }
                */
                
                if (isset(self::$values[$args[0]])) {
                    return self::$values[$args[0]];
                }
                
                break;
            case '2':
                self::$values[$args[0]] = $args[1];
                
                if (DB::table('settings')->where('key', '=', $args[0])->count() > 0) {
                     DB::table('settings')
                        ->where('key', '=', $args[0])
                        ->update(['value' => json_encode($args[1])]);
                } else {
                    DB::table('settings')
                        ->insert(['key' => $args[0], 'value' => json_encode($args[1])]);
                }
                
                break;
        }
        
        return null;
    }
}
