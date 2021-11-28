<?php include('top.php');

  $sql = "select products.*,category.category from products,category where products.category_id=category.id order by products.id asc";
  $res = mysqli_query($con,$sql);

  if(isset($_GET['type']) && $_GET['type']!=='' && isset($_GET['id']) && $_GET['id']>0){
    $type = $_GET['type'];
    $id = $_GET['id'];
    if($type == 'delete'){
      mysqli_query($con,"delete from products where id='$id'");
      redirect('product.php');
    }
    if($type == 'active' || $type == 'deactive' ){
      $status = 1;
      if($type=='deactive'){
        $status = 0;
      }
      mysqli_query($con,"update products set status='$status' where id='$id'");
      redirect('product.php');
    }
  }
?>
<div class="card">
    <div class="card-body">
            <h1 class="grid_title">Product Master</h1>
            <a href="manage_product.php" class="add_link font_size">Add Product</a>
            <div class="row grid_box">
              <div class="col-12">
                <div class="table-responsive">
                  <table id="order-listing" class="table">
                    <thead>
                      <tr>
                          <th width="10%">S.No #</th>
                          <th width="15%">Category</th>
                          <th width="20%">Product Name</th>
                          <th width="15%">Image</th>
                          <th width="15%">Added On</th>
                          <th width="25%">Actions</th>
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
                              <td><?php echo $row['category'];?></td>
                              <td><?php echo $row['product'];?></td>
                              <td><a target="_blank" href="<?php echo SITE_IMAGE_PATH.$row['image'];?>"><img src="<?php echo SITE_IMAGE_PATH.$row['image'];?>"></td>
                              <td><?php echo $row['added_on'];?></a></td>
                              <td>
                                <a href="manage_product.php?id=<?php echo $row['id'];?>"><label class="badge badge-success hand_cursor">Edit</label></a>&nbsp;&nbsp;
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
