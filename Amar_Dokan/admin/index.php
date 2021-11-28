<?php include('top.php');?>

<div class="row">
	<div class="col-md-6 col-lg-3 grid-margin stretch-card">
	  <div class="card">
		<div class="card-body">
		  <h1 class="font-weight-light mb-4">
			<?php
			$start=date('Y-m-d'). ' 00-00-00';
			$end=date('Y-m-d'). ' 23-59-59';
			echo getSale($start,$end);
			?>
		  </h1>
		  <div class="d-flex flex-wrap align-items-center">
			<div>
			  <h4 class="font-weight-normal">Total Sale</h4>

			</div>
			<i class="mdi mdi-shopping icon-lg text-primary ml-auto"></i>
		  </div>
		</div>
	  </div>
	</div>
	<div class="col-md-6 col-lg-3 grid-margin stretch-card">
	  <div class="card">
		<div class="card-body">
		  <h1 class="font-weight-light mb-4">
		    <?php
			$start=strtotime(date('Y-m-d'));
			$start=strtotime("-7 day",$start);
			$start=date('Y-m-d',$start);
			$end=date('Y-m-d'). ' 23-59-59';
			echo getSale($start,$end);
			?>
		  </h1>
		  <div class="d-flex flex-wrap align-items-center">
			<div>
			  <h4 class="font-weight-normal">7 Days Sale</h4>
			  <p class="text-muted mb-0 font-weight-light">Last 7 Days Sale</p>
			</div>
			<i class="mdi mdi-shopping icon-lg text-danger ml-auto"></i>
		  </div>
		</div>
	  </div>
	</div>
	<div class="col-md-6 col-lg-3 grid-margin stretch-card">
	  <div class="card">
		<div class="card-body">
		  <h1 class="font-weight-light mb-4">
		  <?php
			$start=strtotime(date('Y-m-d'));
			$start=strtotime("-30 day",$start);
			$start=date('Y-m-d',$start);
			$end=date('Y-m-d'). ' 23-59-59';
			echo getSale($start,$end);
			?>
		  </h1>
		  <div class="d-flex flex-wrap align-items-center">
			<div>
			  <h4 class="font-weight-normal">30 Days Sale</h4>
			  <p class="text-muted mb-0 font-weight-light">Last 30 Days Sale</p>
			</div>
			<i class="mdi mdi-shopping icon-lg text-info ml-auto"></i>
		  </div>
		</div>
	  </div>
	</div>
	<div class="col-md-6 col-lg-3 grid-margin stretch-card">
	  <div class="card">
		<div class="card-body">
		  <h1 class="font-weight-light mb-4">
		  <?php
			$start=strtotime(date('Y-m-d'));
			$start=strtotime("-365 day",$start);
			$start=date('Y-m-d',$start);
			$end=date('Y-m-d'). ' 23-59-59';
			echo getSale($start,$end);
			?>
		  </h1>
		  <div class="d-flex flex-wrap align-items-center">
			<div>
			  <h4 class="font-weight-normal">365 Days Sale</h4>
			  <p class="text-muted mb-0 font-weight-light">Last 365 Days Sale</p>
			</div>
			<i class="mdi mdi-shopping icon-lg text-success ml-auto"></i>
		  </div>
		</div>
	  </div>
	</div>
	<div class="col-md-6 col-lg-3 grid-margin stretch-card">
	  <div class="card">
		<div class="card-body">
		  <h1 class="font-weight-light mb-4">
			<?php
			$row=mysqli_fetch_assoc(mysqli_query($con,"SELECT count(order_detail.product_detail_id) as t, products.product from order_detail,product_details,products WHERE order_detail.product_detail_id=product_details.id and product_details.product_id=products.id group by order_detail.product_detail_id order by count(order_detail.product_detail_id) desc limit 1"));
			echo $row['product'];
			echo "<br/>";
			echo '<span style="font-size:15px;">('.$row['t'].' Times)</span>';
			?>
		  </h1>
		  <div class="d-flex flex-wrap align-items-center">
			<div>
			  <h4 class="font-weight-normal">Most Liked Dish</h4>

			</div>
			<i class="mdi mdi-food icon-lg text-primary ml-auto"></i>
		  </div>
		</div>
	  </div>
	</div>

	<div class="col-md-6 col-lg-3 grid-margin stretch-card">
	  <div class="card">
		<div class="card-body">
		  <h1 class="font-weight-light mb-4">
			<?php
			$row=mysqli_fetch_assoc(mysqli_query($con,"select count(order_master.user_id) as t,user.username from order_master,user WHERE order_master.user_id=user.id GROUP BY order_master.user_id order by count(order_master.user_id) desc limit 1"));
			echo $row['username'];
			echo "<br/>";
			echo '<span style="font-size:15px;">('.$row['t'].' Times)</span>';
			?>
		  </h1>
		  <div class="d-flex flex-wrap align-items-center">
			<div>
			  <h4 class="font-weight-normal">Most Active User</h4>

			</div>
			<i class="mdi mdi-account icon-lg text-primary ml-auto"></i>
		  </div>
		</div>
	  </div>
	</div>
  </div>
  <?php
  $sql= "select order_master.*, payment_status.Payment_status as payment_status_str,order_status.status as order_status_str from order_master,payment_status,order_status where order_master.payment_status = payment_status.id and order_status.id = order_master.order_status order by order_master.id desc limit 5";
$res=mysqli_query($con,$sql);
  ?>
  <div class="row">
	<div class="col-12">
	  <div class="card">
		<div class="card-body">
		  <h4 class="card-title">Latest 5 Order</h4>
		  <div class="table-responsive">
			<table class="table table-hover">
			  <thead>
				<tr>
				   <th width="5%">Order Id</th>
					<th width="25%">Name/Email/Mobile</th>
					<th width="25%">Address/Zipcode</th>
					<th width="5%">Price</th>
					<th width="10%">Payment Status</th>
					<th width="10%">Order Status</th>
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
