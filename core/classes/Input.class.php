<?php

class Input
{
    public static function exists($type = 'post')
    {
        switch ($type) {
            case 'post':
                return (!empty($_POST)) ? true : false;
                break;

            case 'get':
                return (!empty($_GET)) ? true : false;

                break;

            default:
                return false;
                break;


        }
    }

    public static function get($item)
    {
        if (isset($_POST[$item])) {
            return $_POST[$item];
        } else if (isset($_GET[$item])) {
            return $_GET[$item];
        }

        return '';
    }

    public static function sanitize_string($string)
    {
        $new_string = filter_var($string, FILTER_SANITIZE_STRING);
        return $new_string;
    }

    public static function  sanitize($string)
    {

        //remove space bfore and after
        $string = trim($string); 
        //remove slashes
        $string = stripslashes($string);
        // remove strip_tags
        $string = strip_tags($string);

        $string= self::sanitize_string($string);

        return $string;


    }

}