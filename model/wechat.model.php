<?php

/**
 * @name xxx
 * Created by PhpStorm.
 * User: bo
 * Date: 2017/10/5
 * Time: 17:12
 */
class Wechat
{
    public static function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)) {
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);
            switch ($RX_TYPE) {
                case "event":
                    $result = self::receiveEvent($postObj);
                    break;
                case "text":
                    $result = self::receiveText($postObj);
                    break;
                case "image":
                    $result = self::receiveImage($postObj);
                    break;
                case "location":
                    $result = self::receiveLocation($postObj);
                    break;
                case "voice":
                    $result = self::receiveVoice($postObj);
                    break;
                case "video":
                    $result = self::receiveVideo($postObj);
                    break;
                case "link":
                    $result = self::receiveLink($postObj);
                    break;
                default:
                    $result = "unknow msg type: " . $RX_TYPE;
                    break;
            }
            echo $result;
        } else {
            echo "";
            exit;
        }
    }

    private static function receiveEvent($object)
    {
        $content = "";
        switch ($object->Event) {
            case "subscribe":
                $content = "欢迎关注ireader ";
                $content .= (!empty($object->EventKey)) ? ("\n来自二维码场景 " . str_replace("qrscene_", "", $object->EventKey)) : "";
                break;
            case "unsubscribe":
                $content = "取消 关注";
                break;
            case "SCAN":
                $content = "扫描场景 " . $object->EventKey;
                break;
            case "VIEW":
                switch($object->EventKey){
                    case "111":
                        break;
                    case "222":
                        break;
                    default:
                        break;
                }
                break;
            case "CLICK":
                switch ($object->EventKey) {
                    case "COMPANY":
                        $content = "ireader提供相关产品与服务。";
                        break;
                    case "V1001_LEVEL_1":
                        //神指一戳
                        $content = Kqiwen::randomGetOne();
                        break;
                    default:
                        $content = "点击菜单：" . $object->EventKey;
                        break;
                }
                break;
            case "LOCATION":
                $content = "上传位置：纬度 " . $object->Latitude . ";经度 " . $object->Longitude;
                break;
            default:
                $content = "receive a new event: " . $object->Event;
                break;
        }
        if (is_array($content)) {
            if (isset($content[0]['PicUrl'])) {
                $result = self::transmitNews($object, $content);
            } else if (isset($content['MusicUrl'])) {
                $result = self::transmitMusic($object, $content);
            }
        } else {
            $result = self::transmitText($object, $content);
        }
        return $result;
    }

    private static function receiveText($object)
    {
        $keyword = trim($object->Content);
        switch ($keyword) {
            case "文本":
                $content = "这是个文本消息";
                break;
            case "图文":
            case "单图文":
                $content[] = array("Title" => "单图文标题", "Description" => "单图文内容", "PicUrl" => "http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" => "http://m.cnblogs.com/?u=txw1958");
                break;
            case "多图文":
                $content[] = array("Title" => "多图文1标题", "Description" => "", "PicUrl" => "http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" => "http://m.cnblogs.com/?u=txw1958");
                $content[] = array("Title" => "多图文2标题", "Description" => "", "PicUrl" => "http://d.hiphotos.bdimg.com/wisegame/pic/item/f3529822720e0cf3ac9f1ada0846f21fbe09aaa3.jpg", "Url" => "http://m.cnblogs.com/?u=txw1958");
                $content[] = array("Title" => "多图文3标题", "Description" => "", "PicUrl" => "http://g.hiphotos.bdimg.com/wisegame/pic/item/18cb0a46f21fbe090d338acc6a600c338644adfd.jpg", "Url" => "http://m.cnblogs.com/?u=txw1958");
                break;
            case "音乐":
                $content = array("Title" => "最炫民族风", "Description" => "歌手：凤凰传奇", "MusicUrl" => "http://www.w3school.com.cn/i/song.mp3", "HQMusicUrl" => "http://www.w3school.com.cn/i/song.mp3");
                break;
            default:
                $content = "当前时间：" . date("Y-m-d H:i:s", time());
                break;
        }
        if (is_array($content)) {
            if (isset($content[0]['PicUrl'])) {
                $result = self::transmitNews($object, $content);
            } else if (isset($content['MusicUrl'])) {
                $result = self::transmitMusic($object, $content);
            }
        } else {
            $result = self::transmitText($object, $content);
        }
        return $result;
    }

    private static function receiveImage($object)
    {
        $content = array("MediaId" => $object->MediaId);
        $result = self::transmitImage($object, $content);
        return $result;
    }

    private static function receiveLocation($object)
    {
        $content = "你发送的是位置，纬度为：" . $object->Location_X . "；经度为：" . $object->Location_Y . "；缩放级别为：" . $object->Scale . "；位置为：" . $object->Label;
        $result = self::transmitText($object, $content);
        return $result;
    }

    private static function receiveVoice($object)
    {
        if (empty($object->Recognition)) {
            $content = array("MediaId" => $object->MediaId);
            $result = self::transmitVoice($object, $content);
        } else {
            $content = "你刚才说的是：" . $object->Recognition;
            $result = self::transmitText($object, $content);
        }

        return $result;
    }

    private static function receiveVideo($object)
    {
        $content = array("MediaId" => $object->MediaId, "ThumbMediaId" => $object->ThumbMediaId, "Title" => "", "Description" => "");
        $result = self::transmitVideo($object, $content);
        return $result;
    }

    private static function receiveLink($object)
    {
        $content = "你发送的是链接，标题为：" . $object->Title . "；内容为：" . $object->Description . "；链接地址为：" . $object->Url;
        $result = self::transmitText($object, $content);
        return $result;
    }

    private static function transmitText($object, $content)
    {
        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }

    private static function transmitImage($object, $imageArray)
    {
        $itemTpl = "<Image>
    <MediaId><![CDATA[%s]]></MediaId>
</Image>";

        $item_str = sprintf($itemTpl, $imageArray['MediaId']);

        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[image]]></MsgType>
$item_str
</xml>";

        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    private static function transmitVoice($object, $voiceArray)
    {
        $itemTpl = "<Voice>
    <MediaId><![CDATA[%s]]></MediaId>
</Voice>";

        $item_str = sprintf($itemTpl, $voiceArray['MediaId']);

        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[voice]]></MsgType>
$item_str
</xml>";

        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    private static function transmitVideo($object, $videoArray)
    {
        $itemTpl = "<Video>
    <MediaId><![CDATA[%s]]></MediaId>
    <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
</Video>";

        $item_str = sprintf($itemTpl, $videoArray['MediaId'], $videoArray['ThumbMediaId'], $videoArray['Title'], $videoArray['Description']);

        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[video]]></MsgType>
$item_str
</xml>";

        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    private static function transmitNews($object, $newsArray)
    {
        if (!is_array($newsArray)) {
            return;
        }
        $itemTpl = "    <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
    </item>
";
        $item_str = "";
        foreach ($newsArray as $item) {
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $newsTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<Content><![CDATA[]]></Content>
<ArticleCount>%s</ArticleCount>
<Articles>
$item_str</Articles>
</xml>";

        $result = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }

    private static function transmitMusic($object, $musicArray)
    {
        $itemTpl = "<Music>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
    <MusicUrl><![CDATA[%s]]></MusicUrl>
    <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
</Music>";

        $item_str = sprintf($itemTpl, $musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);

        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[music]]></MsgType>
$item_str
</xml>";

        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }


}