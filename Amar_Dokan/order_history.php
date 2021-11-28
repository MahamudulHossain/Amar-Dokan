<?php
include ("header.php");
if(!isset($_SESSION['USER_ID'])){
  redirect('shop.php');
}else{
  $user_id = $_SESSION['USER_ID'];
  $sql = "select order_master.*, payment_status.Payment_status as payment_status_str,order_status.status as order_status_str from order_master,payment_status,order_status where order_master.payment_status = payment_status.id and order_status.id = order_master.order_status and order_master.user_id = '$user_id' order by order_master.id desc";
  $res = mysqli_query($con,$sql);
}
?>
<div class="breadcrumb-area gray-bg">
        <div class="container">
            <div class="breadcrumb-content">
                <ul>
                    <li><a href="shop.php">Home</a></li>
                    <li class="active">Order history </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="about-us-area pt-50 pb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-7 d-flex align-items-center">
                    <div class="overview-content-2">
                        <h2>Welcome To <span>Your</span> Order history !</h2>
                        <table class="table ">
                          <thead>
                              <tr>
                                <th width="5%">Id</th>
                                <th width="15%">Name/Email/Mobile</th>
                                <th width="10%">Address/Zipcode</th>
                                <th width="5%">Price</th>
                                <th width="20%">Order List</th>
                                <th width="25%">Order Status</th>
                                <th width="15%">Payment Status</th>
                                <th width="5%">Added On</th>
                              </tr>
                          </thead>
                          <tbody>
                              <?php
                                if(mysqli_num_rows($res) > 0){
                                  while($row = mysqli_fetch_assoc($res)){
                              ?>
                                <tr>
                                  <td><?php echo $row['id'];?></td>
                                  <td>
                                    <p><?php echo $row['username'];?></p>
                                    <p><?php echo $row['email'];?></p>
                                    <p><?php echo $row['mobile'];?></p>
                                  </td>
                                  <td>
                                    <p><?php echo $row['address'];?></p>
                                    <p><?php echo $row['zipcode'];?></p>
                                  </td>
                                  <td><?php echo $row['total_price'];?></td>
                                  <td>
                                    <?php
                                      $getProductDetails = getProductDetails($row['id']);
                                      foreach ($getProductDetails as $list) {
                                        ?>
                                        <table class="table table-striped">
                                          <tr>
                                            <td><?php echo $list['product'] ?></td>
                                            <td><?php echo $list['attribute'] ?></td>
                                            <td><?php echo $list['price'] ?></td>
                                            <td><?php echo $list['qty'] ?></td>
                                          </tr>
                                        </table>
                                        <?php
                                      }
                                    ?>
                                </td>
                                <td><div class="order_status <?php echo $row['order_status_str']?>"><?php echo $row['order_status_str'];?></div></td>
                                <td>
                                    <div class="payment_status_<?php echo $row['payment_status_str'];?>"><?php echo $row['payment_status_str'];?></div>
                                </td>
                                <td><?php echo $row['added_on'];?></td>

                              </tr>
                                <?php }
                              }else {?>
                              <tr>
                                <td colspan="6"><?php echo "No User Found";}?></td>
                              </tr>
                          </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
include("footer.php");
?>
