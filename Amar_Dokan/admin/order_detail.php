<?php include('top.php');

if(isset($_GET['id']) && $_GET['id']>0){
  $id = $_GET['id'];
    if(isset($_GET['sid']) && $_GET['sid']>0){
      $sid = $_GET['sid'];
      mysqli_query($con,"update order_master set order_status='$sid' where id=$id");
      redirect(SITE_PATH."Amar_Dokan/admin/order_detail.php?id=".$id);
    }
    if(isset($_GET['dbid'])){
      $dbid = $_GET['dbid'];
      mysqli_query($con,"update order_master set delivary_boy_id='$dbid' where id=$id");
      redirect(SITE_PATH."Amar_Dokan/admin/order_detail.php?id=".$id);
    }

  $sql = "select order_master.*, payment_status.Payment_status as payment_status_str,order_status.status as order_status_str from order_master,payment_status,order_status where order_master.payment_status = payment_status.id and order_status.id = order_master.order_status and order_master.id='$id' order by order_master.id desc";
  $res = mysqli_query($con,$sql);
  if(mysqli_num_rows($res) > 0){
    $row = mysqli_fetch_assoc($res);
  }else{
    redirect('index.php');
  }
}else{
  redirect('index.php');
}


?>
  <div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="row p-5">
                        <div class="col-md-6">
                            <img src="https://i.ibb.co/6myys4W/logo-1.png">
                        </div>
                        <div class="col-md-6 text-right">
                            <p class="font-weight-bold mb-1"><h1>Invoice <?php echo $id?></h1></p>
                            <p class="text-muted"><h5><?php echo dateFormat($row['added_on']) ?></h5></p>
                        </div>
                    </div>


                    <div class="row pb-5 p-5">
                        <div class="col-md-6">
                            <p class="font-weight-bold mb-4"><h4>Client Information</h4></p>
                            <p class="mb-1"><?php echo $row['username']?></p>
                            <p><?php echo $row['email']?></p>
                            <p class="mb-1"><?php echo $row['address']?></p>
                            <p class="mb-1">+880<?php echo $row['mobile']?></p>
                        </div>
                    </div>

                    <div class="row p-5">
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="border-0 text-uppercase small font-weight-bold">ID</th>
                                        <th class="border-0 text-uppercase small font-weight-bold">Item</th>
                                        <th class="border-0 text-uppercase small font-weight-bold">Description</th>
                                        <th class="border-0 text-uppercase small font-weight-bold">Quantity</th>
                                        <th class="border-0 text-uppercase small font-weight-bold">Unit Cost</th>
                                        <th class="border-0 text-uppercase small font-weight-bold">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  $grandPrice = 0;
                                    $getProductDetails = getProductDetails($row['id']);
                                    $i = 1;
                                    foreach ($getProductDetails as $list) {
                                      $grandPrice = $grandPrice + ($list['qty']*$list['price']);
                                      ?>
                                    <tr>
                                        <td><?php echo $i?></td>
                                        <td><?php echo $list['product'] ?></td>
                                        <td><?php echo $list['attribute'] ?></td>
                                        <td><?php echo $list['qty'] ?></td>
                                        <td><?php echo $list['price'] ?></td>
                                        <td><?php echo $list['qty']*$list['price'] ?>Tk.</td>
                                    </tr>
                                    <?php
                                  $i++;
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-6">
                          <div>
                            <div class="mt-3"><h5>Order Status: <?php echo $row['order_status_str']?></h5></div>
                            <?php
                              $orderStatRes = mysqli_query($con,"select * from order_status order by status");
                            ?>
                            <select class="mt-3 form-control w200" name="order_stat" id="order_stat" onchange="changeOrderStatus()">
                              <option value="">Update Order Status</option>
                              <?php
                                while ($orderStatRow = mysqli_fetch_assoc($orderStatRes)) {
                                  echo "<option value=".$orderStatRow['id'].">".$orderStatRow['status']."</option>";
                                }
                              ?>
                            </select>
                          </div>
                          <div>
                            <div class="mt-3"><h5>Delivary Boy: <?php echo  getDelivaryBoyById($row['delivary_boy_id'])?></h5></div>
                            <?php
                              $delivaryBoyRes = mysqli_query($con,"select * from delivary_boy where status=1 order by name");
                            ?>
                            <select class="mt-3 form-control w200" name="dboy_stat" id="dboy_stat" onchange="changeDelivaryBoy()">
                              <option value="">Update Delivary Boy</option>
                              <?php
                                while ($delivaryBoyRow = mysqli_fetch_assoc($delivaryBoyRes)) {
                                  echo "<option value=".$delivaryBoyRow['id'].">".$delivaryBoyRow['name']."</option>";
                                }
                              ?>
                            </select>
                          </div>
                      </div>
                    </div>
                    <div class="d-flex flex-row-reverse bg-dark text-white p-4">
                        <div class="py-3 px-5 text-right">
                            <div class="mb-2">Grand Total</div>
                            <div class="h2 font-weight-light"><?php echo $grandPrice?>Tk.</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
                </div>
            </div>
    </div>
</div>
<script>
  function changeOrderStatus(){
    var order_stat = jQuery("#order_stat").val();
    var oid = "<?php echo $id?>";
    window.location.href = "<?php echo SITE_PATH?>Amar_Dokan/admin/order_detail.php?id="+oid+"&sid="+order_stat;
  }

  function changeDelivaryBoy(){
    var dboy_stat = jQuery("#dboy_stat").val();
    var oid = "<?php echo $id?>";
    window.location.href = "<?php echo SITE_PATH?>Amar_Dokan/admin/order_detail.php?id="+oid+"&dbid="+dboy_stat;
  }
</script>
<?php include('footer.php');?>
