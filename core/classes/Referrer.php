<?php

require "maxmind/geolocation.php";
require "maxmind/localTime.php";



class Referrer {

    private $_ip_address;
    private $_proxy_address;

//functions







    public function __construct($ip_address = null){
        if(!empty($ip_address)){
            $this->_ip_address = $ip_address;
        }	 else {
            $this->_ip_address = getenv("REMOTE_ADDR");
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $this->_proxy_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

    }


    public function get_geolocation() {
        return ip2c_geolocation($this->get_ipaddress());
    }


    public function get_ipaddress(){
        return $this->_ip_address;
    }


    public function get_isProxy() {
        if(isset($this->_proxy_address)){
            return $this->_proxy_address;
        }
    }


    public function get_country_TwoCodes(){
        return ip2c_geolocation($this->get_ipaddress())['countryCode2'];
    }

    public function get_country_ThreeCodes(){
        return ip2c_geolocation($this->get_ipaddress())['countryCode3'];
    }

    public function get_continent(){
        return ip2c_geolocation($this->get_ipaddress())['continentCode'];
    }

    public function get_continentName(){
        return ip2c_geolocation($this->get_ipaddress())['continentName'];
    }


    public function get_countryName(){
        return ip2c_geolocation($this->get_ipaddress())['countryName'];
    }

    public function get_regionName(){
        return ip2c_geolocation($this->get_ipaddress())['regionName'];
    }


    public function get_cityName(){
        return ip2c_geolocation($this->get_ipaddress())['cityName'];
    }



    public function get_cityLatitude(){
        return ip2c_geolocation($this->get_ipaddress())['cityLatitude'];
    }


    public function get_cityLongitude(){
        return ip2c_geolocation($this->get_ipaddress())['cityLongitude'];
    }


    public function get_countryLatitude(){
        return ip2c_geolocation($this->get_ipaddress())['countryLatitude'];
    }

    public function get_countryLongitude(){
        return ip2c_geolocation($this->get_ipaddress())['countryLongitude'];
    }


    public function get_TimeZone(){
        //die($this->get_country_TwoCodes() . $this->get_cityLongitude());a
        return 	ip2c_getTimeZone($this->get_country_TwoCodes(), $this->get_cityLongitude());
    }

    public function get_currentTime(){
        return ip2c_getLocalTime('America/New_York');
        //return ip2c_getLocalTime($this->get_TimeZone());
    }


    public function get_countryFlag(){
        return "flags/" .strtolower($this->get_country_TwoCodes()) . ".png";
    }

    public function get_referrerUrl(){
        $ref_url=explode('utmcsr=',$_COOKIE['__utmz']);
        return $ref_url[1];


    }


    public function get_referrerKeyword(){

        $ref_keyword=explode('utmctr=',$_COOKIE['__utmz']);
        return $ref_keyword[0];

    }

    public function get_deviceType(){



    }

    public function get_browserType(){

    }

    public function get_resolution(){


    }

}



?>