<?php include('top.php');
$category_id="";
$product="";
$product_details="";
$image="";
$msg="";
$required_status="required";
$img_error="";
$id="";

  if(isset($_GET['id']) && $_GET['id'] > 0){
    $id = $_GET['id'];
    $row = mysqli_fetch_assoc(mysqli_query($con,"select * from products where id='$id'"));
    $category_id = $row['category_id'];
    $product = $row['product'];
    $product_details = $row['product_details'];
    $image = $row['image'];
    $required_status="";
  }

  if(isset($_GET['pro_id']) && $_GET['pro_id']>0){
    $product_id = $_GET['pro_id'];
    $id = $_GET['id'];
    mysqli_query($con,"delete from product_details where id='$product_id' ");
    redirect('manage_product.php?id='.$id);
  }
  if(isset($_POST['submit'])){
    $category_id = get_safe_value($_POST['category_id']);
    $product = get_safe_value($_POST['product']);
    $product_details = get_safe_value($_POST['product_details']);
    $added = date('Y-m-d');
    if($id==""){
      $sql ="select * from products where product='$product'";
    }else{
      $sql ="select * from products where product='$product' and id!='$id'";
    }
    if(mysqli_num_rows(mysqli_query($con,$sql)) > 0){
      $msg ="Product already added";
    }else{
      $type= $_FILES['image']['type'];
      if($id==""){
        if($type !="image/jpeg" && $type !="image/png"){
          $img_error="Invalid Image Format(Choose jpeg/png)";
        }else{
          $image = rand(11111111,9999999)."_".$_FILES['image']['name'];
          move_uploaded_file($_FILES['image']['tmp_name'],SERVER_IMAGE_PATH.$image);
          mysqli_query($con,"insert into products(category_id,product,product_details,image,status,added_on) values('$category_id','$product','$product_details','$image',1,'$added')");

          $attributeArr = $_POST['attribute'];
          $priceArr = $_POST['price'];
          $pid = mysqli_insert_id($con);
          foreach ($attributeArr as $key => $value) {
            $attribute = $value;
            $price = $priceArr[$key];
            mysqli_query($con,"insert into product_details(product_id,attribute,price,status,added_on)
            values('$pid','$attribute','$price',1,'$added')");
          }
          redirect('product.php');
        }
      }else{
          $image_condition="";
          if($_FILES['image']['name'] !=""){
            if($type !="image/jpeg" && $type !="image/png"){
              $img_error="Invalid Image Format(Choose jpeg/png)";
            }else{
              $image = rand(11111111,9999999)."_".$_FILES['image']['name'];
              $old_image = mysqli_fetch_assoc(mysqli_query($con,"select image from products where id='$id'"));
              unlink(SERVER_IMAGE_PATH.$old_image['image']);
              move_uploaded_file($_FILES['image']['tmp_name'],SERVER_IMAGE_PATH.$image);
              $image_condition =", image='$image'";
            }
        }
         $sql1 ="update products set category_id='$category_id', product='$product', product_details='$product_details' $image_condition where id='$id'";
        mysqli_query($con,$sql1);

        $attributeArr = $_POST['attribute'];
        $priceArr = $_POST['price'];
        $statusArr = $_POST['status'];
        $productDetailsIdArr = $_POST['product_details_id'];

        foreach ($attributeArr as $key => $value) {
          $attribute = $value;
          $price = $priceArr[$key];
          $status = $statusArr[$key];
          if(isset($productDetailsIdArr[$key])){
            $pro_id = $productDetailsIdArr[$key];
            mysqli_query($con,"update product_details set attribute='$attribute',price='$price',status='$status' where id='$pro_id' ");
          }else{
            mysqli_query($con,"insert into product_details(product_id,attribute,price,status,added_on)
            values('$id','$attribute','$price','$status','$added')");
          }
        }
        redirect('product.php');
      }

    }
  }

$category_res =mysqli_query($con,"select * from category where status='1' order by category asc");
?>
<div class="row">
			<h1 class="grid_title ml10 ml15">Manage Product</h1>
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <form class="forms-sample" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                      <label for="exampleInputName1">Category Name</label>
                      <select class="form-control" name="category_id" required>
                        <option value="">Select Category</option>
                          <?php
                            while($cat_row = mysqli_fetch_assoc($category_res)){
                              if($cat_row['id']==$category_id){
                  								echo "<option value='".$cat_row['id']."' selected>".$cat_row['category']."</option>";
                  							}else{
                  								echo "<option value='".$cat_row['id']."'>".$cat_row['category']."</option>";
                  							}
                            }
                            ?>
                      </select>
					                 <div class="error mt8"><?php echo $msg?></div>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail3">Product Name</label>
                      <input type="textbox" class="form-control" placeholder="Product Name" name="product" required value="<?php echo $product?>">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail3">Product Details</label>
                      <input type="textbox" class="form-control" placeholder="Product Details" name="product_details" required value="<?php echo $product_details?>">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail3">Product Image</label>
                      <input type="file" class="form-control" name="image"<?php echo $required_status?>>
                      <div class="error mt8"><?php echo $img_error?></div>
                    </div>
                    <div class="form-group" id="add_box1">
                      <label for="exampleInputEmail3">Product Attributes</label>
                      <?php if($id==0){ ?>
                              <div class="row mt8">
                                  <div class="col-4">
                                    <input type="textbox" class="form-control" placeholder="Product Arrtibute" name="attribute[]">
                                  </div>
                                  <div class="col-3">
                                    <input type="textbox" class="form-control" placeholder="Product Price" name="price[]">
                                  </div>
                                  <div class="col-3">
                                    <select class="form-control" name="status[]" required>
                                      <option value="">Select Status</option>
                                      <option value="1">Active</option>
                                      <option value="0">Deactive</option>
                                    </select>
                                  </div>
                              </div>
                        <?php } else{
                              $product_details_res=mysqli_query($con,"select * from product_details where product_id='$id' ");
                              $ii=1;
                              while($product_details_row = mysqli_fetch_assoc($product_details_res)){
                                ?>
                                <div class="row mt8">
                                    <div class="col-4">
                                      <input type="hidden" name="product_details_id[]" value="<?php echo $product_details_row['id']?>">
                                      <input type="textbox" class="form-control" placeholder="Product Arrtibute" name="attribute[]" value="<?php echo $product_details_row['attribute']?>">
                                    </div>
                                    <div class="col-3">
                                      <input type="textbox" class="form-control" placeholder="Product Price" name="price[]" value="<?php echo $product_details_row['price']?>">
                                    </div>
                                    <div class="col-3">
                                      <select class="form-control" name="status[]">
                                        <option value="">Select Status</option>
                                        <?php if($product_details_row['status'] == 1){?>
                                          <option value="<?php  echo $product_details_row['status']?>" selected >Active</option>
                                          <option value="0">Deactive</option>
                                        <?php }?>
                                        <?php if($product_details_row['status'] == 0){?>
                                          <option value="1">Active</option>
                                          <option value="<?php  echo $product_details_row['status']?>" selected >Deactive</option>
                                        <?php }?>
                                      </select>
                                    </div>
                                    <?php if ($ii !=1){?>
                                    <div class="col-2"><input type="button" class="btn btn-success" onclick="removemore_new(<?php echo $product_details_row['id']?>)" value="Remove">
                                    </div>
                                  <?php }?>
                                </div>
                            <?php $ii++; } } ?>
                    </div>
                    <button type="submit" class="btn btn-primary mr-2" name="submit">Submit</button>
                    <input type="button" class="btn btn-success" onclick="addmore()" value="Add More">
                  </form>
                </div>
              </div>
            </div>
            <input type="hidden" id= "hid_box_num" value="1">
		 </div>
     <script type="text/javascript">
       function addmore(){
         var hid_box_num = jQuery('#hid_box_num').val();
         hid_box_num++;
         jQuery('#hid_box_num').val(hid_box_num);

         var html = '<div class="row mt8" id="box'+hid_box_num+'"><div class="col-4"><input type="textbox" class="form-control" placeholder="Product Arrtibute" name="attribute[]"></div><div class="col-3"><input type="textbox" class="form-control" placeholder="Product Price" name="price[]"></div><div class="col-3"><select class="form-control" name="status[]" required><option value="">Select Status</option><option value="1">Active</option><option value="0">Deactive</option></select></div><div class="col-2"><input type="button" class="btn btn-success" onclick=removemore("'+hid_box_num+'") value="Remove"></div></div>';
         jQuery('#add_box1').append(html);
       }

       function removemore(num){
         jQuery("#box"+num).remove();
       }
       function removemore_new(id){
         var curr_path = window.location.href;
          window.location.href = curr_path+'&pro_id='+id;
       }
     </script>

<?php include('footer.php');?>
