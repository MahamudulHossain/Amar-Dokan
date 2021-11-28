<?php
session_start();
include('../admin/function.inc.php');
unset($_SESSION['DELIVARY_BOY']);
unset($_SESSION['DELIVARY_ID']);
unset($_SESSION['IS_DELIVARY_BOY_LOGIN']);
redirect('login.php');
?>
