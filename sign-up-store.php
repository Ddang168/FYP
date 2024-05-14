<?php

include 'db.php';


if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $address = $_POST['address'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $location = $_POST['location'];
   $location = filter_var($location, FILTER_SANITIZE_STRING);
   $password = md5($_POST['password']);
   $password = filter_var($password, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $select = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select = $conn->prepare("SELECT * FROM `store_owners` WHERE email = ?");
   $select->execute([$email]);

   if($select->rowCount() > 0){
      $message[] = 'user email already exist!';
   }
   //else{
      //if($password != $cpass){
         //$message[] = 'confirm password not matched!';
      else{
         $insert = $conn->prepare("INSERT INTO `users`(name, email, address, location, password, image) VALUES(?,?,?,?,?,?)");
         $insert->execute([$name, $email, $address, $location, $password, $image]);

         $insert = $conn->prepare("INSERT INTO `store_owners`(email, password, name, image) VALUES(?,?,?,?)");
         $insert->execute([$email, $password, $name, $image]);

         if($insert){
            if($image_size > 2000000){
               $message[] = 'image size is too large!';
            }else{
               move_uploaded_file($image_tmp_name, $image_folder);
               $message[] = 'registered successfully!';
               header('location:sign-in-store.php');
            }
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
 				<a href="sign-in-store.php" class="signup.image-link">
 					Already have an account?
 					Login In
 					
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
 					Register as Store Owner
 				</h2>
 				<form class="register-form" method="POST" action="" id="login-form">
 					<div class="form-group">
 						<label for="your_name">
 							<i class="zmdi zmdi-account material-icons-name">
 								
 							</i>
 							
 						</label>
 						<input type="text" name="name" id="name" placeholder="Enter your Store name" required="required">
 						
 					</div>

                    <div class="form-group">
                        <label for="your_email">
                            <i class="zmdi zmdi-account material-icons-name">
                                
                            </i>
                        </label>
                        <input type="email" name="email" id="email" placeholder="Enter your email" required="required">
                    </div>
                    <div class="form-group">
                  <label for="your_address">
                     <i class="zmdi zmdi-account   material-icons-name">
                        
                     </i>
                  </label>
                  <input type="text" name="address" id="address" placeholder="Enter your address" required="required">
               </div>


                <div>
                   <label for="your_location" name="location" id="location">
                       <i class="zmdi zmdi-pin"></i>
                   </label>
                   <select  name="location" id="location" required="required">
                       <option value="" disabled selected>Select your location</option>
                       <option value="Roehampton">Roehampton</option>
                       <option value="Croydon">Croydon</option>
                   </select>
               </div><p> <br><br>
              
 					<div>
 						<label for="your_pass">
 							<i class="zmdi zmdi-lock">
 								
 							</i>
 						</label>
 						<input type="password" name="password" id="password" placeholder="Enter your password" required="required">
 					</div>
                
                <div class="form-group">
                  <label for="your_image">
                     <i class="zmdi zmdi-account   material-icons-name">
                        
                     </i>
                  </label>
                  <input type="file" name="image" id="image" required="required" class="box" required accept="image/jpg, image/jpeg, image/png">
               </div>  

 					<div class="form-group form-button">
 						<input type="submit" name="submit" id="submit" class="form-submit" value="REGISTER">
 						
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