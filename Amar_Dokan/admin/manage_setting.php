<?php include('top.php');

$cart_min_price = '';
$cart_min_price_msg = '';
$website_close = '';
$website_close_msg = '';
  if(isset($_POST['submit'])){
    $cart_min_price = get_safe_value($_POST['cart_min_price']);
    $cart_min_price_msg = get_safe_value($_POST['cart_min_price_msg']);
    $website_close = get_safe_value($_POST['website_close']);
    $website_close_msg = get_safe_value($_POST['website_close_msg']);
     mysqli_query($con,"update setting set cart_min_price='$cart_min_price', cart_min_price_msg='$cart_min_price_msg' , website_close='$website_close', website_close_msg='$website_close_msg' where id=1 ");
     redirect('manage_setting.php');
}
     $sql = "select * from setting where id = 1 ";
    if(mysqli_num_rows(mysqli_query($con,$sql))>0){
      $row = mysqli_fetch_assoc(mysqli_query($con,$sql));
      $cart_min_price = $row['cart_min_price'];
      $cart_min_price_msg = $row['cart_min_price_msg'];
      $website_close = $row['website_close'];
      $website_close_msg = $row['website_close_msg'];
      }else{
        redirect('index.php');
      }
?>

<div class="row">
			<h1 class="grid_title ml10 ml15">Manage Setting</h1>
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <form class="forms-sample" method="post">
                    <div class="form-group">
                      <label for="exampleInputName1">Cart Min Price</label>
                      <input type="text" class="form-control" placeholder="cart_min_price" name="cart_min_price" required value="<?php echo $cart_min_price?>">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputName1">Cart Min Price Msg</label>
                      <input type="text" class="form-control" placeholder="cart_min_price_msg" name="cart_min_price_msg" required value="<?php echo $cart_min_price_msg?>">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputName1">Website Close</label>
                        <select class="form-control" name="website_close">
                          <option value="">Select Option</option>
                          <?php
                            if($website_close == 0){
                              echo "<option value='0' selected='selected'>No</option>";
                              echo "<option value='1'>Yes</option>";
                            }else{
                              echo "<option value='0'>No</option>";
                              echo "<option value='1' selected='selected'>Yes</option>";
                            }
                          ?>
                        </select>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputName1">Website Close Msg</label>
                      <input type="text" class="form-control" placeholder="Category" name="website_close_msg" required value="<?php echo $website_close_msg?>">
                    </div>

                    <button type="submit" class="btn btn-primary mr-2" name="submit">Submit</button>
                  </form>
                </div>
              </div>
            </div>
		 </div>

<?php include('footer.php');?>
