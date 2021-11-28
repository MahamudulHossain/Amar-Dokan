<?php
session_start();
include('constant.inc.php');
include('database.inc.php');
include('function.inc.php');
  $cupon_code = $_POST['cupon_code'];

	$res=mysqli_query($con,"select * from cupon_code where cupon_code='$cupon_code' and status=1 ");
	$check=mysqli_num_rows($res);
  $getCartTotalPrice=0;
	if($check>0){
  		$row=mysqli_fetch_assoc($res);
      $cupon_type=$row['cupon_type'];
      $cupon_value=$row['cupon_value'];
      $cart_min_value=$row['cart_min_value'];
      $expired_on=strtotime($row['expired_on']);
      $cur_date=strtotime("now");
      $getCartTotalPrice = getCartTotalPrice();
      if($getCartTotalPrice > $cart_min_value){
        if($cur_date > $expired_on){
          $arr =array('status'=>'error','msg'=>'Coupon Code Expired');
        }else{
          $final_pay = 0;
          if($cupon_type == "F"){
            $final_pay = $getCartTotalPrice - $cupon_value;
          }
          if($cupon_type == "P"){
            $final_pay = $getCartTotalPrice - ($getCartTotalPrice*$cupon_value)/100;
          }
          $_SESSION['COUPON_CODE'] = $cupon_code;
          $_SESSION['FINAL_PAY'] = $final_pay;
          $arr = array('status'=>'success','msg'=>'Coupon Applied Successfully','final_pay'=>$final_pay);
        }
    }else{
      $arr =array('status'=>'error','msg'=>'Cart value should be greater than '.$cart_min_value.' Tk.');
    }
  }else{
      $arr =array('status'=>'error','msg'=>'Enter Valid Coupon Code');
    }

echo json_encode($arr);


 ?>
