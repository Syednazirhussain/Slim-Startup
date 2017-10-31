<?php

/** Database Configuration Variables **/

if($_SERVER["HTTP_HOST"] == "sandbox2.didx.net"){

   define ('DEVELOPMENT_ENVIRONMENT',false);
   define('DB_NAME', 'weddidx');
   define('DB_HOST', 'backup.didx.net');
   define('DB_USER', 'sandbox');
   define('DB_PASSWORD', 'hcRMnDfSz543QZa9sm5W');

} else {
	define ('DEVELOPMENT_ENVIRONMENT',true);
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    	//echo 'This is a server using Windows!';
		define('DB_HOST', 'localhost');
		define('DB_NAME', 'users');
		define('DB_PASSWORD', '');
		define('DB_USER', 'root');
	} else {
	   // echo 'This is a server not using Windows!';
		define('DB_HOST', 'localhost');
		define('DB_NAME', 'localdidx');
		define('DB_PASSWORD', '1');
		define('DB_USER', 'root');
	}


}
 
?>