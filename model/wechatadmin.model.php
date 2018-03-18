<?php
/**
 * @name xxx
 * Created by PhpStorm.
 * User: bo
 * Date: 2018/3/3
 * Time: 20:21
 */
class WechatAdmin{

    const CREATE_MENU = 'https://api.weixin.qq.com/cgi-bin/menu/create';
    const QUERY_MENU = 'https://api.weixin.qq.com/cgi-bin/menu/get';
    const DEL_MENU = 'https://api.weixin.qq.com/cgi-bin/menu/delete';
    private $token = '';
    private $menus = '';


    public function __construct(){
        $wechat_config = include CONFIG.'/wechat.config.php';
        $this->token = Tokens::getAccessToken($wechat_config[RUN_MODE]['appid'],$wechat_config[RUN_MODE]['appsecret']);
        $this->menus = include CONFIG.'/menu.config.php';
    }

    public function createMenu(){
        if(empty($this->token) || empty($this->menus)){
           return false;
        }
        $url = self::CREATE_MENU."?access_token={$this->token}";
        $params = json_encode($this->menus,JSON_UNESCAPED_UNICODE);
        $result = Curl::post($url,$params);
        if(!empty($result['result'])){
            $data = json_decode($result['result'],true);
            if(empty($data['errcode'])){
                return true;
            }
        }
        return false;
    }

    public function queryMenu(){
        if(empty($this->token)){
            return false;
        }
        $url = self::QUERY_MENU."?access_token={$this->token}";
        $result = Curl::get($url);
        if(!empty($result['result'])) {
            $data = json_decode($result['result'], true);
            return $data;
        }
        return false;
    }

    public function delMenu(){
        if(empty($this->token)){
            return false;
        }
        $url = self::DEL_MENU."?access_token={$this->token}";
        $result = Curl::get($url);
        if(!empty($result['result'])){
            $data = json_decode($result['result'],true);
            if(empty($data['errcode'])){
                return true;
            }
        }
        return false;
    }
}