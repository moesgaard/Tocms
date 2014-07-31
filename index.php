<?php
session_start();
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
//we define a few things TOCMS  is currently set to setup so we can build the setup ofg the system 
define("TOCMS",'setup');
define('lang','en');
global $db,$html,$theme,$base,$user;
//We load our custom error logging for the exceptions (mostly database related errors). 
require_once('core/error_class.php');
require_once('core/base_class.php');
require_once('core/database_class.php');
$db = new database_class();
//we load our system fully through our src folder  
require_once('src/index.php');
// we call the function tocms to trigger the system.
tocms();
?>
