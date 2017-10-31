<?php
class config{
	public static function getDbCredentials(){
		$credentials = array();
		$credentials = $GLOBALS['config']['mysql'];
		return $credentials;
	}
}
?>

