<?php
session_start();
include('constant.inc.php');
include('database.inc.php');
include('function.inc.php');

$type = get_safe_value($_POST['type']);

if($type=='profile_update'){
  $name=get_safe_value($_POST['name']);
	$mobile=get_safe_value($_POST['mobile']);
  $uid = $_SESSION['USER_ID'];
	mysqli_query($con,"update user set username='$name',mobile='$mobile' where id='$uid'");

	$arr=array('status'=>'pro_success');
	echo json_encode($arr);
}

if($type=='password_update'){
  $old_password = mysqli_real_escape_string($con,md5($_POST['old_password']));
  $new_password = mysqli_real_escape_string($con,md5($_POST['new_password']));
  $uid = $_SESSION['USER_ID'];
  $res = mysqli_query($con,"select password from user where id='$uid'");
  $data = mysqli_fetch_assoc($res);
  $check = $data['password'];
  if($check == $old_password){
    mysqli_query($con,"update user set password='$new_password' where id='$uid'");
    $arr=array('status'=>'pass_success');
  }else{
    $arr=array('status'=>'pass_fail','msg'=>'Please Enter Correct Password');
  }
  echo json_encode($arr);
}





 ?>
