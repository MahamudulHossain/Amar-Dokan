<?php
session_start();
include('function.inc.php');
unset($_SESSION['IS_LOGIN']);
unset($_SESSION['name']);
redirect('login.php');
?>
