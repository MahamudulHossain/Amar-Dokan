<?php include('header.php'); ?>

<div class="login-register-area pt-95 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-12 ml-auto mr-auto">
                <div class="login-register-wrapper">
                  <center>  <h4> Forgot Password </h4> </center>
                    <div class="tab-content">
                        <div id="lg1" class="tab-pane active">
                            <div class="login-form-container">
                                <div class="login-register-form">
                                  <form method="post" id="form_forget_password">
                                      <input type="email" name="user_email_forgot" placeholder="Email" required>
                                      <input type="hidden" name="type" value="forgot">
                                      <div class="button-box">
                                          <button type="submit" id="forgot_submit">LOGIN</button>
                                      </div>
                                      <div class="reg_success" id="success_msg"></div>
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
</div>

<?php include('footer.php'); ?>
