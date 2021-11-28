<?php include('top.php');
$image="";
$short_title="";
$long_title="";
$link="";
$link_txt="";
$order_number="";
$msg="";
$required_status="required";
$img_error="";
$id="";

  if(isset($_GET['id']) && $_GET['id'] > 0){
    $id = $_GET['id'];
    $row = mysqli_fetch_assoc(mysqli_query($con,"select * from banner where id='$id'"));
    $image = $row['image'];
    $short_title = $row['short_title'];
    $long_title = $row['long_title'];
    $link = $row['link'];
    $link_txt = $row['link_txt'];
    $order_number = $row['order_number'];
    $required_status="";
  }
  if(isset($_POST['submit'])){
    $short_title = get_safe_value($_POST['short_title']);
    $long_title = get_safe_value($_POST['long_title']);
    $link = get_safe_value($_POST['link']);
    $link_txt = get_safe_value($_POST['link_txt']);
    $order_number = get_safe_value($_POST['order_number']);
    $added = date('Y-m-d');
      if($id==""){
        $type= $_FILES['image']['type'];
        if($type !="image/jpeg" && $type !="image/png"){
          $img_error="Invalid Image Format(Choose jpeg/png)";
        }else{
          $image = rand(11111111,9999999)."_".$_FILES['image']['name'];
          move_uploaded_file($_FILES['image']['tmp_name'],SERVER_IMAGE_PATH.$image);
          mysqli_query($con,"insert into banner(image,short_title,long_title,link,link_txt,order_number,status,added_on) values('$image','$short_title','$long_title','$link','$link_txt','$order_number',1,'$added')");
          }
      }else{
        $type= $_FILES['image']['type'];
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
        mysqli_query($con,"update banner set short_title='$short_title', long_title='$long_title',link='$link',link_txt='$link_txt',order_number='$order_number' $image_condition where id='$id'");
      }
      redirect('banner.php');

  }
?>

<div class="row">
			<h1 class="grid_title ml10 ml15">Manage Banner</h1>
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <form class="forms-sample" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                      <label for="exampleInputName1">Image</label>
                      <input type="file" class="form-control" placeholder="Image" name="image" <?php echo $required_status?>>
					                 <div class="error mt8"><?php echo $msg?></div>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputName1">Short Title</label>
                      <input type="text" class="form-control" placeholder="Short Title" name="short_title" required value="<?php echo $short_title?>">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputName1">Long Title</label>
                      <input type="text" class="form-control" placeholder="Long Title" name="long_title" required value="<?php echo $long_title?>">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputName1">Link</label>
                      <input type="text" class="form-control" placeholder="Link" name="link" required value="<?php echo $link?>">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputName1">Link Text</label>
                      <input type="text" class="form-control" placeholder="Link Text" name="link_txt" required value="<?php echo $link_txt?>">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputName1">Order Number</label>
                      <input type="text" class="form-control" placeholder="Order Number" name="order_number" required value="<?php echo $order_number?>">
                    </div>
                    <button type="submit" class="btn btn-primary mr-2" name="submit">Submit</button>
                  </form>
                </div>
              </div>
            </div>
		 </div>

<?php include('footer.php');?>
