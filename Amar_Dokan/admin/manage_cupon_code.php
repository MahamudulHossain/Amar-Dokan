<?php
include('top.php');
$msg="";
$cupon_code="";
$cupon_type="";
$cupon_value="";
$cart_min_value="";
$expired_on="";
$id="";

if(isset($_GET['id']) && $_GET['id']>0){
	$id=get_safe_value($_GET['id']);
	$row=mysqli_fetch_assoc(mysqli_query($con,"select * from cupon_code where id='$id'"));
	$cupon_code=$row['cupon_code'];
	$cupon_type=$row['cupon_type'];
	$cupon_value=$row['cupon_value'];
	$cart_min_value=$row['cart_min_value'];
	$expired_on=$row['expired_on'];
}

if(isset($_POST['submit'])){
	$cupon_code=get_safe_value($_POST['cupon_code']);
	$cupon_type=get_safe_value($_POST['cupon_type']);
	$cupon_value=get_safe_value($_POST['cupon_value']);
	$cart_min_value=get_safe_value($_POST['cart_min_value']);
	$expired_on=get_safe_value($_POST['expired_on']);
	$added_on=date('Y-m-d h:i:s');

	if($id==''){
		$sql="select * from cupon_code where cupon_code='$cupon_code'";
	}else{
		$sql="select * from cupon_code where cupon_code='$cupon_code' and id!='$id'";
	}
	if(mysqli_num_rows(mysqli_query($con,$sql))>0){
		$msg="Cupon code already added";
	}else{
		if($id==''){

			mysqli_query($con,"insert into cupon_code(cupon_code,cupon_type,cupon_value,cart_min_value,expired_on,status,added_on) values('$cupon_code','$cupon_type','$cupon_value','$cart_min_value','$expired_on',1,'$added_on')");
		}else{
			mysqli_query($con,"update cupon_code set cupon_code='$cupon_code', cupon_type='$cupon_type' , cupon_value='$cupon_value', cart_min_value='$cart_min_value', expired_on='$expired_on' where id='$id'");
		}

		redirect('cupon_code.php');
	}
}
?>
<div class="row">
			<h1 class="grid_title ml10 ml15">Manage Cupon Code</h1>
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <form class="forms-sample" method="post">
                    <div class="form-group">
                      <label for="exampleInputName1">Cupon Code</label>
                      <input type="text" class="form-control" placeholder="Cupon Code" name="cupon_code" required value="<?php echo $cupon_code?>">
					                 <div class="error mt8"><?php echo $msg?></div>
                    </div>
					          <div class="form-group">
                      <label for="exampleInputName1">cupon Type</label>
                      <select name="cupon_type" required class="form-control">
						                  <option value="">Select Type</option>
						                        <?php
						                              $arr=array('P'=>'Percentage','F'=>'Fixed');
						                              foreach($arr as $key=>$val){
							                              if($key==$cupon_type){
								                                echo "<option value='".$key."' selected>".$val."</option>";
							                              }else{
								                                echo "<option value='".$key."'>".$val."</option>";
							                              }
                                          }
						                        ?>
					            </select>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail3">cupon Value</label>
                      <input type="textbox" class="form-control" placeholder="cupon Value" name="cupon_value" required  value="<?php echo $cupon_value?>">
                    </div>
					          <div class="form-group">
                      <label for="exampleInputEmail3">Cart Min Value</label>
                      <input type="textbox" class="form-control" placeholder="Cart Min Value" name="cart_min_value" required value="<?php echo $cart_min_value?>">
                    </div>
					          <div class="form-group">
                      <label for="exampleInputEmail3">Expired On</label>
                      <input type="date" class="form-control" placeholder="Expired On" name="expired_on"  value="<?php echo $expired_on?>">
                    </div>

                    <button type="submit" class="btn btn-primary mr-2" name="submit">Submit</button>
                  </form>
                </div>
              </div>
            </div>

		 </div>

<?php include('footer.php');?>
