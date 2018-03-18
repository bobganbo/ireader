<?php
/**
 * 爬取看奇闻网的新闻
 * https://www.kqiwen.com/qirenyishi/
 * Created by PhpStorm.
 * User: bo
 * Date: 2018/3/4
 * Time: 13:18
 */
class Kqiwen extends CrawBase{

    const  SOURCE = 1;

    const site_url = 'https://www.kqiwen.com';

    const qiwenyishi_url = 'https://www.kqiwen.com/qirenyishi/';

    private static $craw_url = [
        'https://www.kqiwen.com/qirenyishi/',
        //'https://www.kqiwen.com/qirenyishi/list_7_2.html',
        //'https://www.kqiwen.com/qirenyishi/list_7_3.html'
    ];

    protected static function parseList($dom){
        // find all span tags with class=gb1
        foreach($dom->find('div.asan ul li h3 a') as $e){
            $uri = $e->getAttribute('href');
            $title = $e->getAttribute('title');
            $content_md5 = self::crawContent($uri,$title);
            $pic_url =  $e->parentNode()->nextSibling()->firstChild()->getAttribute('src');
            $profile =  $e->parentNode()->nextSibling()->nextSibling()->text();
            self::addCrawRecord($title,$pic_url,$content_md5,$profile);
        }
    }

    protected static function parseContent($dom,$title){
        foreach($dom->find('div.newsnr') as $e){
            $content = $e->childNodes(3)->outertext;
            $content_hash = md5($content);
            //写入对应的文件
            $path = CONTENT.'/kqiwen/'.date("Ymd").'/'.$content_hash.'.html';
            self::saveContent($path,$title,$content);
            return $content_hash;
        }
    }

    /**
     * 将爬取的记录信息写入数据库
     */
    public static function addCrawRecord($title,$pic_url,$content_md5,$profile){
        $ctime = time();
        $day = date("Ymd",$ctime);
        $source = self::SOURCE;
        $sql = "insert into t_grab_list (`source`,`day`,`title`,`profile`,`pic_url`,`content_md5`,`ctime`)
                values ('{$source}','{$day}','{$title}','{$profile}','{$pic_url}','{$content_md5}','{$ctime}')";
        $mysql = new Mysql();
        return $mysql->insert($sql);
    }

    public static function crawList(){
        self::loadParseLib();
        $urls = self::$craw_url;
        if(is_array($urls)){
            foreach($urls as $v){
                $dom = file_get_html($v);
                self::parseList($dom);
            }
        }else {
            $dom = file_get_html($urls);
            self::parseList($dom);
        }
    }

    public static function crawContent($urls,$title){
        self::loadParseLib();

        if(is_array($urls)){
            foreach($urls as $v){
                $dom = file_get_html(self::site_url.$v);
                return self::parseContent($dom,$title);
            }
        }else {
            $dom = file_get_html(self::site_url.$urls);
            return self::parseContent($dom,$title);
        }
    }



    public static function getContentUrl(){
        //拼接出来一个url地址

    }


    public static function getListByDate($date){


    }

    public static function randomGetOne(){
        $date = date("Ymd");
        $sql = "select * from t_grab_list where day = {$date} and source = ".self::SOURCE;
        $mysql = new Mysql();
        $result = $mysql->query($sql);
        $count = count($result);
        $random = rand(1,$count);
        $url =  SITE_URL."/content/kqiwen/{$date}/".$result[$random-1]['content_md5'].".html";
        $rtn[] = [
               "Title" => $result[$random-1]['title'],
               "Description" =>$result[$random-1]['profile'],
               "PicUrl" =>  $result[$random-1]['pic_url'],
               "Url" => $url
        ];
        return $rtn;
    }
}
