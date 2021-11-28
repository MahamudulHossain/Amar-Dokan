<?php
session_start();
include "database.inc.php";
include "function.inc.php";
$msg = " ";
  if(isset($_POST['submit'])){
    $username = get_safe_value($_POST['username']);
    $password = get_safe_value($_POST['password']);
    $sql = "select * from admin_login where username='$username' and password='$password'";
    $res = mysqli_query($con,$sql);
      if(mysqli_num_rows($res) > 0){
        $row  = mysqli_fetch_assoc($res);
        $_SESSION['IS_LOGIN'] = "yes";
        $_SESSION['name'] = $row['username'];
          redirect('index.php');
      }else{
        $msg = "Please enter valid login details";
      }
  }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin Panel Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/design.css">
  </head>
  <body>
    <div class="container">
     <div class="row">
         <div class="col-lg-3 col-md-2"></div>
         <div class="col-lg-6 col-md-8 login-box">
             <div class="col-lg-12 login-key">
                 <i class="fa fa-key" aria-hidden="true"></i>
             </div>
             <div class="col-lg-12 login-title">
                 ADMIN PANEL
             </div>

             <div class="col-lg-12 login-form">
                 <div class="col-lg-12 login-form">
                     <form method="post">
                         <div class="form-group">
                             <label class="form-control-label">USER NAME</label>
                             <input type="text" class="form-control" name="username">
                         </div>
                         <div class="form-group">
                             <label class="form-control-label">PASSWORD</label>
                             <input type="password" class="form-control" name="password" >
                         </div>

                         <div class="col-lg-12 loginbttm">
                             <div class="col-lg-6 login-btm login-text">
                                 <!-- Error Message -->
                                 <?php echo $msg;?>
                             </div>
                             <div class="col-lg-6 login-btm login-button">
                                 <button type="submit" name="submit" class="btn btn-outline-primary">LOGIN</button>
                             </div>
                         </div>
                     </form>
                 </div>
             </div>
             <div class="col-lg-3 col-md-2"></div>
         </div>
     </div>
  </body>
</html>
