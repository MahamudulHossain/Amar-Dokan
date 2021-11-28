<?php include('header.php'); ?>

<div class="login-register-area pt-95 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-12 ml-auto mr-auto">
                <div class="login-register-wrapper">
                    <div class="login-register-tab-list nav">
                        <a class="active" data-toggle="tab" href="#lg1">
                            <h4> login </h4>
                        </a>
                        <a data-toggle="tab" href="#lg2">
                            <h4> register </h4>
                        </a>
                    </div>
                    <div class="tab-content">
                        <div id="lg1" class="tab-pane active">
                            <div class="login-form-container">
                                <div class="login-register-form">
                                  <form method="post" id="form_login">
                                      <input type="email" name="user_email" placeholder="Email" required>
                                      <input type="password" name="user_password" placeholder="Password" required>
                                      <input type="hidden" name="type" value="login">
                                      <div class="button-box">
                                          <div class="login-toggle-btn">
                                              <a href="forget_password.php">Forgot Password?</a>
                                          </div>
                                          <button type="submit" id="login_submit">LOGIN</button>
                                      </div>
                                      <div class="reg_success" id="success_msg"></div>
                                  </form>
                                </div>
                            </div>
                        </div>
                        <div id="lg2" class="tab-pane">
                            <div class="login-form-container">
                                <div class="login-register-form">
                                    <form  method="post" id="formRegId">
                                      <input type="text" name="username" id="username" placeholder="Username" required>
                                        <input type="email" name="email" id="email" placeholder="Email" required>
                                        <div class="reg_error" id="email_error"></div>
                                        <input type="text" name="mobile" id="mobile" placeholder="Mobile" required>
                                        <input type="password" name="password" id="password" placeholder="Password" required>
                                        <input type="hidden" name="type" value="Register">
                                        <div class="button-box">
                                            <button type="submit" id="register_submit" name="submit">Register</button>
                                        </div>
                                        <div class="reg_success" id="email_success"></div>
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

<?php include('footer.php'); ?>
