<?php
    //Copyright© 2011 Huzoor Bux Panhwar. All rights reserved.
    ob_start();
    // include_once "Const.inc.php";
    // include_once $INCLUDEPATH."/User.inc.php";
    // include_once $INCLUDEPATH."/UI.inc.php";

    function dPrint($str) { if($fDebug) echo "$str<br>\n";} 
    ini_set("session.cookie_domain", ".didx.net");
    $myUser = new User($fDebug);
    $myUI= new UI();
    $qryUID = $_COOKIE['vplc'];
    if(isset($_COOKIE['vplc']))
    {
        $qryUID = $_COOKIE['vplc'];

        setcookie('vplc', $qryUID, time()+(3600*24), '/','.didx.net');
    }
    
    $UserInfo = $myUser->getUIDByEncUIDMD($qryUID) ;
    //print_r($UserInfo);
    $UID = $UserInfo['UID'];
    $OID = $UserInfo['UID'];
    if ($UserInfo['UID'] == "")
    {
        mail('hb@supertec.com',"LogineError",$qryUID);
        $myUI->getSessionErrorMessage();
    }
?>