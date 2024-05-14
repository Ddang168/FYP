<?php

@include 'db.php';

session_start();

  // Check if the user is logged in
    if(isset($_SESSION['admin_id'])) {
        // Get the logged-in user's ID
        $admin_id = $_SESSION['admin_id'];

         $query = "SELECT store_owner_id FROM users WHERE id = ?";

        $stmt = $conn->prepare($query);
        $stmt->execute([$admin_id]);

        // Fetch the store owner ID
        $store_owner_id = $stmt->fetchColumn();

}

if(!isset($admin_id)){
   header('location:sign-in-store.php');
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
    .dashboard {
    background-color: #f4f4f4;
    padding: 20px;
}

.title {
    text-align: center;
}

.box-container {
    display: flex;
    justify-content: space-around;
    margin-top: 30px;
}

.box {
    background-color: #fff;
    border-radius: 5px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 200px;
}

.box h3 {
    font-size: 24px;
    margin: 0;
}

.box p {
    color: #666;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #4CAF50;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 10px;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #45a049;
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
                        <a class="navbar-brand" href="admin_page.php"><img src="images/logo.png" width = "200px" class="logo" alt="logo"></a>
                        <a class="navbar-toggler" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                            <i class="bi bi-list"></i>
                        </a>

                        <div class="collapse navbar-collapse ms-auto " id="navbar-content">
                            <div class="main-menu">
                                <ul class="navbar-nav mb-lg-0 mx-auto">
                                     <li class="nav-item">
                                        <a class="nav-link" href="admin_page.php">Home</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="admin_products.php">List Product</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="admin_orders.php">Order</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="about-us.html">About us</a>
                                    </li>
                                    
                                    <li class="nav-item">
                                        <a class="nav-link" href="contact.php">Contact us</a>
                                    </li>
                                    

         
        
         
      </div>

      <div class="profile" class="nav-item">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         
         <p style="color: white"><?= $fetch_profile['name']; ?></p>
         <a style="color: white" href="admin_update_profile.php"  class="btn">Profile</a>
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
                            <li class="menu_item">
                                <a class="menu_link" href="admin_products.php">List Products</a>
                            </li>
                            <li class="menu_item dropdown">
                                <a class="menu_link dropdown-toggle" href="about-us.html" role="button" data-bs-toggle="dropdown" aria-expanded="false">About Us</a>
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

               <section id="search-banner">




                            <!--text -->
                            <div class="search-banner-text">
                                <h1 style="color: white;">Order Your daily Groceries</h1>
                                <p><br><br>
                                <strong style="color: white;">#Free Delivery</strong>

                                <!--search-box -->
                                <form action="" class="search-box">
                                    <!--icon -->
                                    <i class="fas fa-search"></i>
                                    <!--input -->
                                    <input type="text" class="search-input" placeholder="Search your daily groceries" name="search" required>
                                    <!--btn -->
                                    <input type="submit" class="search-btn" value="Search">
                                </form>
                            </div>


                        </section>
                        <!--search-banner -->

<section class="dashboard">

   <h1 class="title">Dashboard</h1>

   <div class="box-container">


      <div class="box">
      <?php
         $total_completed = 0;
         $select_completed = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
         $select_completed->execute(['completed']);
         while($fetch_completed = $select_completed->fetch(PDO::FETCH_ASSOC)){
            $total_completed += $fetch_completed['total_price'];
         };
      ?>
      <h3><?= $total_completed; ?></h3>
      <p>Completed orders in our server</p>
      <a href="admin_orders.php" class="btn">see orders</a>
      </div>

      <div class="box">
      <?php
         $select_orders = $conn->prepare("SELECT * FROM `orders`");
         $select_orders->execute();
         $number_of_orders = $select_orders->rowCount();
      ?>
      <h3><?= $number_of_orders; ?></h3>
      <p>Orders placed in our server</p>
      <a href="admin_orders.php" class="btn">see orders</a>
      </div>
     <div class="box">
     <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
      <h3><?= $fetch_profile['id']; ?>th</h3>
      <p>You are the <?= $fetch_profile['id']; ?>th Store Owner on our server.</p>
      <a href="admin_products.php" class="btn">Click to See your Products</a>
      </div>
      
    

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
                            <a href="admin_page.php">
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

      
    <script src="assets/js/jquery-3.6.3.min.js"></script>
    
    <script src="assets/js/plugins.js"></script>
    <!-- main js -->
    <script src="assets/js/main.js"></script>
    

</body>
</html>
