<?php

@include 'db.php';

session_start();

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $password = md5($_POST['password']);
   $password = filter_var($password, FILTER_SANITIZE_STRING);

   $sql = "SELECT * FROM `users` WHERE email = ? AND password = ?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$email, $password]);
   $rowCount = $stmt->rowCount();  

   $row = $stmt->fetch(PDO::FETCH_ASSOC);

   if($rowCount > 0){

      if($row['user_type'] == 'admin'){

         $_SESSION['admin_id'] = $row['id'];
         header('location:admin_page.php');

      }elseif($row['user_type'] == 'user'){

         $_SESSION['user_id'] = $row['id'];
         header('location:home.php');

      }else{
         $message[] = 'no user found!';
      }

   }else{
      $message[] = 'incorrect email or password!';
   }

}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>LocalMart | SIGN IN  </title>
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
                  <!--  This is from giphy I'm allowed to use it for personal use  -->
 					<iframe src="https://giphy.com/embed/l2SpMPQGyuSQy9TdC" width="600" height="270" frameBorder="0" class="giphy-embed" allowFullScreen></iframe><p><a href="https://giphy.com/gifs/southparkgifs-l2SpMPQGyuSQy9TdC"></a></p>
 				</figure>
 				<a href="sign-up-store.php" class="signup.image-link">
 					Don't have an account?
 					Create an account 
 				</a>
            <a href="sign-in.php"><div class="form-group form-button">
                  <input type="" name="" id="submit" class="form-submit" value="Sign In as Customer">
                  
               </div></a>

               <a href="./"><div class="form-group form-button">
                  <input type="" name="" id="submit" class="form-submit" value="Take me back to Home">
                  
               </div></a>

 			</div>
 			<div class="signin-form">
 				<h2 class="form-title">
 					Sign in as Store Owner
 				</h2>
 				<form class="register-form" method="POST" action="" id="login-form">
 					<div class="form-group">
 						<label for="your_name">
 							<i class="zmdi zmdi-account material-icons-name">
 								
 							</i>
 							
 						</label>
 						<input type="text" name="email" id="email" placeholder="Enter your Email" required="required">
 						
 					</div>
 					
 					<div class="form-group">
 						<label for="your_pass">
 							<i class="zmdi zmdi-lock">
 								
 							</i>
 						</label>
 						<input type="password" name="password" id="password" placeholder="Enter your Password" required="required">
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