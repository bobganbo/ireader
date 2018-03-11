<?php
/**
 * @name xxx
 * Created by PhpStorm.
 * User: bo
 * Date: 2017/10/5
 * Time: 11:48
 */

//是否运行生产模式
define('RUN_MODE','pro');

define('ROOT',__DIR__);
define('CONFIG',ROOT.'/config');
define('LIB',ROOT.'/lib');
define('MODEL',ROOT.'/model');
define('CRAW',ROOT.'/craw');
define('CONTENT',ROOT.'/content');

define('SITE_URL','http://gaobiezhai.applinzi.com');



include_once LIB.'/myredis.class.php';
include_once LIB.'/tokens.class.php';
include_once LIB.'/curl.class.php';
include_once MODEL.'/sign.model.php';
include_once MODEL.'/wechat.model.php';

include_once CRAW.'/crawbase.class.php';
include_once LIB.'/mysql.class.php';

include_once CRAW.'/crawkqiwen.class.php';

if (!isset($_GET['echostr'])) {
    Wechat::responseMsg();
}else{
    Sign::valid();
}
