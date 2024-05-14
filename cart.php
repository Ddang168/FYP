<?php
error_reporting(0);
@include 'db.php';

session_start();

$user_id = $_SESSION['user_id'];
$store_id = $_SESSION['store'];
if(!isset($user_id)){
   header('location:sign-in.php');
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$delete_id]);
   header('location:cart.php');
}

if(isset($_GET['delete_all'])){
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   header('location:cart.php');
}

if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $p_qty = $_POST['p_qty'];
   $p_qty = filter_var($p_qty, FILTER_SANITIZE_STRING);
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$p_qty, $cart_id]);
   $message[] = 'cart quantity updated';
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
    <style>
       
       /* Responsive CSS */
/* Centering the shopping cart */
.shopping-cart {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.box-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    margin-top: 20px;
}

.empty-message {
    background-color: #f9f9f9;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    text-align: center;
    margin-top: 20px;
}

.total-actions {
    background-color: #f9f9f9;
    padding: 90px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-top: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* Styling for action buttons */
.action-btn {
    padding: 8px 15px;
    margin-top: 10px;
    background-color: #4caf50;
    color: #fff;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.action-btn:hover {
    background-color: #45a049;
}

/* Animation for action buttons */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.action-btn {
    animation: fadeInUp 0.5s ease forwards;
    animation-delay: 0.2s;
}


.box {
    width: 300px;
    margin-bottom: 20px;
    background-color: #f9f9f9;
    padding: 20px;
    box-sizing: border-box;
}

.box img {
    max-width: 100%;
    height: auto;
}

.flex-btn {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 10px;
}

.qty {
    width: 60px;
    padding: 5px;
}

.option-btn {
    padding: 8px 15px;
    background-color: #4caf50;
    color: #fff;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.option-btn:hover {
    background-color: #45a049;
}

.sub-total {
    margin-top: 10px;
}

.cart-total {
    margin-top: 20px;
}

.cart-total p {
    font-weight: bold;
}

.cart-total span {
    color: #4caf50;
}

.empty {
    text-align: center;
}

.disabled {
    pointer-events: none;
    opacity: 0.5;
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
                                            <a href="shop.php"><li class="dropdown-item" style="color: white;">
                                                Local Stores in Roehampton
                                            </li></a>

                                            <a href="shop1.php"><li class="dropdown-item" style="color: white;">
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


    <p><br><br>

   


<section class="shopping-cart">

   <h1 class="title" style="color: white;">Products Added</h1>

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart->execute([$user_id]);
      if($select_cart->rowCount() > 0){
         while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" method="POST" class="box">
      <a href="cart.php?delete=<?= $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from cart?');"></a>

      <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="" width="200">
      <p><br>
      <div class="name"><?= $fetch_cart['name']; ?></div>
      <p><br>
      <div class="price">Price: £<?= $fetch_cart['price']; ?></div>
      <p><br>
      <div class="price">Discounted Price: £<?= floatval($fetch_cart['finalPrice']); ?></div>
      <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
      <div class="flex-btn">
         <input type="number" min="1" value="<?= $fetch_cart['quantity']; ?>" class="qty" name="p_qty">
         <input type="submit" value="update" name="update_qty" class="option-btn">
      </div>
      <div class="sub-total"> Sub total : <span>£<?= $sub_total = ($fetch_cart['finalPrice'] * $fetch_cart['quantity']); ?></span> </div>
   </form>
   <?php
      $grand_total += $sub_total;
      }
   }else{
      echo '<p class="empty-message" style="color: red;">Your cart is empty</p>';
   }
   ?>
   </div>

   <div class="total-actions">
      <p>Grand total : <span style="color: red;">£<?= $grand_total; ?></span></p>
      <a href="shop.php" class="action-btn">Continue shopping</a>
      <a href="cart.php?delete_all" class="action-btn <?= ($select_cart->rowCount() > 0)?'':'disabled'; ?>">Delete All</a>
      <a href="checkout.php" class="action-btn <?= ($select_cart->rowCount() > 0)?'':'disabled'; ?>">Proceed to checkout</a>
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
                            <a href="shop.php">
                                Shop
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