<?php

//include_once($_SERVER['DOCUMENT_ROOT']."vendor/adodb/adodb.inc.php");

//include_once("../../vendor/adodb/adodb.inc.php");
include_once (ROOT . DS . 'vendor/adodb/adodb.inc.php');


class ADb {

    public $conn1 = null;
    public $debugging = true;

    public function __construct(){
        $mysql_settings = $GLOBALS['config']['mysql'];
        $driver = 'mysqli';

        $this->conn1 = newAdoConnection($driver);
        $this->conn1->connect($mysql_settings['host'],
            $mysql_settings['username'],
            $mysql_settings['password'],
            $mysql_settings['db']);

        $settings = [
            // Path to log directory
            'directory' => ROOT .'/logs',
            // // Log file name
            //'filename' => 'my-app-db.log',
            // Your timezone
            'timezone' => 'Asia/Jakarta',
            // Log level
            'level' => 'debug',
            // List of Monolog Handlers you wanna use
            // 'handlers' => [],
            ];

        // $this->logger = new \Projek\Slim\Monolog('slim-app', $settings);
            $this->logger = new \Projek\Slim\Monolog('slim-app', $settings);


    }
 //    function ADb()
    // {
 //        include "config_setting.php";
 //        $dbuser     = $config['master_dbuser'];
 //        $dbpass     = $config['master_dbpass'];
 //        $dbserver   = $config['db3_host'];
 //        $database   = $config['master_db'];

 //        $dbuser     = $dbuser;
 //        $dbpass     = $dbpass;
 //        $dbserver   = $dbserver;
    //  $database   = $database;

    //  $this->conn1 = &ADONewConnection('mysql'); 
    //  #$this->dPrint("PConnect($dbserver, $dbuser, $dbpass, $database)");
    //  $this->conn1->PConnect($dbserver, $dbuser, $dbpass, $database);
 //    }




    function query($sql){


        if ($this->debugging){
            $this->logger->info("Query string: " . $sql);
            $Result = $this->conn1->Execute($sql);
            return $Result;

        } else {
            $Result = $this->conn1->Execute($sql);
            return $Result;
        }

     }



    function ExecuteQuery($sql){
        return $this->query($sql);
    }




    function Execute($sql){
        return $this->query($sql);
    }

    function currentDbDate(){
            //global $myADb;
            $strSQL="select curdate()";
            $Result = $this->conn1->Execute($strSQL);
            
            return  $Result->fields[0];
    }

    function getCount($table, $where=''){


        $sql = "select count(*) from " . $table;
        if ($where != ''){ $sql .= " where " . $where; }
        $Result = $this->conn1->Execute($sql);        
        if(!$Result->EOF)
            $count = $Result->fields[0];
        else
            $count = 0;
        return $count;
    }


    function getDatabaseDetails(){
//      global $database;
        $mysql_settings = $GLOBALS['config']['mysql'];
        $result = $this->query("show table status from " . $mysql_settings['db']);
        $size = 0;
        $oh = 0;
        if ($result){
            while( $row = $this->fetch_array($result)){
                $size += $row['Data_length'] + $row['Index_length'];
                $oh += $row['Data_free'];
            }
        }
        $ret['size'] = number_format($size/1048576, 2) . " MB";
        $ret['overhead'] = $oh . " bytes";
        return $ret;
    }
}
?>
