<?php include('header.php');

$websiteCloseStatus = websiteCloseStatus();
if($websiteCloseStatus['website_close'] == 1){
  redirect('shop.php');
}
?>

<div class="cart-main-area pt-95 pb-100">
        <div class="container">
            <h3 class="page-title">Your cart items</h3>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                  <?php
                  $cartArr = getUserFullCart();
                  $totalCartProduct = count($cartArr);
                  if($totalCartProduct>0){
                   ?>
                    <form method="post">
                        <div class="table-content table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Product Name</th>
                                        <th>Unit Price</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                        <th>action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php foreach ($cartArr as $key=>$list) { ?>
                                    <tr>
                                        <td class="product-thumbnail">
                                            <a href="javascript:void(0)"><img alt="Product Image" src="<?php echo SITE_IMAGE_PATH. $list['image']?>" width="85px"></a>
                                        </td>
                                        <td class="product-name"><a href="javascript:void(0)"><?php echo $list['product']?></a></td>
                                        <td class="product-price-cart"><span class="amount"><?php echo $list['price']."Tk."?></span></td>
                                        <td class="product-quantity">
                                            <div class="cart-plus-minus">
                                                <input class="cart-plus-minus-box" type="text" name="qtybutton[<?php echo $key?>][]" value="<?php echo $list['qty']?>">
                                            </div>
                                        </td>
                                        <td class="product-subtotal"><?php echo $list['qty']*$list['price']."Tk."?></td>
                                        <td class="product-remove">
                                            <a href="javascript:void(0)" onclick="delete_from_cart('<?php echo $key?>','load')"><i class="fa fa-times"></i></a>
                                       </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="cart-shiping-update-wrapper">
                                    <div class="cart-shiping-update">
                                        <a href="shop.php">Continue Shopping</a>
                                    </div>
                                    <div class="cart-clear">
                                        <button name="update_cart_qty">Update Shopping Cart</button>
                                        <a href="checkout.php">Checkout</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                  <?php }else{
                    echo "NO PRODUCT IN YOUR CART";
                  } ?>
                </div>
            </div>
        </div>
    </div>

<?php include('footer.php');?>
