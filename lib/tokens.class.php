<?php
/**
 * 微信公众号token工具
 * Created by PhpStorm.
 * User: bo
 * Date: 2018/3/3
 * Time: 19:20
 */
class Tokens{

    const TOKEN_URL = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential';

    const TOKEN_EXIPRE_TIME = 3600;

    /**
     * 获取accesstoken，被动刷新
     * @param $appid
     * @param $appsecret
     * @return bool
     */
    public static function getAccessToken($appid,$appsecret){
        if(empty($appid) || empty($appsecret)){
            return false;
        }
        /*
        $config_redis = include CONFIG.'/redis.config.php';
        $redis = MyRedis::getInstance($config_redis);
        $token_key = 'token@@wechat';
        $token = $redis->get($token_key);
        if(!empty($token)){
            return $token;
        }*/

        $url = self::TOKEN_URL."&appid={$appid}&secret={$appsecret}";
        $result = Curl::get($url);

        if(!empty($result['result'])){
            $data = json_decode($result['result'],true);
            if(!empty($data['access_token'])){
                //$redis->set($token_key,$data['access_token'],self::TOKEN_EXIPRE_TIME);
                return $data['access_token'];
            }else{
                //记录接口返回日志

            }
        }
        return false;
    }

    /**
     * 主动刷新accesstoken 定时任务每各1小时刷新一次
     * @param $appid
     * @param $appsecret
     * @return bool
     */
    public static function refreshToken($appid,$appsecret){
        return self::getAccessToken($appid,$appsecret);
    }










}