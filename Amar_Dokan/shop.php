<?php include('header.php');
$cat_check='';
$product_arr=array();
  if(isset($_GET['cat_check'])){
    $cat_check = $_GET['cat_check'];
    $product_arr = array_filter(explode(':',$cat_check));
    $product_str = implode(',',$product_arr);
  }
?>

<div class="shop-page-area pt-100 pb-100">
  <?php
  $websiteCloseStatus = websiteCloseStatus();
  if($websiteCloseStatus['website_close'] == 1){
    echo "<div class='website_close_txt'><marquee>$websiteCloseStatus[website_close_msg]</marquee></div>";
  }
  ?>
    <div class="container">
        <div class="row flex-row-reverse">
            <div class="col-lg-9">
              <?php
              $pro_sql = "select * from products where status = 1";
              $cat_id=0;
              if(isset($_GET['cat_check']) && $_GET['cat_check'] !=''){
                $pro_sql .=" and category_id in($product_str) ";
              }
              $pro_sql .= " order by id desc ";
              $product_res = mysqli_query($con,$pro_sql);
              $product_num = mysqli_num_rows($product_res);
              ?>
            <div class="grid-list-product-wrapper">
                <div class="product-grid product-view pb-20">
                    <div class="row">
                      <?php
                       if($product_num > 0){
                         while($product_row = mysqli_fetch_assoc($product_res)){
                        ?>
                        <div class="product-width col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12 mb-30">
                            <div class="product-wrapper">
                                <div class="product-img">
                                    <a href="javascript:void(0)">
                                        <img src="<?php echo SITE_IMAGE_PATH.$product_row['image'] ?>" alt="">
                                    </a>
                                </div>
                                <div class="product-content">
                                    <h4>
                                        <a href="javascript:void(0)"><?php echo $product_row['product'] ?></a>
                                    </h4>
                                    <?php
                                    $product_attr_res = mysqli_query($con,'select * from product_details where product_id="'.$product_row['id'].'"  order by attribute desc');
                                    ?>
                                    <?php if($websiteCloseStatus['website_close'] != 1){ ?>
                                      <div class="product-price-wrapper" id="product-price-wrapper_radio">
                                        <?php while($product_attr_row = mysqli_fetch_assoc($product_attr_res)){
                                          $attr_status='';
                                          $attr_status_msg='';
                                          if($product_attr_row['status']==0){
                                            $attr_status="disabled";
                                            $attr_status_msg="(out of stock)";
                                          }
                                          echo "<input type='radio' class='radio_btn' name='radio_".$product_row["id"]."'
                                          value='".$product_attr_row["id"]."' $attr_status>";
                                          echo "&nbsp;";
                                          echo $product_attr_row['attribute'];
                                          echo "&nbsp;";
                                          echo "(".$product_attr_row['price']."/-)";
                                          echo '<span class="attr_status_msg">'.$attr_status_msg.'</span>';
                                          $cart_msg='';
                                          if(array_key_exists($product_attr_row["id"],$cartArr)){
                                            $cart_count = getUserFullCart($product_attr_row["id"]);
                                            $cart_msg = "(Added -".$cart_count. ")";
                                          }
                                          echo "<span class='added_cart' id='added_cart_id_".$product_attr_row['id']."'>$cart_msg</span>";
                                          echo "&nbsp;&nbsp;";
                                          } ?>
                                        <div class="pro_qty">
                                          <select class="pro_select" name="" id="qty_<?php echo $product_row['id'] ?>">
                                            <option value="0">Qyt</option>
                                            <?php
                                            for($p=1;$p<=20;$p++){
                                              echo "<option value='$p'>$p</option>";
                                              }
                                              ?>
                                          </select>
                                          <img src="<?php echo SITE_IMAGE_PATH?>cart1.png" onclick="add_cart(<?php echo $product_row['id'] ?>,'add')">
                                        </div>
                                      </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } }else{
                      echo "No Product Found";
                    }?>
                    </div>
                </div>
            </div>
        </div>
          <?php
          $category_res = mysqli_query($con,"select * from category where status='1' order by order_number desc");
          ?>
          <div class="col-lg-3">
              <div class="shop-sidebar-wrapper gray-bg-7 shop-sidebar-mrg">
                  <div class="shop-widget">
                      <h4 class="shop-sidebar-title">Shop By Categories</h4>
                      <div class="shop-catigory">
                          <ul id="faq" class="category_list">
                            <li><a href="shop.php"><b><u>clear</u></b></a></li>
                            <?php
                            $is_checked='';
                            while($category_row = mysqli_fetch_assoc($category_res)){
                              if(in_array($category_row['id'],$product_arr)){
                                $is_checked = "checked='checked'";
                              }else{
                                $is_checked='';
                              }
                              ?>
                              <li><input type="checkbox" <?php echo $is_checked ;?> name="cat_arr[]" class="cat_checkbox" onclick="cat_check('<?php echo $category_row['id']?>')" > <a href="shop.php?cat_id=<?php echo $category_row['id']?>"><?php echo $category_row['category']?></a> </li>
                            <?php }  ?>
                      </div>
                  </div>
              </div>
          </div>
      </div>
    </div>
    <form id="check_box"  method="get">
      <input type="hidden" name="cat_check" id="cat_check_id" value="<?php echo $cat_check ?>">
    </form>
</div>



<?php include('footer.php'); ?>
