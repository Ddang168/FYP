<?php

include 'db.php';

if(isset($_POST['submit'])){

   $fname = $_POST['fname'];
   $fname = filter_var($fname, FILTER_SANITIZE_STRING);

   $username = $_POST['username'];
   $username = filter_var($username, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $password = md5($_POST['password']);
   $password = filter_var($password, FILTER_SANITIZE_STRING);
   

   

   $select = $conn->prepare("SELECT * FROM `user` WHERE email = ?");
   $select->execute([$email]);

   if($select->rowCount() > 0){
      $message[] = 'user email already exist!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         $insert = $conn->prepare("INSERT INTO `user`(fname, username, email, password) VALUES(?,?,?,?)");
         $insert->execute([$fname, $username, $email, $password]);

               $message[] = 'registered successfully!';
               header('location:sign-in.php');
            }
         }

      }
   

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>LocalMart | SIGN UP  </title>
	<link rel="shortcut icon" type="images\png" href="images\logo.png"> 
	<!--  css dependencies start  -->

    <!-- bootstrap five css -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- bootstrap-icons css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- nice select css -->
    <link rel="stylesheet" href="assets/css/nice-select.css">
    <!-- magnific popup css -->
    <link rel="stylesheet" href="assets/css/magnific-popup.css">
    <!-- slick css -->
    <link rel="stylesheet" href="assets/css/slick.css">
    <!-- odometer css -->
    <link rel="stylesheet" href="assets/css/odometer.css">
    <!-- animate css -->
    <link rel="stylesheet" href="assets/css/animate.css">
    <!--  / css dependencies end  -->

    <!-- main css -->
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body align="center" style="background-color: #03211B;">


   <?php

if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span style="color: white;">'.$message.'</span>
         <i style="color: white;" class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}

?>

<!--  Preloader  -->
    <div class="preloader">
        <span class="loader"></span>
    </div>

 <center>
  <section class="hero hero--secondary" class="sign-in">
 	<div class="container">
 		<div class="signin-content">
 			<div class="signin-image">
 				<figure>
 					<img src="images/signup.jpg">
 				</figure>
 				<a href="sign-in.php" class="signup.image-link">
 					Already have an account?
 					Login In
 					
 				</a>
 				
 			</div>
 			<div class="signin-form">
 				<h2 class="form-title">
 					Sign Up as Customer 
 				</h2>
 				<form class="register-form" method="POST" action="" id="login-form">
 					<div class="form-group">
 						<label for="your_name">
 							<i class="zmdi zmdi-account material-icons-name">
 								
 							</i>
 							
 						</label>
 						<input type="text" name="fname" id="fname" placeholder="Enter your full name" required="required">
 						
 					</div>
 					<div class="form-group">
 						<label for="your_username">
 							<i class="zmdi zmdi-account	material-icons-name">
 								
 							</i>
 						</label>
 						<input type="text" name="username" id="username" placeholder="Enter your username" required="required">
 					</div>
                    <div class="form-group">
                        <label for="your_email">
                            <i class="zmdi zmdi-account material-icons-name">
                                
                            </i>
                        </label>
                        <input type="text" name="email" id="email" placeholder="Enter your email" required="required">
                    </div>

 					<div class="form-group">
 						<label for="your_pass">
 							<i class="zmdi zmdi-lock">
 								
 							</i>
 						</label>
 						<input type="password" name="password" id="password" placeholder="Enter your password" required="required">
 					</div>
                    
 					<div class="form-group form-button">
 						<input type="submit" name="submit" id="submit" class="form-submit" value="login">
 						
 					</div>


 				</form>

 				
 			</div>
 			
 		</div>

 	</div>


 </section>
</center>




  <script src="assets/js/jquery-3.6.3.min.js"></script>
    
    <script src="assets/js/plugins.js"></script>
    <!-- main js -->
    <script src="assets/js/main.js"></script>
    

</body>
</html>