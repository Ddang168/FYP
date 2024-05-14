<?php

@include 'db.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:sign-in.php');
}

?>




<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>LocalMart</title>
   <link rel="shortcut icon" type="images\png" href="images\logo.png"> 

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
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
    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="stylesheet" type="text/css" href="styles.css">
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <style>
       .placed-orders {
  padding: 20px;
}

.title {
  color: white;
  text-align: center;
}

.box-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
}

.box {
  background-color: #f5f5f5;
  border-radius: 8px;
  padding: 20px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  position: relative; /* Adding relative position to the box */
}

.box p {
  margin: 5px 0;
}

.box p span {
  font-weight: bold;
}

.drop-down {
  width: 100%;
  padding: 8px;
  margin-top: 10px;
}

.flex-btn {
  display: flex;
  justify-content: center; /* Centering the buttons */
  align-items: center;
  margin-top: 10px;
  position: absolute; /* Positioning the button group absolutely */
  bottom: 20px; /* Adjust this value to change the distance from the bottom */
  left: 50%;
  transform: translateX(-50%); /* Centering horizontally */
}

.option-btn,
.delete-btn {
  padding: 8px 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.option-btn {
  background-color: #007bff;
  color: white;
}

.delete-btn {
  background-color: #dc3545;
  color: white;
  text-decoration: none;
}

   </style>

</head>
<body style="background-color:  #03211B;">

    
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

      <!--header-section start-->
    <header class="header-section ">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="navbar navbar-expand-xl nav-shadow" id="#navbar">
                        <a class="navbar-brand" href="home.php"><img src="images/logo.png" width = "200px" class="logo" alt="logo"></a>
                        <a class="navbar-toggler" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                            <i class="bi bi-list"></i>
                        </a>

                        <div class="collapse navbar-collapse ms-auto " id="navbar-content">
                            <div class="main-menu">
                                <ul class="navbar-nav mb-lg-0 mx-auto">
                                     <li class="nav-item">
                                        <a class="nav-link" href="home.php">Home</a>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Shop by location</a>
                                        <ul class="dropdown-menu">
                                            <a href="roehamptonstore.php"><li class="dropdown-item" style="color: white;">
                                                Local Stores in Roehampton
                                            </li></a>

                                            <a href="croydonstore.php"><li class="dropdown-item" style="color: white;">
                                                Local Stores in Croydon
                                            </li></a>
                                        
                                        </ul>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="shop.php">Shop</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="order.php">Order</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="contact.php">Contact us</a>
                                    </li>
                                    

         
         <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
            $count_wishlist_items->execute([$user_id]);
         ?>
         
      </div>

      <div class="profile" class="nav-item">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
            $select_profile->execute([$user_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         

         <a style="color: white;" href="wishlist.php"><i class="fas fa-heart"></i><span>(<?= $count_wishlist_items->rowCount(); ?>)</span></a>
         <a style="color: white" href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $count_cart_items->rowCount(); ?>)</span></a>
         <a style="color: white" href="user_profile_update.php"  class="btn">Profile</a>
         <a style="color: white" href="logout.php" class="delete-btn">Logout</a>
         <div class="flex-btn">
         </div>
      </div>

   </div>


                                    
                                    
                                
                            
                                
                                <div class="nav-right d-none d-xl-block">
                  
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Offcanvas More info-->
    <div class="offcanvas offcanvas-end " tabindex="-1" id="offcanvasRight">
        <div class="offcanvas-body custom-nevbar">
            <div class="row">
                <div class="col-md-7 col-xl-8">
                    <div class="custom-nevbar__left">
                        <button type="button" class="close-icon d-md-none ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"><i class="bi bi-x"></i></button>
                        <ul class="custom-nevbar__nav mb-lg-0">
                            <li class="menu_item dropdown">
                                <a class="menu_link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Products </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" class="active" href="products.php"></a></li>
                                </ul>
                            </li>
                            <li class="menu_item dropdown">
                                <a class="menu_link dropdown-toggle" href="shop.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
                                <ul class="dropdown-menu">
                                </ul>
                            </li>
                             <li class="menu_item">
                                <a class="menu_link" href="contact.php">Contact us</a>
                            </li>
                            
                                
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-5 col-xl-4">
                    <div class="custom-nevbar__right">
                        <div class="custom-nevbar__top d-none d-md-block">
                            <button type="button" class="close-icon ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"><i class="bi bi-x"></i></button>
                            <div class="custom-nevbar__right-thumb mb-auto">
                                <img src="images/logo.png" alt="logo">
                            </div>
                        </div>
                        <ul class="custom-nevbar__right-location">
                            <li>
                              
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- header-section end-->
    <!-- hero section start-->
    
    <section class="hero hero--secondary">
      <div class="container">
         <div class="row gy-5 gy-lg-0 align-items-center justify-content-center justify-content-lg-between">
            <div class="col-12 col-lg-7 col-xxl-6">
               <div class="section__content">
                  
                        
                        </section>
               </div>

   

<section class="placed-orders">

   <h1 class="title" style="color:white;">placed orders</h1>

   <div class="box-container">

   <?php
      $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
      $select_orders->execute([$user_id]);
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <div class="box">
      <p> Date Placed: <span><?= $fetch_orders['placed_on']; ?></span> </p>
      <p> Name: <span><?= $fetch_orders['name']; ?></span> </p>
      <p> Phone Number: <span><?= $fetch_orders['number']; ?></span> </p>
      <p> Email: <span><?= $fetch_orders['email']; ?></span> </p>
      <p> Address: <span><?= $fetch_orders['address']; ?></span> </p>
      <p> Payment Method: <span><?= $fetch_orders['method']; ?></span> </p>
      <p> Order Details: <span><?= $fetch_orders['total_products']; ?></span> </p>
      <p> Total Price: <span>£<?= $fetch_orders['total_price']; ?></span> </p>
      <p> Order Status: <span style="color:<?php if($fetch_orders['payment_status'] == 'pending'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['payment_status']; ?></span> </p>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">no orders placed yet!</p>';
   }
   ?>

   </div>

</section>








    <footer class="footer">
        <div class="container">
            <div class="row section gy-5 gy-xl-0">
                <div class="col-12 col-sm-6 col-xl-3">
                <div class="about-company wow fadeinLeft" data-wow-duration="0.8s">
                    <div class="footer__logo mb-4">
                        <a href="">
                            <img src="images/logo.png" alt="logo" width="200px">
                        </a>
                        
                    </div>
                    <p>
                        Welcome to LocalMart - Your trust worthy partner Tailored for your foodwaste
                    </p>
                    <div class="social mt_32">
                        <a href="" class="btn_theme social_box">
                            <i class="bi bi-facebook">
                                
                            </i>
                            
                        </a>


                        <a href="" class="btn_theme social_box">
                            <i class="bi bi-instagram">
                                
                            </i>
                            
                        </a>


                        <a href="" class="btn_theme social_box">
                            <i class="bi bi-twitter">
                                
                            </i>
                            
                        </a>
                    </div>
                </div>
                
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="footer__contacts ms-sm-4 ms-xl-0 wow fadeInUp" data-wow-duration="0.8s">
                    <h4 class="footer__title mb-4">
                        Contacts
                    </h4>
                    <div class="footer__content">
                        <a href="">
                            <span class="btn_theme social_box">
                                <i class="bi bi-telephone-plus">
                                    <span>
                                        075757575754 
                                    </span>
                                </i>
                                
                            </span>
                        </a>
                        <a href="">
                            <span class="btn_theme social_box">
                                <i class="bi bi-envelope-open">
                                    <span>
                                        dd@gmail.com
                                    </span>
                                    
                                </i>
                                
                            </span>
                        </a>

                        <a>
                            <span class="btn_theme social_box">
                                <i class="bi bi-geo-alt">
                                    <span>
                                        United Kingdom
                                    </span>
                                    
                                </i>
                            </span>
                        </a>
                    </div>
                    
                </div>
            </div>
            <br>
            <br>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="newsletter wow fadeInDown" data-wow-duration="0.8s">
                    <h4 class="footer__title mb-4">
                        Newsletter
                    </h4>
                    <p class ="mb_32">
                        Subscribe to our newletter and get the latest updates
                    </p>
                    <form method="POST" id="frmSubscribe" autocomplete="off" class="newsletter__content-form">
                        <div class="input-group">
                            <input type="email" class="form-control" id="sMail" name="sMail" placeholder="Enter your mail" required>
                            <button type="submit" class="emailSubscribe btn_theme btn_theme_active" name="emailSubscribe" id="emailSubscribe">
                                <i class="bi bi-cursor">
                                    <span>
                                        
                                    </span>
                                </i>
                                
                            </button>
                        </div>
                        <span id="subscriberMsg">
                            
                        </span>
                        
                    </form>
                </div>
                
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="quick-link ms-sm-4 ms-xl-0 wow fadeinRight" data-wow-duration="0.8s">
                    <h4 class="footer__title mb-4">
                        QUICKLINK
                    </h4>
                    <ul>
                        <li>
                            <a href="home.php">
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="about-us.php">
                                About
                            </a>
                        </li>
                        <li>
                            <a href="contact.php">
                                Contact Us
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class ="col-12">
                <div class="footer__copyright">
                    <p class="copyright text-center">
                        Copyright
                        <span id="copyYear">
                            
                        </span>
                        <a href="" class="secondary_color">
                            LocalMart
                        </a>
                        <ul class="footer__copyright-condition">
                            <li>
                                <a href="">
                                    Help and Support
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    Privacy and Policy
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    Terms and Conditions
                                </a>
                            </li>
                        </ul>
                    </p>
                </div>
                
            </div>
            </div>
        </div>
    </footer>








<script src="js/script.js"></script>


  <script src="assets/js/jquery-3.6.3.min.js"></script>
    
    <script src="assets/js/plugins.js"></script>
    <!-- main js -->
    <script src="assets/js/main.js"></script>
    

</body>
</html>