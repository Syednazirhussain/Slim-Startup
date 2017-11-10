<?php
class config{

	public static function getDbCredentials(){
		$credentials = array();
		$credentials = $GLOBALS['config']['mysql'];
		return $credentials;
	}

	public static function getValidApiKeys(){
		$keys = array(
			'3fb542058bffb60d31cf14db5dcd144c',
			'c0660685ba6826b826528c7ebc898abd',
			'a466d335298c5ba6f77246a6b94d4766'
		);
		return $keys;
	}
	
}
?>

