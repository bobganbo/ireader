<?php
/**
 * @name xxx
 * Created by PhpStorm.
 * User: bo
 * Date: 2018/3/4
 * Time: 11:05
 */

//是否运行生产模式
define('RUN_MODE','test');

define('ROOT',__DIR__);
define('CONFIG',ROOT.'/config');
define('LIB',ROOT.'/lib');
define('MODEL',ROOT.'/model');

include_once LIB.'/myredis.class.php';
include_once LIB.'/tokens.class.php';
include_once LIB.'/curl.class.php';
include_once MODEL.'/wechatadmin.model.php';

$obj = new WechatAdmin();

$result = $obj->queryMenu();

if($result){
    echo "success";
}else{
    echo "fail";
}