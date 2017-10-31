<?php
// Load Slim Settings file
ini_set('max_execution_time', 300);
ini_set('mysql.connect_timeout', 300);
ini_set('default_socket_timeout', 300); 


require_once (ROOT . DS . 'config' . DS . 'slim_config.php');
// Database Settings
require_once (ROOT . DS . 'config' . DS . 'database_config.php');


if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
    // this is HTTPS
    $protocol  = "https";
} else {
    // this is HTTP
    $protocol  = "http";
}

$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => DB_HOST,
        'username' => DB_USER,
        'password' => DB_PASSWORD,
        'db' => DB_NAME),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800
    ),
        'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'),
        'domain' => array(
            'website_url' => $protocol."://".$_SERVER["HTTP_HOST"],
            'website_title' => 'DIDx exchange for DID buying and selling' )
);



require_once (ROOT . DS . 'config' . DS . 'constants_config.php');


// load core files 
// this will load all our custom class files 
require_once (ROOT . DS . 'core' . DS . 'init.php');



?>