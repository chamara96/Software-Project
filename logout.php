<?php

session_start();
session_destroy();
$_SESSION=array();
setcookie('logging', $keepLog, time() - 60 * 10);
header('location:index.php');

?>
