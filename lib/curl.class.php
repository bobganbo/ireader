<?php
/**
 * @name xxx
 * Created by PhpStorm.
 * User: bo
 * Date: 2018/3/3
 * Time: 20:08
 */
class Curl{
    private static function makeHttpReq($url, $params = array(),$headers=array(),$method='GET',$expire = 0, $extend = array(), $hostIp = ''){
        if (empty($url)){
            return array('code'=>'100');
        }
        if ($method == 'GET' && count($params) > 0 ){
            if(strpos($url, '?') == false){
                $url .= "?" . urldecode(http_build_query($params));//http_build_query会对数组中的中文参数urlencode
            }
            else{
                $url .= "&" . urldecode(http_build_query($params));
            }

        }
        $t = microtime(true);
        $_curl = curl_init();
        $_header = array(
            'Accept-Language: zh-cn',
            'Connection: Keep-Alive',
            'Cache-Control: no-cache',
            'REMOTE_ADDR: 121.14.45.137'
        );
        if(is_array($_header)&&!empty($headers)){
            foreach($headers as $k=>$v){
                $_header[]=$v;
            }
        }
        // 方便直接访问要设置host的地址
        if (!empty($hostIp)) {
            /**
             * 解析url成关联数组，如http://username:password@hostname/path?arg=value#anchor，解析后
             * Array
            (
            [scheme] => http
            [host] => hostname
            [user] => username
            [pass] => password
            [path] => /path
            [query] => arg=value
            [fragment] => anchor
            )
             */
            $urlInfo = parse_url($url);
            if (empty($urlInfo['host'])) {
                $urlInfo['host'] = substr(DOMAIN, 7, -1);
                $url = "http://{$hostIp}{$url}";
            } else {
                $url = str_replace($urlInfo['host'], $hostIp, $url);//把域名替换成IP
            }
            $_header[] = "Host: {$urlInfo['host']}";
        }

        // 只要第二个参数传了值之后，就是POST的
        if ($method == 'POST'){
            curl_setopt($_curl, CURLOPT_POSTFIELDS, $params);
            curl_setopt($_curl, CURLOPT_POST, true);
        }
        if (substr($url, 0, 8) == 'https://') {
            curl_setopt($_curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($_curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }

        curl_setopt($_curl, CURLOPT_URL, $url);//设置要请求的url
        curl_setopt($_curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($_curl, CURLOPT_USERAGENT, 'API PHP Servert 0.5 (curl) ');
        curl_setopt($_curl, CURLOPT_HTTPHEADER, $_header);//设置http请求头

        if ($expire>0){
            curl_setopt($_curl, CURLOPT_TIMEOUT, $expire);  // 处理超时时间
            curl_setopt($_curl, CURLOPT_CONNECTTIMEOUT, $expire);  // 建立连接超时时间
        }

        // 额外的配置
        if(!empty($extend)){
            curl_setopt_array($_curl, $extend);
        }

        $result['result'] = curl_exec($_curl);
        $result['code']  = curl_getinfo($_curl, CURLINFO_HTTP_CODE);//http请求状态码
        if ($result['result']===false)
        {
            $result['result'] = curl_error($_curl);
            $result['code']  = curl_errno($_curl);
        }
        curl_close($_curl);
        $t=round(microtime(true) - $t, 3);//保留3位小数
        return $result;

    }
    public static function post($url, $params = array(),$headers=array(),$expire = 10, $extend = array(), $hostIp = ''){
        return self::makeHttpReq($url, $params,$headers,'POST',$expire,$extend,$hostIp );
    }

    public static function get($url, $params = array(),$headers=array(),$expire = 10, $extend = array(), $hostIp = ''){
        return self::makeHttpReq($url, $params,$headers,'POST',$expire,$extend,$hostIp );
    }

    /**
     * post xml数据
     * @param $url 请求url
     * @param $xml 请求的xml格式数据
     * @param int $time_out 超时时间
     * @return mixed
     */
    public static function curlXml($url, $xml, $time_out=10){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $time_out);
        curl_setopt($ch, CURLOPT_TIMEOUT, $time_out);
        curl_setopt($ch, CURLOPT_USERAGENT, 'API PHP Servert 0.2 (curl_xml) ');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Accept-Language:UTF-8;',
                'Content-Type:text/xml;charset=utf-8'
            )
        );
        $return['result'] = curl_exec($ch);
        $return['code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);//http请求状态码,成功返回200
        curl_close($ch);
        unset($ch);
        return $return;
    }
}