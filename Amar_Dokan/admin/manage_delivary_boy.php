<?php include('top.php');
$name="";
$mobile="";
$msg="";
$id="";

  if(isset($_GET['id']) && $_GET['id'] > 0){
    $id = $_GET['id'];
    $row = mysqli_fetch_assoc(mysqli_query($con,"select * from delivary_boy where id='$id'"));
    $name = $row['name'];
    $mobile = $row['mobile'];
  }
  if(isset($_POST['submit'])){
    $name = get_safe_value($_POST['name']);
    $mobile = get_safe_value($_POST['mobile']);
    $password = mysqli_real_escape_string($con,md5($_POST['password']));
    $added = date('Y-m-d');
    if($id==""){
      $sql ="select * from delivary_boy where mobile='$mobile'";
    }else{
      $sql ="select * from delivary_boy where mobile='$mobile' and id!='$id'";
    }
    if(mysqli_num_rows(mysqli_query($con,$sql)) > 0){
      $msg ="Delivary boy already added";
    }else{
      if($id==""){
      mysqli_query($con,"insert into delivary_boy(name,mobile,password,status,added_on) values('$name','$mobile','$password',1,'$added')");
      }else{
        mysqli_query($con,"update delivary_boy set name='$name', mobile='$mobile' where id='$id'");
      }
      redirect('delivary_boy.php');
    }
  }
?>
<div class="row">
			<h1 class="grid_title ml10 ml15">Manage Delivary Boy</h1>
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <form class="forms-sample" method="post">
                    <div class="form-group">
                      <label for="exampleInputName1">Name</label>
                      <input type="text" class="form-control" placeholder="Delivary Boy Name" name="name" required value="<?php echo $name?>">
					                 <div class="error mt8"><?php echo $msg?></div>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail3">Mobile Number</label>
                      <input type="textbox" class="form-control" placeholder="Mobile Number" required name="mobile"  value="<?php echo $mobile?>">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail3">Password</label>
                      <input type="password" class="form-control" placeholder="Password" required name="password" ">
                    </div>
                    <button type="submit" class="btn btn-primary mr-2" name="submit">Submit</button>
                  </form>
                </div>
              </div>
            </div>
		 </div>

<?php include('footer.php');?>
