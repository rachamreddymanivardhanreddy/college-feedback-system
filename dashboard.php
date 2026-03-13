<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if(!isset($_SESSION['role'])){
    header("Location: admin_login.php");
    exit();
}

if($_SESSION['role'] == 'admin'){
    header("Location: admin_dashboard.php");
    exit();
}

if($_SESSION['role'] == 'hod'){
    header("Location: hod_dashboard.php");
    exit();
}
?>