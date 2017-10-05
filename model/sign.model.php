<?php
/**
 * @name xxx
 * Created by PhpStorm.
 * User: bo
 * Date: 2017/10/5
 * Time: 17:06
 */

class Sign{

    const TOKEN = 'aireader';

    public static function valid(){
        $echoStr = $_GET["echostr"];
        if(self::checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    public static function checkSign(){
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = self::TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if($tmpStr == $signature){
            return true;
        }else{
            return false;
        }
    }
}