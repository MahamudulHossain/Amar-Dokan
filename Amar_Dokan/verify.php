<?php include('header.php');
$msg="";
if(isset($_GET['verify_id']) && $_GET['verify_id'] != ""){
  $verify_id = $_GET['verify_id'];
  mysqli_query($con,"update user set verify= 1 where rand_str='$verify_id' ");
  $msg = "Congrats! Your account is verified";
}else{
  redirect('shop.php');
}

?>
      <div class="breadcrumb-area gray-bg">
              <div class="container">
                  <div class="breadcrumb-content">
                      <ul>
                          <li><a href="shop.php">Home</a></li>
                          <li class="active">verify</li>
                      </ul>
                  </div>
              </div>
          </div>
          <div class="contact-area pt-100 pb-100">
              <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="contact-message-wrapper">
                            <h4 class="contact-title"><?php echo $msg ?></h4>
                          </div>
                      </div>
                    </div>
                </div>
          </div>
<?php include('footer.php'); ?>
