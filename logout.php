<?php
require_once 'config/class_login.php';
$login = new Login();
$login->logout();
header("Location: login.php");
exit;
?>