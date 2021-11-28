<?php
session_start();
include "../admin/database.inc.php";
include "../admin/function.inc.php";
include "../admin/constant.inc.php";

  if(!isset($_SESSION['IS_DELIVARY_BOY_LOGIN'])){
    redirect('login.php');
  }
  $sql = "select order_master.*, payment_status.Payment_status as payment_status_str,order_status.status as order_status_str,delivary_boy.name from order_master,payment_status,order_status,delivary_boy where order_master.payment_status = payment_status.id and order_master.order_status = order_status.id and order_master.delivary_boy_id = delivary_boy.id and delivary_boy.id = '".$_SESSION['DELIVARY_ID']."' and order_master.order_status != '4' ";
  $res = mysqli_query($con,$sql);


  if(isset($_GET['id']) && $_GET['id'] > 0){
    $order_id = get_safe_value($_GET['id']);
    mysqli_query($con,"update order_master set order_status='4', payment_status='2' where id=$order_id and delivary_boy_id = '".$_SESSION['DELIVARY_ID']."' ");
    redirect('index.php');
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Manage Order-Amar Dokan</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../admin/assets/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../admin/assets/css/dataTables.bootstrap4.css">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../admin/assets/css/style.css">
</head>
<body class="sidebar-light">
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="navbar-menu-wrapper d-flex align-items-stretch justify-content-between">
        <ul class="navbar-nav mr-lg-2 d-none d-lg-flex">
          <li class="nav-item nav-toggler-item">

          </li>

        </ul>
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
          <a class="navbar-brand brand-logo" href="index.php"><img src="../admin/assets/images/logo-new.jpg" alt="logo"/></a>
          <a class="navbar-brand brand-logo-mini" href="index.php"><img src="../admin/assets/images/logo-new.jpg" alt="logo"/></a>
        </div>
        <ul class="navbar-nav navbar-nav-right">

          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="javascript:void()" data-toggle="dropdown" id="profileDropdown">
              <span class="nav-profile-name"><?php echo $_SESSION['DELIVARY_BOY']?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="logout.php">
                <i class="mdi mdi-logout text-primary"></i>
                Logout
              </a>
            </div>
          </li>
        </ul>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel" style="width:100%">
        <div class="content-wrapper">
<div class="card">
    <div class="card-body">
            <h1 class="grid_title">Order Master</h1>
            <div class="row grid_box">
              <div class="col-12">
                <div class="table-responsive">
                  <table id="order-listing" class="table">
                    <thead>
                      <tr>
                          <th width="10%">Id</th>
                          <th width="15%">Name/Mobile</th>
                          <th width="20%">Address/Zipcode</th>
                          <th width="10%">Price</th>
                          <th width="15%">Order Status</th>
                          <th width="15%">Payment Status</th>
                          <th width="15%">Added On</th>
                      </tr>
                    </thead>
                        <tbody>
                          <?php
                            if(mysqli_num_rows($res) > 0){
                              $i = 1;
                              while($row = mysqli_fetch_assoc($res)){
                                //prx($row);
                          ?>
                            <tr>
                              <td><?php echo $i;?></td>
                              <td>
                                <p><?php echo $row['username'];?></p>
                                <p><?php echo $row['mobile'];?></p>
                              </td>
                              <td>
                                <p><?php echo $row['address'];?></p>
                                <p><?php echo $row['zipcode'];?></p>
                              </td>
                              <td>
                                <?php echo $row['total_price'];
                                ?>
                              </td>
                              <td><a href="?id=<?php echo $row['id'];?>">Delivered</a></td>
                              <td>
                                <div class="payment_status_<?php echo $row['payment_status_str'];?>"><?php echo $row['payment_status_str'];?></div>
                              </td>
                              <td><?php echo $row['added_on'];?></td>
                          </tr>
                            <?php $i++;}
                          }else {?>
                          <tr>
                            <td colspan="6"><?php echo "No Order Found";}?></td>
                          </tr>
                      </tbody>
                  </table>
                    </div>
                </div>
            </div>
    </div>
</div>
</div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021 <a href="https://www.urbanui.com/" target="_blank">Urbanui</a>. All rights reserved.</span>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="../admin/assets/js/vendor.bundle.base.js"></script>
  <script src="../admin/assets/js/jquery.dataTables.js"></script>
  <script src="../admin/assets/js/dataTables.bootstrap4.js"></script>
  <!-- endinject -->
  <script src="../admin/assets/js/data-table.js"></script>
  <!-- End custom js for this page-->
</body>
</html>
