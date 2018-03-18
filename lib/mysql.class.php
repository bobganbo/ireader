<?php
/**
 * @name xxx
 * Created by PhpStorm.
 * User: bo
 * Date: 2018/3/4
 * Time: 18:04
 */

class Mysql{

    //连接对象
    protected $conn = '';

    public function __construct() {
        $db_config = include CONFIG.'/db.config.php';
        $this->conn = mysql_connect($db_config[RUN_MODE]['mysql_host'], $db_config[RUN_MODE]['mysql_user'],
            $db_config[RUN_MODE]['mysql_pwd']);

        if (!$this->conn) die("error: mysql connect failed!");
        mysql_set_charset("utf8");
        mysql_select_db($db_config[RUN_MODE]['mysql_db'], $this->conn);
    }

    public function __destruct() {
        mysql_close($this->conn);
    }

    public function insert($sql){
        $result = mysql_query($sql);
        $id = mysql_insert_id();
        return $id;
    }

    public function update($sql){
        mysql_query($sql);
        $id = mysql_insert_id();
        return $id;
    }

    public function query($sql){
        $result = mysql_query($sql);
        $rtn = [];
        while($row = mysql_fetch_array($result))
        {
            $rtn[] = $row;
        }
        return $rtn;
    }

    public function delete($sql){
        return mysql_query($sql);
    }

    public function queryOne(){


    }

    public function queryAll(){

    }
}