<?php
session_start();
include('constant.inc.php');
include('database.inc.php');
include('function.inc.php');

$type = get_safe_value($_POST['type']);
$added_on=date('Y-m-d h:i:s');

if($type == 'Register'){
  $username = get_safe_value($_POST['username']);
  $email = get_safe_value($_POST['email']);
  $mobile = get_safe_value($_POST['mobile']);
  $password = mysqli_real_escape_string($con,md5($_POST['password']));
  $check = mysqli_num_rows(mysqli_query($con,"select * from user where email='$email'"));
  if($check>0){
    $arr = array('status'=>'register_error','msg'=>'Email id already exists in the database','field'=>'email_error');
  }else{
    $random_string = rand_str();
    $subject = "Verify your Email";
    $content = SITE_PATH."Amar_Dokan/verify.php?verify_id=".$random_string;
    mysqli_query($con,"insert into user(username,email,mobile,password,status,verify,added_on,rand_str) values('$username','$email','$mobile','$password',1,0,'$added_on','$random_string') ");

    send_email($email,$subject,$content);
    $arr =array('status'=>'register_success','msg'=>'Thank you. Please check your email to verify your account','field'=>'email_success');
  }
  echo json_encode($arr);
}


if($type=='login'){
	$email=get_safe_value($_POST['user_email']);
  $password = mysqli_real_escape_string($con,md5($_POST['user_password']));

	$res=mysqli_query($con,"select * from user where email='$email' and password='$password'");
	$check=mysqli_num_rows($res);
	if($check>0){
		$row=mysqli_fetch_assoc($res);
		$status=$row['status'];
		$email_verify=$row['verify'];
		if($email_verify==1){
			if($status==1){
					$_SESSION['USER_ID']=$row['id'];
					$_SESSION['USER_NAME']=$row['username'];
          $arr=array('status'=>'login_success','msg'=>'');
          if(isset($_SESSION['cart']) && count($_SESSION['cart'])>0){
            foreach ($_SESSION['cart'] as $key => $value) {
              addTocart($_SESSION['USER_ID'],$value['qty'],$key);
            }
          }
				}else{
				$arr=array('status'=>'login_error','msg'=>'Your account has been deactivated.');
			}
		}else{
			$arr=array('status'=>'login_error','msg'=>'Please verify your email id');
		}
	}else{
		$arr=array('status'=>'login_error','msg'=>'Please enter valid login details');
	}
	echo json_encode($arr);
}


if($type=='forgot'){
	$email=get_safe_value($_POST['user_email_forgot']);

	$res=mysqli_query($con,"select * from user where email='$email'");
	$check=mysqli_num_rows($res);
	if($check>0){
		$row=mysqli_fetch_assoc($res);
		$status=$row['status'];
		$email_verify=$row['verify'];
		if($email_verify==1){
			if($status==1){
        $random_string = rand_str();
        $subject = "Your New Password";
        $content = "NEW PASSWORD IS : ".$random_string;
        $password = mysqli_real_escape_string($con,md5($random_string));
        mysqli_query($con,"update user set password='$password' where email='$email' ");
        send_email($email,$subject,$content);
        $arr =array('status'=>'login_success','msg'=>'A new password has been sent in your email Id');
				}else{
				$arr=array('status'=>'login_error','msg'=>'Your account has been deactivated.');
			}
		}else{
			$arr=array('status'=>'login_error','msg'=>'Please verify your email id');
		}
	}else{
		$arr=array('status'=>'login_error','msg'=>'Please enter valid Email Id');
	}
	echo json_encode($arr);
}



 ?>
