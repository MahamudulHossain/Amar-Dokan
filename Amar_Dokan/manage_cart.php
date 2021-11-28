<?php
session_start();
include('constant.inc.php');
include('database.inc.php');
include('function.inc.php');

  $attr = get_safe_value($_POST['attr']);
  $type = get_safe_value($_POST['type']);

  if($type == 'add'){
    $qty = get_safe_value($_POST['qty']);
    if(isset($_SESSION['USER_ID'])){
      $uid = $_SESSION['USER_ID'];
      addTocart($uid,$qty,$attr);
    }else{
      $_SESSION['cart'][$attr]['qty'] = $qty;
    }
    $totalProduct = count(getUserFullCart());
    $totalCartPrice = 0;
    $cartArr = getUserFullCart();
    foreach ($cartArr as $list){
      $totalCartPrice = $totalCartPrice+($list['qty']*$list['price']);
    }
    $getCartById = getCartById($attr);
    $product = $getCartById['product'];
    $price = $getCartById['price'];
    $image = $getCartById['image'];
    $arr = array('totalCartProduct'=>$totalProduct,'totalCartPrice'=>$totalCartPrice,'product'=>$product,'price'=>$price,'image'=>$image);
    echo json_encode($arr);
  }

  if($type == 'delete'){
    delete_product_from_cart($attr);
    $totalProduct = count(getUserFullCart());
    $totalCartPrice = 0;
    $cartArr = getUserFullCart();
    foreach ($cartArr as $list){
      $totalCartPrice = $totalCartPrice+($list['qty']*$list['price']);
    }
    $arr = array('totalCartProduct'=>$totalProduct,'totalCartPrice'=>$totalCartPrice);
    echo json_encode($arr);
  }

 ?>
