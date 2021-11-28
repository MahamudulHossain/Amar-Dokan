<?php
session_start();
include('constant.inc.php');
include('database.inc.php');
include('function.inc.php');
if(isset($_POST['update_cart_qty'])){
  $qtybtn = $_POST['qtybutton'];
  foreach ($qtybtn as $key => $val) {
    if(isset($_SESSION['USER_ID'])){
      if($val[0] == 0){
        mysqli_query($con,"delete from product_cart where product_detail_id='$key'");
      }else{
        mysqli_query($con,"update product_cart set qty='".$val[0]."' where product_detail_id='$key'");
      }
    }else{
      if($val[0] == 0){
        unset($_SESSION['cart'][$key]);
      }else{
        $_SESSION['cart'][$key]['qty'] = $val[0];
      }
    }
  }
}
getCartStatus();
$cartArr = getUserFullCart();
$totalCartProduct = count($cartArr);

$totalCartPrice = getCartTotalPrice();
?>

<!doctype html>
<html class="no-js" lang="zxx">
<head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <link rel="icon" type="image/png" href="<?php echo SITE_IMAGE_PATH?>favi.png">
        <title><?php echo SITE_NAME?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/animate.css">
        <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
        <link rel="stylesheet" href="assets/css/slick.css">
        <link rel="stylesheet" href="assets/css/chosen.min.css">
        <link rel="stylesheet" href="assets/css/ionicons.min.css">
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
        <link rel="stylesheet" href="assets/css/simple-line-icons.css">
        <link rel="stylesheet" href="assets/css/jquery-ui.css">
        <link rel="stylesheet" href="assets/css/meanmenu.min.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/css/responsive.css">
        <script src="assets/js/vendor/modernizr-2.8.3.min.js"></script>
    </head>
    <body>
        <!-- header start -->
        <header class="header-area">
            <div class="header-top black-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-12 col-sm-4">

                        </div>
                        <?php if(isset($_SESSION['USER_NAME'])){?>
                        <div class="col-lg-8 col-md-8 col-12 col-sm-8">
                            <div class="account-curr-lang-wrap f-right">
                                <ul>
                                    <li class="top-hover"><a href="#">Welcome <span id="acc_name"><?php echo $_SESSION['USER_NAME']?></span>  <i class="ion-chevron-down"></i></a>
                                        <ul>
                                            <li><a href="wishlist.html">Wishlist</a></li>
                                            <li><a href="order_history.php">Order history</a></li>
                                            <li><a href="my_account.php">My account</a></li>
                                            <li><a href="logout.php">Logout</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                      <?php } ?>
                    </div>
                </div>
            </div>
            <div class="header-middle">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-12 col-sm-4">
                            <div class="logo">
                                <a href="index.php">
                                    <img alt="" src="assets/img/logo/logo.png">
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-8 col-12 col-sm-8">
                            <div class="header-middle-right f-right">
                              <?php if(!isset($_SESSION['USER_NAME'])){?>
                                <div class="header-login">
                                    <a href="login_register.php">
                                        <div class="header-icon-style">
                                            <i class="icon-user icons"></i>
                                        </div>
                                        <div class="login-text-content">
                                            <p>Register <br> or <span>Sign in</span></p>
                                        </div>
                                    </a>
                                </div>
                              <?php } ?>
                                <div class="header-wishlist">
                                   &nbsp;
                                </div>
                                <div class="header-cart">
                                    <a href="#">
                                        <div class="header-icon-style">
                                            <i class="icon-handbag icons"></i>
                                            <span class="count-style" id="totalCartProduct"><?php echo $totalCartProduct?></span>
                                        </div>
                                        <div class="cart-text">
                                            <span class="digit">My Cart</span>
                                            <span class="cart-digit-bold" id="totalCartPrice">
                                              <?php
                                              if($totalCartProduct !=0){
                                                echo $totalCartPrice."Tk." ;
                                              }
                                              ?></span>
                                        </div>
                                    </a>
                                    <?php if($totalCartProduct !=0){?>
                                    <div class="shopping-cart-content">
                                        <ul id="cart_ul">
                                          <?php foreach ($cartArr as $key=>$list) { ?>
                                            <li class="single-shopping-cart" id="cart_product_id_<?php echo $key?>">
                                                <div class="shopping-cart-img">
                                                    <a href="javascript:void(0)"><img alt="Product Image" src="<?php echo SITE_IMAGE_PATH. $list['image']?>"></a>
                                                </div>
                                                <div class="shopping-cart-title">
                                                    <h4><a href="javascript:void(0)"><?php echo $list['product']?></a></h4>
                                                    <h6>Qty: <?php echo $list['qty']?></h6>
                                                    <span><?php echo $list['qty']*$list['price']."Tk."?></span>
                                                </div>
                                                <div class="shopping-cart-delete">
                                                    <a href="javascript:void(0)" onclick="delete_from_cart('<?php echo $key?>')"><i class="ion ion-close"></i></a>
                                                </div>
                                            </li>
                                          <?php } ?>
                                        </ul>
                                        <div class="shopping-cart-total">
                                            <h4>Total : <span class="shop-total"><?php
                                            if($totalCartProduct !=0){
                                              echo $totalCartPrice."Tk." ;
                                            }
                                            ?></span></h4>
                                        </div>
                                        <div class="button-box">
                                          <button class="btn btn-block ext" onclick="document.location='cart.php'">view cart</button>
                                          <button class="btn btn-block ext" onclick="document.location='checkout.php'">checkout</button>
                                        </div>
                                    </div>
                                    <?php } ?>
							                   </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-bottom transparent-bar black-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-12">
                            <div class="main-menu">
                                <nav>
                                    <ul>
                                        <li><a href="shop.php">Home</a></li>
                                        <li><a href="about_us.php">about</a></li>
                                        <li><a href="contact.php">contact us</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- mobile-menu-area-start -->
			<div class="mobile-menu-area">
				<div class="container">
					<div class="row">
						<div class="col-lg-12">
							<div class="mobile-menu">
								<nav id="mobile-menu-active">
									<ul class="menu-overflow" id="nav">
										<li><a href="shop.php">Home</a></li>
										<li><a href="about_us.php">About</a></li>
										<li><a href="contact.php">Contact Us</a></li>
									</ul>
								</nav>
							</div>
						</div>
					</div>
				</div>
			</div>
    </header>
