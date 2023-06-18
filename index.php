<?php
session_start(); 
session_unset();
$_SESSION["playlist"] = null;
$_SESSION["error"] = null;
require('vue/home.php'); 
?>