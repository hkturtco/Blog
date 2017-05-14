<?php
// Turn on Debug mode
// error_reporting(E_ALL);
// ini_set("display_errors","On");

ob_start();
ini_set('session.cookie_httponly', true);
session_start();
session_regenerate_id(true);

//database credentials
define('DBHOST', 'localhost');
define('DBNAME', '');
define('DBUSER', '');
define('DBPASS', '');

$db = new PDO("mysql:host=".DBHOST.";port=3306;dbname=".DBNAME, DBUSER, DBPASS);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//set timezone
date_default_timezone_set('Asia/Hong_Kong');

//load classes as needed
function __autoload($class) {

	$class = strtolower($class);
	$classpath =  'classes/class.'.$class . '.php';

	if(file_exists($classpath)){
		require_once $classpath;
	}

	$classpath =  '../classes/class.'.$class . '.php';

	if(file_exists($classpath)){
		require_once $classpath;
	}

	$classpath =  '../../classes/class.'.$class . '.php';

	if(file_exists($classpath)){
		require_once $classpath;
	}

}

$user = new User($db);
$blog = new Blog($db);
?>