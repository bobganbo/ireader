<?php
/**
 * @name xxx
 * Created by PhpStorm.
 * User: bo
 * Date: 2018/3/4
 * Time: 14:43
 */

//是否运行生产模式
define('RUN_MODE','test');

$source = isset($_GET['source']) && $_GET['source']?$_GET['source']:'kqiwen';
define('ROOT',__DIR__);
define('CONFIG',ROOT.'/config');
define('LIB',ROOT.'/lib');
define('CRAW',ROOT.'/craw');

define('CONTENT',ROOT.'/content');

define('SITE_URL','http://gaobiezhai.applinzi.com');

if(!is_file(CRAW.'/craw'.$source.'.class.php')){
    exit('unknow');
}
include_once CRAW.'/crawbase.class.php';

include_once CRAW.'/craw'.$source.'.class.php';

include_once LIB.'/mysql.class.php';

$source = ucfirst($source);

$source::crawList();

//$source::randomGetOne();

