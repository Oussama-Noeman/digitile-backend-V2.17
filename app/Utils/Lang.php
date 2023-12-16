<?php

namespace App\Utils;
class Lang{
    public static function get_name($name,string $lang){
     if($lang){
          if($lang == "en"){
           return $name['en'];
          }
       return $name['ar'];
     }
    }
}