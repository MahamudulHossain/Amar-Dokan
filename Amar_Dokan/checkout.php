<?php
include('header.php');
$websiteCloseStatus = websiteCloseStatus();
if($websiteCloseStatus['website_close'] == 1){
  redirect('shop.php');
}
$cartArr = getUserFullCart();
$totalCartProduct = count($cartArr);
if($totalCartProduct>0){

}else{
  redirect('shop.php');
}
if(isset($_SESSION['USER_ID'])){
	$is_show='';
	$box_id='';
	$final_show='show';
	$final_box_id='payment-2';
}else{
	$is_show='show';
	$box_id='payment-1';
	$final_show='';
	$final_box_id='';
}
$err_msg='';
$userArr=getUserDetailsByid();
if(isset($_POST['place_order'])){
	$checkout_name=get_safe_value($_POST['checkout_name']);
	$checkout_email=get_safe_value($_POST['checkout_email']);
	$checkout_mobile=get_safe_value($_POST['checkout_mobile']);
	$checkout_zip=get_safe_value($_POST['checkout_zip']);
	$checkout_address=get_safe_value($_POST['checkout_address']);
	$payment_type=get_safe_value($_POST['payment_type']);
	$added_on=date('Y-m-d h:i:s');
  $cupon_code_applied = '';
  if(isset($_SESSION['COUPON_CODE']) && isset($_SESSION['FINAL_PAY'])){
    $cupon_code_applied = $_SESSION['COUPON_CODE'] ;
    $totalCartPrice = $_SESSION['FINAL_PAY'] ;
  }
  if($totalCartPrice>$websiteCloseStatus['cart_min_price']){
  	$sql="insert into order_master(user_id,username,email,mobile,address,zipcode,	cupon_code,final_pay,total_price,order_status,payment_status,added_on) values('".$_SESSION['USER_ID']."','$checkout_name','$checkout_email','$checkout_mobile','$checkout_address','$checkout_zip','$cupon_code_applied','$totalCartPrice','$totalCartPrice','1','1','$added_on')";
  	mysqli_query($con,$sql);
  	$insert_id=mysqli_insert_id($con);
  	$_SESSION['ORDER_ID']=$insert_id;
  	foreach($cartArr as $key=>$val){
  		mysqli_query($con,"insert into order_detail(order_id,product_detail_id,price,qty) values('$insert_id','$key','".$val['price']."','".$val['qty']."')");
  	}
  	emptyCart();
    $getUserDetailsBy=getUserDetailsByid();
  	$email=$getUserDetailsBy['email'];
  	$emailHTML=orderEmail($insert_id);
  	require 'vendor/autoload.php';
  	send_email($email,'Order Placed',$emailHTML);
    redirect('success.php');
  }else{
    $err_msg = $websiteCloseStatus['cart_min_price_msg'];
  }
}
?>

		<div class="breadcrumb-area gray-bg">
            <div class="container">
                <div class="breadcrumb-content">
                    <ul>
                        <li><a href="shop.php">Home</a></li>
                        <li class="active"> Checkout </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- checkout-area start -->
        <div class="checkout-area pb-80 pt-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="checkout-wrapper">
                            <div id="faq" class="panel-group">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5 class="panel-title"><span>1.</span> <a data-toggle="collapse" data-parent="#faq" href="#payment-1">Checkout method</a></h5>
                                    </div>
                                    <div id="<?php echo $box_id?>" class="panel-collapse collapse <?php echo $is_show?>">
                                        <div class="panel-body">
                                            <div class="row">

                                                <div class="col-lg-12">
                                                    <div class="checkout-login">
                                                        <div class="title-wrap">
                                                            <h4 class="cart-bottom-title section-bg-white">LOGIN</h4>
                                                        </div>
                                                        <p>&nbsp;</p>
                                                        <form method="post" id="form_login">
                                                            <div class="login-form">
                                                                <label>Email Address * </label>
                                                                <input type="email" name="user_email" placeholder="Email" required>
                                                            </div>
                                                            <div class="login-form">
                                                                <label>Password *</label>
                                                                <input type="password" name="user_password" placeholder="Password" required>
                                                            </div>
                                                            <input type="hidden" name="type" value="login">
                                                            <input type="hidden" name="is_checkout" value="checkout" id="is_checkout">
                                                            <div class="checkout-login-btn">
                                                               <button type="submit" id="login_submit" class="login_btn_checkout" >LOGIN</button>
   															                              <a href="login_register.php" style="background-color: #e02c2b;color:#fff;">Register Now</a>
                                                           </div>
                                                           <div class="reg_success" id="success_msg"></div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5 class="panel-title"><span>2.</span> <a data-toggle="collapse" data-parent="#faq" href="#payment-2">Other information</a></h5>
                                    </div>
                                    <div id="<?php echo $final_box_id?>" class="panel-collapse collapse <?php echo $final_show?>">
                                        <div class="panel-body">
                                          <form method="post">
                                              <div class="billing-information-wrapper">
                                                <div class="row">
                                                  <div class="col-lg-3 col-md-6">
                                                    <div class="billing-info">
                                                      <label>First Name</label>
                                                      <input type="text" name="checkout_name" required value="<?php echo $userArr['name']?>">
                                                    </div>
                                                  </div>
                                                  <div class="col-lg-3 col-md-6">
                                                    <div class="billing-info">
                                                      <label>Email Address</label>
                                                      <input type="email"  name="checkout_email" required value="<?php echo $userArr['email']?>">
                                                    </div>
                                                  </div>
                                                  <div class="col-lg-3 col-md-6">
                                                    <div class="billing-info">
                                                      <label>Mobile</label>
                                                      <input type="text"  name="checkout_mobile" required value="<?php echo $userArr['mobile']?>">
                                                    </div>
                                                  </div>
                                                  <div class="col-lg-3 col-md-6">
                                                    <div class="billing-info">
                                                      <label>Zip/Postal Code</label>
                                                      <input type="text"  name="checkout_zip" required>
                                                    </div>
                                                  </div>
                                                  <div class="col-lg-12 col-md-12">
                                                    <div class="billing-info">
                                                      <label>Address</label>
                                                      <input type="text"  name="checkout_address" required>
                                                    </div>
                                                  </div>
                                                  <div class="col-lg-3 col-md-3">
                                                    <div class="billing-info">
                                                      <label>Cupon Code</label>
                                                      <input type="text"  name="cupon_code" id="cupon_code" >
                                                      <div class="coupon_msg"></div>
                                                    </div>
                                                  </div>
                                                  <div class="billing-back-btn">
                                                    <div class="billing-btn">
                                                      <button type="button" name="apply_coupon" id="apply_coupon" onclick="coupon_code_apply()">Apply Coupon</button>
                                                    </div>
                                                  </div>
                                                  </div>
                                                <div class="ship-wrapper">
                                                  <div class="single-ship">
                                                    <input type="radio" name="payment_type" value="cod" checked="checked">
                                                    <label>Cash on Delivery(COD)</label>
                                                  </div>
                                                </div>
                                                <div class="billing-back-btn">
                                                  <div class="billing-btn">
                                                    <button type="submit" name="place_order">Place Your Order</button>
                                                  </div>
                                                </div>
                                                <div class="cart_min_price_message"><?php echo $err_msg?></div>
                                              </div>
                                        </form>
                                      </div>
                                  </div>
                              </div>
						                </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="checkout-progress">
                            <div class="shopping-cart-content-box">
								<h4 class="checkout_title">Cart Details</h4>
								<ul>
                  <?php foreach ($cartArr as $key=>$list) { ?>
									<li class="single-shopping-cart">
										<div class="shopping-cart-img">
											<a href="javascript:void(0)"><img alt="" src="<?php echo SITE_IMAGE_PATH. $list['image']?>"></a>
										</div>
										<div class="shopping-cart-title">
											<h4><a href="javascript:void(0)"><?php echo $list['product']?></a></h4>
											<h6>Qty: <?php echo $list['qty']?></h6>
											<span><?php echo $list['qty']*$list['price']."Tk."?></span>
										</div>
									</li>
                  <?php } ?>
								</ul>
								<div class="shopping-cart-total">
									<h4>Total : <span class="shop-total"><?php  echo $totalCartPrice."Tk." ; ?></span></h4>
								</div>
                <div class="shopping-cart-total coupon_code_apply_box">
									<h4>Coupon Code : <span class="shop-total coupon_code_name"></span></h4>
								</div>
                <div class="shopping-cart-total coupon_code_apply_box">
									<h4>Final Price : <span class="shop-total final_price"></span></h4>
								</div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
include('footer.php');
if(isset($_SESSION['COUPON_CODE'])){
  unset($_SESSION['COUPON_CODE']);
  unset($_SESSION['FINAL_PAY']);
}
?>
