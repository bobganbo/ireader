<?php
/**
 * http://www.jb51.net/article/89771.htm 参考文档
 * 微信公众号之二维码
 * Created by PhpStorm.
 * User: bo
 * Date: 2018/3/11
 * Time: 20:11
 */
class WechatQrcode{
    //二维码创建,

    //POST数据： {"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 订单id}}}
    //返回值：{"ticket":"","expire_seconds":60,"url":"http:\/\/weixin.qq.com\/q\/kZgfwMTm72WWPkovabbI"}
    const QRCODE_CREATE = 'https://api.weixin.qq.com/cgi-bin/qrcode/create';

    const QRCODE_SHOW   = 'https://mp.weixin.qq.com/cgi-bin/showqrcode';













}