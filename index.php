<?php
/**
 * @name xxx
 * Created by PhpStorm.
 * User: bo
 * Date: 2017/10/5
 * Time: 11:48
 */
include_once 'model/sign.model.php';
include_once 'model/wechat.model.php';
if (!isset($_GET['echostr'])) {
    Wechat::responseMsg();
}else{
    Sign::valid();
}
