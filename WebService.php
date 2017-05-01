<?php 
$class = $_REQUEST['class'];
$method = $_REQUEST['method'];
require_once 'init.php';
require_once "app/services/{$class}.class.php";

$class::$method($_REQUEST);

?>