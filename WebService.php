<?php 
$class = $_REQUEST['class'];
$method = $_REQUEST['method'];
if( !$class OR ! $method OR !file_exists("app/services/{$class}.class.php") )
{
       die( "Permission denied!" );
}

require_once 'init.php';
require_once "app/services/{$class}.class.php";

$class::$method($_REQUEST);

?>