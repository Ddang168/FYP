<?php
@include 'db.php';

session_start();

$user_id = $_SESSION['user_id'];

// Check if the user is logged in
if (!isset($user_id)) {
   header('location:sign-in.php');
   exit; // Exit to prevent further execution
}

if (isset($_POST['order'])) {
   // Retrieve order details from the form
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   // Concatenate address components
   $address = 'house no. ' . $_POST['house'] . ' ' . $_POST['street'] . ' ' . $_POST['city'] . ' ' . $_POST['state'] . ' ' . $_POST['country'] . ' - ' . $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $placed_on = date('d-M-Y');

   // Initialize variables for cart total and products
   $cart_total = 0;
   $cart_products = array();

   // Retrieve cart items for the user
   $cart_query = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $cart_query->execute([$user_id]);

   if ($cart_query->rowCount() > 0) {
      while ($cart_item = $cart_query->fetch(PDO::FETCH_ASSOC)) {
         // Retrieve store name from cart item
         $store_name = $cart_item['store_name'];
         // Construct array of cart products
         $cart_products[] = array(
            'name' => $cart_item['name'],
            'quantity' => $cart_item['quantity'],
            'store_name' => $store_name // Store name added here
         );
         // Calculate subtotal for the cart item
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      }
   }

   // Convert array of cart products to JSON string
   $total_products = json_encode($cart_products);

   // Prepare and execute query to insert order into `orders` table
   $insert_order = $conn->prepare("INSERT INTO `orders` (user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
   $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $cart_total, $placed_on]);

   // Delete cart items for the user after placing the order
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart->execute([$user_id]);

   $message = 'Order placed successfully!, We will get back to you shortly...';
}


?>





<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>LocalMart</title>
   <link rel="shortcut icon" type="images\png" href="images\logo.png"> 
   <!--  css dependencies start  -->

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

</head>
<body style="background-color:  #03211B;">

    
   <?php

if(isset($message)){

      echo '
      <div class="message">
         <span style="color: black;">'.$message.'</span>
         <i style="color: black;" class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
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

    <section class="hero hero--secondary">
      <div class="container">
         <div class="row gy-5 gy-lg-0 align-items-center justify-content-center justify-content-lg-between">
            <div class="col-12 col-lg-7 col-xxl-6">
               <div class="section__content">
                  
                        
                        </section>


<section class="display-orders">

   <?php
      $cart_grand_total = 0;
      $select_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart_items->execute([$user_id]);
      if($select_cart_items->rowCount() > 0){
         while($fetch_cart_items = $select_cart_items->fetch(PDO::FETCH_ASSOC)){
            $cart_total_price = ($fetch_cart_items['finalPrice'] * $fetch_cart_items['quantity']);
            $cart_grand_total += $cart_total_price;
            $ba=$fetch_cart_items['store_name'];
            
            
   ?>
   <p> <?= $fetch_cart_items['name']; ?> <span>(<?= '£'.$fetch_cart_items['finalPrice'].'/- x '. $fetch_cart_items['quantity']; ?>)</span> </p>
   <?php
    }
   }else{
      echo '<p class="empty">your cart is empty!</p>';
   }
   ?>
   <div class="grand-total">Grand Total : <span>£<?= $cart_grand_total; ?>/-</span></div>
</section>

<section class="checkout-orders">
<?php
     /**
      $select_user = $conn->prepare("SELECT * FROM `users` WHERE user_id = ?");
      $select_user->execute([$user_id]);
      if($select_user->rowCount() > 0){
         while($fetch_user = $select_cart_items->fetch(PDO::FETCH_ASSOC)){
            
   
    }
   }else{
      echo '<p class="empty">user not found!</p>';
   }
   **/
   ?>
   <form action="" method="POST">

      <h3>place your order</h3>

      <div class="flex">
         <div class="inputBox">
            <span>Your Name:</span>
            <input type="text" name="name" placeholder="enter your name" class="box" required value="<?=$fetch_profile['fname']; ?>" readonly>
         </div>
         <div class="inputBox">
            <span>Your Phone Number:</span>
            <input type="number" name="number" placeholder="Enter your Phone Number" class="box" required>
         </div>
         <div class="inputBox">
            <span>Your Email:</span>
            <input type="email" name="email" placeholder="enter your email" class="box" required  value="<?=$fetch_profile['email']; ?>" readonly>
         </div>
         <div class="inputBox">
            <span>Payment method:</span>
            <select name="method" class="box" required>
               <option value="cash on delivery">cash on collection</option>
               <option value="credit card">credit card</option>
               <option value="paypal">paypal</option>
            </select>
         </div>
         <div class="inputBox">
            <span>address line 01:</span>
            <input type="text" name="house" placeholder="e.g. house number" class="box" required>
         </div>
         <div class="inputBox">
            <span>address line 02:</span>
            <input type="text" name="street" placeholder="e.g. street name" class="box" required>
         </div>
         <div class="inputBox">
            <span>City:</span>
            <input type="text" name="city" placeholder="e.g. London" class="box" required>
         </div>
         <div class="inputBox">
            <span>Postcode:</span>
            <input type="text" name="state" placeholder="e.g. SW15 5PH" class="box" required>
         </div>
         <div class="inputBox">
            <span>Country:</span>
            <input type="text" name="country" placeholder="e.g. United Kingdom" class="box" required>
         </div>
         <div class="inputBox">
            <span>Type of collection:</span>
            <select name="method" class="box" required>
               <option value="Collection">Collection</option>
               
               
            </select>
         </div>
         
      </div>


      <?php
$cart_querye = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $cart_querye->execute([$user_id]);
   if($cart_querye->rowCount() > 0){
      while($cart_iteme = $cart_querye->fetch(PDO::FETCH_ASSOC)){
         

         ?>

      <input type="text" name="store_id" value="<?= $cart_iteme['store_name']; ?>"> 

    <?php }
    } ?>

      <input type="submit" name="order" class="btn" value="place order">

   </form>

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
                            <a href="about-us.html">
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
