<?php
session_start();
$_SESSION['role'] = $_GET['role'];
header('location:dashboard_lecturer.php');
?>