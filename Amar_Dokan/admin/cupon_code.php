<?php include('top.php');

  $sql = "select * from cupon_code order by id desc";
  $res = mysqli_query($con,$sql);

  if(isset($_GET['type']) && $_GET['type']!=='' && isset($_GET['id']) && $_GET['id']>0){
    $type = $_GET['type'];
    $id = $_GET['id'];
    if($type == 'delete'){
      mysqli_query($con,"delete from cupon_code where id='$id'");
      redirect('cupon_code.php');
    }
    if($type == 'active' || $type == 'deactive' ){
      $status = 1;
      if($type=='deactive'){
        $status = 0;
      }
      mysqli_query($con,"update cupon_code set status='$status' where id='$id'");
      redirect('cupon_code.php');
    }
  }

?>
<div class="card">
    <div class="card-body">
            <h1 class="grid_title">Cupon Code Master</h1>
            <a href="manage_cupon_code.php" class="add_link font_size">Add Cupon Code</a>
            <div class="row grid_box">
              <div class="col-12">
                <div class="table-responsive">
                  <table id="order-listing" class="table">
                    <thead>
                      <tr>
                          <th width="5%">S.No #</th>
                          <th width="10%">Code</th>
                          <th width="5%">Type</th>
                          <th width="5%">Value</th>
                          <th width="15%">Cart Min Value</th>
                          <th width="15%">Added On</th>
                          <th width="15%">Expire Date</th>
                          <th width="30%">Actions</th>
                      </tr>
                    </thead>
                        <tbody>
                          <?php
                            if(mysqli_num_rows($res) > 0){
                              $i = 1;
                              while($row = mysqli_fetch_assoc($res)){
                          ?>
                            <tr>
                              <td><?php echo $i;?></td>
                              <td><?php echo $row['cupon_code'];?></td>
                              <td><?php echo $row['cupon_type'];?></td>
                              <td><?php echo $row['cupon_value'];?></td>
                              <td><?php echo $row['cart_min_value'];?></td>
                              <td><?php echo $row['added_on'];?></td>
                              <td><?php echo $row['expired_on'];?></td>
                              <td>
                                <a href="manage_cupon_code.php?id=<?php echo $row['id'];?>"><label class="badge badge-success hand_cursor">Edit</label></a>&nbsp;&nbsp;
                                <?php
                                  if($row['status']==1){
                                  ?>
                                  <a href="?id=<?php echo $row['id'];?>&type=deactive"><label class="badge badge-danger hand_cursor">Active</label></a>&nbsp;&nbsp;
                                  <?php
                                  }else{
                                  ?>
                                  <a href="?id=<?php echo $row['id'];?>&type=active"><label class="badge badge-info hand_cursor">Deactive</label></a>&nbsp;&nbsp;
                                  <?php
                                  }
                                ?>
                                <a href="?id=<?php echo $row['id'];?>&type=delete"><label class="badge badge-danger delete_red hand_cursor">Delete</label></a>&nbsp;&nbsp;
                            </td>
                          </tr>
                            <?php $i++;}
                         }else {?>
                          <tr>
                            <td colspan="7"><?php echo "No Data Found";}?></td>
                          </tr>
                      </tbody>
                  </table>
                    </div>
                </div>
            </div>
    </div>
</div>
<?php include('footer.php');?>
