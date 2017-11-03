<?php

define('SECRET_KEY', "fakesecretkey");
define('VALIDITY_TIME', 3600);

function createToken($data)
{
    $tokenGeneric = SECRET_KEY.$_SERVER["SERVER_NAME"];
    $token = hash('sha256', $tokenGeneric.$data);
    return array('token' => $token, 'userData' => $data);
}

function auth($username)
{

    // Concatenating data with TIME
    $data = time()."_".$username;
    $token = createToken($data);
    echo json_encode($token);
}



function checkToken($receivedToken, $receivedData)
{
    /* Recreate the generic part of token using secretKey and other stuff */
    $tokenGeneric = SECRET_KEY.$_SERVER["SERVER_NAME"];

    // We create a token which should match
    $token = hash('sha256', $tokenGeneric.$receivedData);

    // We check if token is ok !
    if ($receivedToken != $token)
    {
        echo 'wrong Token !';
        return false;
    }

    list($tokenDate, $userData) = explode("_", $receivedData);
    // here we compare tokenDate with current time using VALIDITY_TIME to check if the token is expired
    // if token expired we return false

    // otherwise it's ok and we return a new token
    return createToken(time()."#".$userData);
}




?>