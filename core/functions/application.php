<?php

if(!defined('isIncludedTrue')) {
    die('Direct access not permitted');
}

function setReporting() {
if (DEVELOPMENT_ENVIRONMENT == true) {
    error_reporting(E_ALL);
    ini_set('display_errors','On');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors','Off');
    ini_set('log_errors', 'On');
    //ini_set('error_log', ROOT.DS.'tmp'.DS.'logs'.DS.'error.log');
}
}

function isUserLogin()
{
    if (isset($_SESSION['username'])) {
        return true;
    }
    return false;
}

function userAuthenticationRequired()
{
    if (!isUserLogin()) {
        $url = $GLOBALS['website_url'] . "/index.php";
        //header('Location: '.'index.php');
        echo $url;
        //exit;
        header('Location: ' . $url, true, $permanent ? 301 : 302);
    }
}

function logOutUser(){
    unset($_SESSION['username']);
    session_destroy();
    header('Location: '.'index.php');
}


function getUserID($encrptedUID){
   return encryptor('decrypt', $encrptedUID);
}

function currentUser(){
    if (isUserLogin()){
        return getUserID($_SESSION['username']);
    }
    return nil;
}

function isBuyer(){
    $CusType = getCustomerType();
    if($CusType != "nil" && $CusType == "Buyer"){
        return true;
    }
    return false;
}

function isSeller(){
    $CusType = getCustomerType();
    if($CusType != "nil" && $CusType == "Seller"){
        return true;
    }
    return false;
}

function getCustomerType(){
    if (isUserLogin()){

        if (isset($_SESSION['custype'])) {
            return $_SESSION['custype'];
        } else {

            $myOrders = new Orders();
            $UID = getUserID($_SESSION['username']);
            $Orders = $myOrders->getOrdersInfoByUID($UID);
            $CusType    = $Orders['CusType'];
            switch ($CusType) {
                case 0:
                    $_SESSION['custype'] = "Buyer";
                    return $_SESSION['custype'];
                    break;
                case 1:
                    $_SESSION['custype'] = "Seller";
                    return $_SESSION['custype'];
                    break;
                case 2:
                    $_SESSION['custype'] = "Trader";
                    return $_SESSION['custype'];
                    break;
                case 3:
                    $_SESSION['custype'] = "Exchange";
                    return $_SESSION['custype'];
                    break;
                default:
                    return nil;
            }

        }

    }
    return nil;
} 


?>
