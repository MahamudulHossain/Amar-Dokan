<?php include('top.php');

  $sql = "select order_master.*, payment_status.Payment_status as payment_status_str,order_status.status as order_status_str from order_master,payment_status,order_status where order_master.payment_status = payment_status.id and order_status.id = order_master.order_status order by order_master.id desc";
  $res = mysqli_query($con,$sql);

?>
<div class="card">
    <div class="card-body">
            <h1 class="grid_title">Order Master</h1>
            <div class="row grid_box">
              <div class="col-12">
                <div class="table-responsive">
                  <table id="order-listing" class="table">
                    <thead>
                      <tr>
                          <th width="10%">Id</th>
                          <th width="15%">Name/Email/Mobile</th>
                          <th width="20%">Address/Zipcode</th>
                          <th width="10%">Price</th>
                          <th width="15%">Order Status</th>
                          <th width="15%">Payment Status</th>
                          <th width="15%">Added On</th>
                      </tr>
                    </thead>
                        <tbody>
                          <?php
                            if(mysqli_num_rows($res) > 0){
                              while($row = mysqli_fetch_assoc($res)){
                          ?>
                            <tr>
                              <td><div class="order_details"><a href="order_detail.php?id=<?php echo $row['id'];?>"><?php echo $row['id'];?></a></div></td>
                              <td>
                                <p><?php echo $row['username'];?></p>
                                <p><?php echo $row['email'];?></p>
                                <p><?php echo $row['mobile'];?></p>
                              </td>
                              <td>
                                <p><?php echo $row['address'];?></p>
                                <p><?php echo $row['zipcode'];?></p>
                              </td>
                              <td>
                                <?php echo $row['total_price'];
                                if($row['cupon_code']!=''){
                                  echo "<br>";
                                  echo $row['cupon_code'];
                                }
                                ?>
                              </td>
                              <td><?php echo $row['order_status_str'];?></td>
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
<?php include('footer.php');?>
