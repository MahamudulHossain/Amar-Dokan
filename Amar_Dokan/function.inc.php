<?php
    function pr($arr){
    	echo '<pre>';
    	print_r($arr);
    }


    function prx($arr){
    	echo '<pre>';
    	print_r($arr);
    	die();
    }
    function get_safe_value($str){
	     global $con;
	     $str=mysqli_real_escape_string($con,$str);
	     return $str;
   }
   function redirect($link){
    	  ?>
      	<script>
       	window.location.href='<?php echo $link?>';
      	</script>
      	<?php
      	die();
   }

   function rand_str(){
     $str = str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIKLMNOPQRSTUVWXYZ");
     $str_sub = substr($str,0,15);
     return $str_sub;
   }

   function send_email($sender_email,$sub,$content){
     $API_KEY = "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";
      require 'vendor/autoload.php';
      $email = new \SendGrid\Mail\Mail();
      $email->setFrom("websitetestingbyhira@gmail.com");
      $email->setSubject($sub);
      $email->addTo($sender_email);
      $email->addContent("text/html",$content);

      $sendgrid = new \SendGrid($API_KEY);

      $sendgrid->send($email);
   }

   function addTocart($uid,$qty,$attr){
     global $con;
     $res = mysqli_query($con,"select * from product_cart where user_id='$uid' and product_detail_id='$attr'");
     if(mysqli_num_rows($res)>0){
       $row = mysqli_fetch_assoc($res);
       $id = $row['id'];
       mysqli_query($con,"update product_cart set qty='$qty' where id='$id'");
     }else{
       $added_on = date('Y-m-d h-i-s');
       mysqli_query($con,"insert into product_cart(user_id,product_detail_id,qty,added_on) values('$uid','$attr','$qty','$added_on')");
     }
   }

   function getCartData(){
    	global $con;
    	$arr = array();
    	$uid = $_SESSION['USER_ID'];
    	$res= mysqli_query($con,"select * from product_cart where user_id='$uid'");
    	while($row = mysqli_fetch_assoc($res)){
    		$arr[] = $row;
    	}
    	return $arr;
  }


  function getCartStatus(){
    global $con;
    $cartArray=array();
    $productArray = array();
    if(isset($_SESSION['USER_ID'])){
      $getCartData = getCartData();
      foreach ($getCartData as $list) {
        $productArray[] = $list['product_detail_id'];
      }
    }else{
        if(isset($_SESSION['cart']) && count($_SESSION['cart'])>0){
          foreach ($_SESSION['cart'] as $key=>$val) {
           $productArray[] = $key;
         }
        }
    }
    foreach ($productArray as $pro_id) {
      $res = mysqli_query($con,"select product_details.status, products.status as product_status, products.id from product_details, products where product_details.id = '$pro_id' and product_details.product_id = products.id");
      $row1 = mysqli_fetch_assoc($res);
        if($row1['status'] == 0){
          delete_product_from_cart($pro_id);
        }
      if($row1['product_status'] == 0){
        $id = $row1['id'];
        $res1 = mysqli_query($con,"select id from product_details where product_id='$id'");
        while($row2 = mysqli_fetch_assoc($res1)){
          $did = $row2['id'];
          delete_product_from_cart($did);
        }
      }
    }
  }

  function getUserFullCart($attr_id=''){
    global $con;
    $cartArray=array();
    if(isset($_SESSION['USER_ID'])){
      $getCartData = getCartData();
      foreach ($getCartData as $list) {
        $cartArray[$list['product_detail_id']]['qty'] = $list['qty'];
        $getCartById = getCartById($list['product_detail_id']);
        $cartArray[$list['product_detail_id']]['product'] = $getCartById ['product'];
        $cartArray[$list['product_detail_id']]['price'] = $getCartById ['price'];
        $cartArray[$list['product_detail_id']]['image'] = $getCartById ['image'];

      }
    }else{
        if(isset($_SESSION['cart']) && count($_SESSION['cart'])>0){
          foreach ($_SESSION['cart'] as $key=>$val) {
            $cartArray[$key]['qty'] = $val['qty'];
            $getCartById = getCartById($key);
            $cartArray[$key]['product'] = $getCartById['product'];
            $cartArray[$key]['price'] = $getCartById['price'];
            $cartArray[$key]['image'] = $getCartById['image'];
          }
        }
    }
    if($attr_id!=''){
				 return $cartArray[$attr_id]['qty'];
			 }else{
				 return $cartArray;
			 }
}

  function getCartById($id){
    global $con;
    $arr = array();
    $res= mysqli_query($con,"select products.product, products.image, product_details.price from product_details,products where product_details.id='$id' and products.id = product_details.product_id");
    while($row = mysqli_fetch_assoc($res)){
      $arr = $row;
    }
    return $arr;
  }
  function getCartTotalPrice(){
    $cartArr = getUserFullCart();
    $totalCartPrice = 0;
    foreach ($cartArr as $list) {
      $totalCartPrice = $totalCartPrice+($list['qty']*$list['price']);
    }
    return $totalCartPrice;
  }

  function delete_product_from_cart($id){
    global $con;
    if(isset($_SESSION['USER_ID'])){
      mysqli_query($con,"delete from product_cart where product_detail_id='$id' and user_id=".$_SESSION['USER_ID']);
    }else{
      unset($_SESSION['cart'][$id]);
    }
  }

  function getUserDetailsByid(){
	global $con;
	$data['name']='';
	$data['email']='';
	$data['mobile']='';

	if(isset($_SESSION['USER_ID'])){
		$row=mysqli_fetch_assoc(mysqli_query($con,"select * from user where id=".$_SESSION['USER_ID']));
		$data['name']=$row['username'];
		$data['email']=$row['email'];
		$data['mobile']=$row['mobile'];
	}
	return $data;
}

function emptyCart(){
	if(isset($_SESSION['USER_ID'])){
		global $con;
		$res=mysqli_query($con,"delete from product_cart where user_id=".$_SESSION['USER_ID']);
	}else{
		unset($_SESSION['cart']);
	}
}

function getProductDetails($oid){
  global $con;
  $sql = "SELECT order_detail.price,order_detail.qty,product_details.attribute,products.product
           FROM order_detail,product_details,products
           WHERE order_detail.order_id=$oid AND
           order_detail.product_detail_id = product_details.id AND
           product_details.product_id = products.id";
   $res = mysqli_query($con,$sql);
   $data = array();
   while($row = mysqli_fetch_assoc($res)){
     $data[]  = $row;
   }
   return $data;
}

function getOrderById($oid){
	global $con;
	$sql="select * from order_master where id='$oid'";
	$data=array();
	$res=mysqli_query($con,$sql);
	while($row=mysqli_fetch_assoc($res)){
		$data[]=$row;
	}
	return $data;
}

function orderEmail($oid){
	$getUserDetailsBy=getUserDetailsByid();
	$name=$getUserDetailsBy['name'];
	$email=$getUserDetailsBy['email'];

	$getOrderById=getOrderById($oid);

	$order_id=$getOrderById[0]['id'];
	$total_amount=$getOrderById[0]['total_price'];

	$getOrderDetails=getProductDetails($oid);

	$html='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="x-apple-disable-message-reformatting" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title></title>
    <style type="text/css" rel="stylesheet" media="all">
    /* Base ------------------------------ */

    @import url("https://fonts.googleapis.com/css?family=Nunito+Sans:400,700&display=swap");
    body {
      width: 100% !important;
      height: 100%;
      margin: 0;
      -webkit-text-size-adjust: none;
    }

    a {
      color: #3869D4;
    }

    a img {
      border: none;
    }

    td {
      word-break: break-word;
    }

    .preheader {
      display: none !important;
      visibility: hidden;
      mso-hide: all;
      font-size: 1px;
      line-height: 1px;
      max-height: 0;
      max-width: 0;
      opacity: 0;
      overflow: hidden;
    }
    /* Type ------------------------------ */

    body,
    td,
    th {
      font-family: "Nunito Sans", Helvetica, Arial, sans-serif;
    }

    h1 {
      margin-top: 0;
      color: #333333;
      font-size: 22px;
      font-weight: bold;
      text-align: left;
    }

    h2 {
      margin-top: 0;
      color: #333333;
      font-size: 16px;
      font-weight: bold;
      text-align: left;
    }

    h3 {
      margin-top: 0;
      color: #333333;
      font-size: 14px;
      font-weight: bold;
      text-align: left;
    }

    td,
    th {
      font-size: 16px;
    }

    p,
    ul,
    ol,
    blockquote {
      margin: .4em 0 1.1875em;
      font-size: 16px;
      line-height: 1.625;
    }

    p.sub {
      font-size: 13px;
    }
    /* Utilities ------------------------------ */

    .align-right {
      text-align: right;
    }

    .align-left {
      text-align: left;
    }

    .align-center {
      text-align: center;
    }
    /* Buttons ------------------------------ */

    .button {
      background-color: #3869D4;
      border-top: 10px solid #3869D4;
      border-right: 18px solid #3869D4;
      border-bottom: 10px solid #3869D4;
      border-left: 18px solid #3869D4;
      display: inline-block;
      color: #FFF;
      text-decoration: none;
      border-radius: 3px;
      box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);
      -webkit-text-size-adjust: none;
      box-sizing: border-box;
    }

    .button--green {
      background-color: #22BC66;
      border-top: 10px solid #22BC66;
      border-right: 18px solid #22BC66;
      border-bottom: 10px solid #22BC66;
      border-left: 18px solid #22BC66;
    }

    .button--red {
      background-color: #FF6136;
      border-top: 10px solid #FF6136;
      border-right: 18px solid #FF6136;
      border-bottom: 10px solid #FF6136;
      border-left: 18px solid #FF6136;
    }

    @media only screen and (max-width: 500px) {
      .button {
        width: 100% !important;
        text-align: center !important;
      }
    }
    /* Attribute list ------------------------------ */

    .attributes {
      margin: 0 0 21px;
    }

    .attributes_content {
      background-color: #F4F4F7;
      padding: 16px;
    }

    .attributes_item {
      padding: 0;
    }
    /* Related Items ------------------------------ */

    .related {
      width: 100%;
      margin: 0;
      padding: 25px 0 0 0;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
    }

    .related_item {
      padding: 10px 0;
      color: #CBCCCF;
      font-size: 15px;
      line-height: 18px;
    }

    .related_item-title {
      display: block;
      margin: .5em 0 0;
    }

    .related_item-thumb {
      display: block;
      padding-bottom: 10px;
    }

    .related_heading {
      border-top: 1px solid #CBCCCF;
      text-align: center;
      padding: 25px 0 10px;
    }
    /* Discount Code ------------------------------ */

    .discount {
      width: 100%;
      margin: 0;
      padding: 24px;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
      background-color: #F4F4F7;
      border: 2px dashed #CBCCCF;
    }

    .discount_heading {
      text-align: center;
    }

    .discount_body {
      text-align: center;
      font-size: 15px;
    }
    /* Social Icons ------------------------------ */

    .social {
      width: auto;
    }

    .social td {
      padding: 0;
      width: auto;
    }

    .social_icon {
      height: 20px;
      margin: 0 8px 10px 8px;
      padding: 0;
    }
    /* Data table ------------------------------ */

    .purchase {
      width: 100%;
      margin: 0;
      padding: 35px 0;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
    }

    .purchase_content {
      width: 100%;
      margin: 0;
      padding: 25px 0 0 0;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
    }

    .purchase_item {
      padding: 10px 0;
      color: #51545E;
      font-size: 15px;
      line-height: 18px;
    }

    .purchase_heading {
      padding-bottom: 8px;
      border-bottom: 1px solid #EAEAEC;
    }

    .purchase_heading p {
      margin: 0;
      color: #85878E;
      font-size: 12px;
    }

    .purchase_footer {
      padding-top: 15px;
      border-top: 1px solid #EAEAEC;
    }

    .purchase_total {
      margin: 0;
      text-align: right;
      font-weight: bold;
      color: #333333;
    }

    .purchase_total--label {
      padding: 0 15px 0 0;
    }

    body {
      background-color: #F4F4F7;
      color: #51545E;
    }

    p {
      color: #51545E;
    }

    p.sub {
      color: #6B6E76;
    }

    .email-wrapper {
      width: 100%;
      margin: 0;
      padding: 0;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
      background-color: #F4F4F7;
    }

    .email-content {
      width: 100%;
      margin: 0;
      padding: 0;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
    }
    /* Masthead ----------------------- */

    .email-masthead {
      padding: 25px 0;
      text-align: center;
    }

    .email-masthead_logo {
      width: 94px;
    }

    .email-masthead_name {
      font-size: 16px;
      font-weight: bold;
      color: #A8AAAF;
      text-decoration: none;
      text-shadow: 0 1px 0 white;
    }
    /* Body ------------------------------ */

    .email-body {
      width: 100%;
      margin: 0;
      padding: 0;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
      background-color: #FFFFFF;
    }

    .email-body_inner {
      width: 570px;
      margin: 0 auto;
      padding: 0;
      -premailer-width: 570px;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
      background-color: #FFFFFF;
    }

    .email-footer {
      width: 570px;
      margin: 0 auto;
      padding: 0;
      -premailer-width: 570px;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
      text-align: center;
    }

    .email-footer p {
      color: #6B6E76;
    }

    .body-action {
      width: 100%;
      margin: 30px auto;
      padding: 0;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
      text-align: center;
    }

    .body-sub {
      margin-top: 25px;
      padding-top: 25px;
      border-top: 1px solid #EAEAEC;
    }

    .content-cell {
      padding: 35px;
    }
    /*Media Queries ------------------------------ */

    @media only screen and (max-width: 600px) {
      .email-body_inner,
      .email-footer {
        width: 100% !important;
      }
    }

    @media (prefers-color-scheme: dark) {
      body,
      .email-body,
      .email-body_inner,
      .email-content,
      .email-wrapper,
      .email-masthead,
      .email-footer {
        background-color: #333333 !important;
        color: #FFF !important;
      }
      p,
      ul,
      ol,
      blockquote,
      h1,
      h2,
      h3 {
        color: #FFF !important;
      }
      .attributes_content,
      .discount {
        background-color: #222 !important;
      }
      .email-masthead_name {
        text-shadow: none !important;
      }
    }
    </style>
    <!--[if mso]>
    <style type="text/css">
      .f-fallback  {
        font-family: Arial, sans-serif;
      }
    </style>
  <![endif]-->
  </head>
  <body>
    <span class="preheader">This is an invoice for your purchase on '.SITE_NAME.'</span>
    <table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
      <tr>
        <td align="center">
          <table class="email-content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
              <td class="email-masthead">
                <a href="" class="f-fallback email-masthead_name">
                <img src="https://i.ibb.co/6myys4W/logo-1.png"/>
              </a>
              </td>
            </tr>
            <!-- Email Body -->
            <tr>
              <td class="email-body" width="100%" cellpadding="0" cellspacing="0">
                <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                  <!-- Body content -->
                  <tr>
                    <td class="content-cell">
                      <div class="f-fallback">
                        <h1>Hi '.$name.',</h1>
                        <p>This is an invoice for your recent purchase.</p>
                        <table class="attributes" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                          <tr>
                            <td class="attributes_content">
                              <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                  <td class="attributes_item">
                                    <span class="f-fallback">
              <strong>Amount Due:</strong> '.$total_amount.'Tk
            </span>
                                  </td>
                                </tr>
                                <tr>
                                  <td class="attributes_item">
                                    <span class="f-fallback">
              <strong>Order ID:</strong> '.$order_id.'
            </span>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                        <!-- Action -->

                        <table class="purchase" width="100%" cellpadding="0" cellspacing="0">

                          <tr>
                            <td colspan="2">
                              <table class="purchase_content" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                  <th class="purchase_heading" align="left">
                                    <p class="f-fallback">Description</p>
                                  </th>
								   <th class="purchase_heading" align="left">
                                    <p class="f-fallback">Qty</p>
                                  </th>
                                  <th class="purchase_heading" align="right">
                                    <p class="f-fallback">Amount</p>
                                  </th>
                                </tr>';
								$total_price=0;
                                foreach($getOrderDetails as $list){
									$item_price=$list['qty']*$list['price'];
									$total_price=$total_price+$item_price;
									$html.='<tr>
									  <td width="40%" class="purchase_item"><span class="f-fallback">'.$list['product'].'('.$list['attribute'].')</span></td>
									  <td width="40%" class="purchase_item"><span class="f-fallback">'.$list['qty'].'</span></td>
									  <td class="align-right" width="20%" class="purchase_item"><span class="f-fallback">'.$item_price.'Tk</span></td>
									</tr>';
                                }
                                $html.='<tr>
                                  <td width="80%" class="purchase_footer" valign="middle" colspan="2">
                                    <p class="f-fallback purchase_total purchase_total--label">Total</p>
                                  </td>
                                  <td width="20%" class="purchase_footer" valign="middle">
                                    <p class="f-fallback purchase_total">'.$total_price.'Tk</p>
                                  </td>
                                </tr>
                                <tr>
                                  <td width="80%" class="purchase_footer" valign="middle" colspan="2">
                                    <p class="f-fallback purchase_total purchase_total--label">Amount to Pay</p>
                                  </td>
                                  <td width="20%" class="purchase_footer" valign="middle">
                                    <p class="f-fallback purchase_total">'.$total_amount.'Tk</p>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                        <p>If you have any questions about this invoice, simply reply to this email or reach out to our <a href="'.SITE_FULL_PATH.'">support team</a> for help.</p>
                        <p>Cheers,
                          <br>'.SITE_NAME.'</p>
                        <!-- Sub copy -->

                      </div>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>';
  return $html;
}


function websiteCloseStatus(){
  global $con;
  $res= mysqli_query($con,"select * from setting where id= 1");
  $row = mysqli_fetch_assoc($res);
  $data = array();

  $data['cart_min_price'] = $row['cart_min_price'];
  $data['cart_min_price_msg'] = $row['cart_min_price_msg'];
  $data['website_close'] = $row['website_close'];
  $data['website_close_msg'] = $row['website_close_msg'];
  return $data;
}






 ?>
