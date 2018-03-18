<?php
/**
 * 爬取基类
 * Created by PhpStorm.
 * User: bo
 * Date: 2018/3/4
 * Time: 14:12
 */

class CrawBase{
    protected static function loadParseLib(){
        if(!class_exists('simple_html_dom')){
            include LIB.'/simple_html_dom.php';
        }
    }

    protected static function saveContent($path,$title,$content){
        $dir = dirname($path);
        if(!is_dir($dir)){
            mkdir($dir,0755);
        }
        $html = self::buildHtml($title,$content);
        @file_put_contents($path,$html);
    }

    protected static function buildHtml($title,$content){
        $str = <<<HTML
<head><meta charset="UTF-8"><title>{$title}</title></head>
               <body>{$content}</body>
HTML;
return $str;


    }





}