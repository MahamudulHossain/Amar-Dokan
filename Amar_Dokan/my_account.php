<?php include('header.php');
if(!isset($_SESSION['USER_NAME'])){
  redirect('shop.php');
}
$getUserDetailsByid = getUserDetailsByid();
?>

<div class="breadcrumb-area gray-bg">
        <div class="container">
            <div class="breadcrumb-content">
                <ul>
                    <li><a href="shop.php">Home</a></li>
                    <li class="active">My Account </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- my account start -->
    <div class="myaccount-area pb-80 pt-100">
        <div class="container">
            <div class="row">
                <div class="ml-auto mr-auto col-lg-9">
                    <div class="checkout-wrapper">
                        <div id="faq" class="panel-group">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h5 class="panel-title"><span>1</span> <a data-toggle="collapse" data-parent="#faq" href="#my-account-1">Edit your account information </a></h5>
                                </div>
                                <div id="my-account-1" class="panel-collapse collapse show">
                                    <div class="panel-body">
                                      <form  method="post" id="frmMyAccount">
                                          <div class="billing-information-wrapper">
                                              <div class="account-info-wrapper">
                                                  <h4>My Account Information</h4>
                                                  <h5>Your Personal Details</h5>
                                              </div>
                                              <div class="row">
                                                  <div class="col-lg-6 col-md-6">
                                                      <div class="billing-info">
                                                          <label>Name</label>
                                                          <input type="text" name="name" value="<?php echo $getUserDetailsByid['name']?>" id="pro_name" required>
                                                      </div>
                                                  </div>
                                                  <div class="col-lg-6 col-md-6">
                                                      <div class="billing-info">
                                                          <label>Mobile</label>
                                                          <input type="text" name="mobile" value="<?php echo $getUserDetailsByid['mobile']?>" required>
                                                      </div>
                                                  </div>
                                                  <div class="col-lg-12 col-md-12">
                                                      <div class="billing-info">
                                                          <label>Email</label>
                                                          <input type="email" name="email" value="<?php echo $getUserDetailsByid['email']?>" readonly="readonly" required>
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="billing-back-btn">
                                                  <div class="billing-back">
                                                      <a href="#"><i class="ion-arrow-up-c"></i> back</a>
                                                  </div>
                                                  <div class="billing-btn">
                                                      <button type="submit" id="profile_submit">Save</button>
                                                      <input type="hidden" name="type" value="profile_update">
                                                  </div>
                                              </div>
                                              <div id="success_pro_msg"></div>
                                          </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h5 class="panel-title"><span>2</span> <a data-toggle="collapse" data-parent="#faq" href="#my-account-2">Change your password </a></h5>
                                </div>
                                <div id="my-account-2" class="panel-collapse collapse">
                                    <div class="panel-body">
                                      <form method="post" id="frmChngPass">
                                          <div class="billing-information-wrapper">
                                              <div class="account-info-wrapper">
                                                  <h4>Change Password</h4>
                                                  <h5>Your Password</h5>
                                              </div>
                                              <div class="row">
                                                  <div class="col-lg-12 col-md-12">
                                                      <div class="billing-info">
                                                          <label>Old Password</label>
                                                          <input type="password" name="old_password" required>
                                                      </div>
                                                  </div>
                                                  <div class="col-lg-12 col-md-12">
                                                      <div class="billing-info">
                                                          <label>New Password</label>
                                                          <input type="password" name="new_password" required>
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="billing-back-btn">
                                                  <div class="billing-back">
                                                      <a href="#"><i class="ion-arrow-up-c"></i> back</a>
                                                  </div>
                                                  <div class="billing-btn">
                                                      <button type="submit" id="pass_submit">Save</button>
                                                      <input type="hidden" name="type" value="password_update">
                                                  </div>
                                              </div>
                                              <div id="success_pass_msg"></div>
                                          </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php include('footer.php');?>
