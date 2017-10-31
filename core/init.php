<?php
// define isIncludedTrue variable for files should not run stand alone
if(!defined('isIncludedTrue')) {
    die('Sorry, Direct access not permitted');
}


function MyCoreClassesAutoload($className){

	$className = ucfirst($className);

	if (strpos($className, 'Controller') !== false) {
		//echo "controller == $className";
		require_once(ROOT . DS . 'app' . DS .'controllers' . DS . $className . '.php');
	} else {
		//echo "controller != $className";
		//require_once (ROOT . DS . 'core' . DS .'model' . DS . $className.'.class.php');
	}


}
// auto load core/model folder
spl_autoload_register('MyCoreClassesAutoload');

require_once (ROOT . DS . 'core' . DS .'functions' . DS . 'common.php');
require_once (ROOT . DS . 'core' . DS .'functions' . DS . 'application.php');



