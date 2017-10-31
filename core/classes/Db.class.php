<?php
#$Id: db.inc.php,v 1.3 2004/11/12 20:35:43 ryan Exp $
class Db {
    function Db(){
        // include "config_setting.php";
        // $dbuser     = $config['master_dbuser'];
        // $dbpass     = $config['master_dbpass'];
        // $dbserver   = $config['db3_host'];
        // $database   = $config['master_db'];

        // $dbuser     = 'root';
        // $dbpass     = '123';
        // $dbserver   = 'localhost';
        // $database   = 'didx';

        $dbuser     = DB_USER;
        $dbpass     = DB_PASSWORD;
        $dbserver   = DB_HOST;
        $database   = DB_NAME;

        @mysql_connect($dbserver, $dbuser, $dbpass,false,65536) or die("MySQL Connection Failed");
        @mysql_select_db("$database") or die("Could Not Select Database");
    }
    function query($sql){
        $result = mysql_query($sql) or die(mysql_error() . "<br>" . $sql);
        return $result;
    }
    function ExecuteQuery($sql){
        $result = mysql_query($sql) or die(mysql_error() . "<br>" . $sql);
        return $result;
	}
    function fetch($sql){
        $result = $this->fetch_row($this->query($sql));
        return $result;
    }
    function fetch_row($set){
        $result = mysql_fetch_row($set);
        return $result;

    }
    function fetch_array($set){
        $result = mysql_fetch_array($set);
        return $result;
    }
    function insert_id(){
        $id = mysql_insert_id();
        return $id;
    }
    function dbCall($field, $table="config", $limit=1){
        list($ret) = $this->fetch_row($this->query("select $field from $table limit $limit"));
        return $ret;
    }
    function msgCall($lang, $key){
		$sql="select ".$lang."_value from game_message where msg_key='$key'";
        list($ret) = $this->fetch_row($this->query($sql));
        return $ret;
    }
    function getCount($table, $where=''){
        $sql = "select count(*) from " . $table;
        if ($where != ''){ $sql .= " where " . $where; }
        list($count) = $this->fetch_row($this->query($sql));
        return $count;
    }
    function getDatabaseDetails(){
	    global $database;
	    $result = $this->query("show table status from $database");
        $size = 0;
        $oh = 0;
        if ($result){
            while( $row = $this->fetch_array($result)){
                $size += $row[Data_length] + $row[Index_length];
                $oh += $row[Data_free];
            }
        }
        $ret[size] = number_format($size/1048576, 2) . " MB";
        $ret[overhead] = $oh . " bytes";
        return $ret;
    }
}
?>