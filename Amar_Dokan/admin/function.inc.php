<?php
    function pr($arr){
    	echo '<pre>';
    	print_r($arr);
    }

    function prx($arr){
    	echo '<pre>';
    	print_r($arr);
    	die();
    }
    function get_safe_value($str){
	     global $con;
	     $str=mysqli_real_escape_string($con,$str);
	     return $str;
   }
   function redirect($link){
    	  ?>
      	<script>
       	window.location.href='<?php echo $link?>';
      	</script>
      	<?php
      	die();
   }
   function getProductDetails($oid){
     global $con;
     $sql = "SELECT order_detail.price,order_detail.qty,product_details.attribute,products.product
              FROM order_detail,product_details,products
              WHERE order_detail.order_id=$oid AND
              order_detail.product_detail_id = product_details.id AND
              product_details.product_id = products.id";
      $res = mysqli_query($con,$sql);
      $data = array();
      while($row = mysqli_fetch_assoc($res)){
        $data[]  = $row;
      }
      return $data;
   }

   function dateFormat($daTe){
     $str = strtotime($daTe);
     $dateForm = date('d-m-Y',$str);
     return $dateForm;
   }

   function getDelivaryBoyById($id){
      global $con;
      $res= mysqli_query($con,"select * from delivary_boy where id='$id'");
      if(mysqli_num_rows($res)>0){
        $row = mysqli_fetch_assoc($res);
        return $row['name']."(+880".$row['mobile'].")";
      }else{
        return "Not Assigned Yet";
      }

   }

   function getSale($start,$end){
    	global $con;
    	$sql="select sum(total_price) as total_price from order_master where added_on between '$start' and '$end' and order_status=4";
    	$res=mysqli_query($con,$sql);

    	while($row=mysqli_fetch_assoc($res)){
    		return $row['total_price'].' Tk';
    	}
}

 ?>
