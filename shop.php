<?php

@include 'db.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:sign-in.php');
};

if(isset($_POST['add_to_wishlist'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);

   $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
   $check_wishlist_numbers->execute([$p_name, $user_id]);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_wishlist_numbers->rowCount() > 0){
      $message[] = 'already added to wishlist!';
   }elseif($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
   }else{
      $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
      $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
      $message[] = 'added to wishlist!';
   }

}

if(isset($_POST['add_to_cart'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $final = $_POST['p_final'];
   $final = filter_var($final, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);
   $p_store = $_POST['store_id'];
   $p_store = filter_var($p_store, FILTER_SANITIZE_STRING);
   $p_qty = $_POST['p_qty'];
   $p_qty = filter_var($p_qty, FILTER_SANITIZE_STRING);

   $_SESSION['store']=$p_store;
   
   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
   }else{

      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$p_name, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
         $delete_wishlist->execute([$p_name, $user_id]);
      }

      $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, finalPrice, quantity, image, store_name) VALUES(?,?,?,?,?,?,?,?)");
      $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $final , $p_qty, $p_image, $p_store]);
      $message[] = 'added to cart!';
   }

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

    <link rel="stylesheet" type="text/css" href="style.css">
    

     <style>
  /* Styling for products section */
.products {
    background-color: #f9f9f9;
    padding: 20px;
}

/* Styling for title */
.title {
    color: white;
    text-align: center;
}

/* Styling for box container */
.box-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    margin-top: 20px;
}

/* Styling for product box */
/* Styling for product box */
.box {
    width: 300px;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 20px;
    box-sizing: border-box;
    margin-bottom: 20px;
    text-align: center;
    position: relative;
}

.box img {
    max-width: 100%;
    height: auto;
    margin-bottom: 10px;
}

/* Styling for buttons */
.option-btn, .btn {
    padding: 8px 15px;
    background-color: #4caf50;
    color: #fff;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    display: block; /* Changed */
    margin: 10px auto; /* Changed */
}

.option-btn:hover, .btn:hover {
    background-color: #45a049;
}


.price {
    font-weight: bold;
    color: #4caf50;
}

.name {
    margin-bottom: 10px;
}

.qty {
    width: 60px;
    padding: 5px;
    margin-bottom: 10px;
}

/* Styling for buttons */
.option-btn, .btn {
    padding: 8px 15px;
    background-color: #4caf50;
    color: #fff;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    position: center; 
    transition: background-color 0.3s ease;
}

.option-btn:hover, .btn:hover {
    background-color: #45a049;
}

/* Styling for empty message */
.empty {
    text-align: center;
    font-weight: bold;
    color: #777;
}
/* Media query for mobile devices */
@media screen and (max-width: 767px) {
    .box-container {
        flex-direction: row; /* Align products horizontally on smaller screens */
        flex-wrap: nowrap; /* Prevent wrapping on smaller screens */
        overflow-x: auto; /* Add horizontal scroll if necessary */
    }
    .box {
        margin-right: 10px; /* Add some spacing between products */
    }
    .box:last-child {
        margin-right: 0; /* Remove right margin from the last product */
    }
}

    </style>
    <style>
    .filter-button {
    padding: 10px 20px; /* Adjust padding as needed */
    font-size: 30px; /* Adjust font size as needed */
    background-color: green;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.filter-button:hover {
    background-color: darkgreen; /* Change color on hover */
}

    </style>

</head>
<body style="background-color: #03211B;">

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
                        <a class="navbar-brand" href="./"><img src="images/logo.png" width = "200px" class="logo" alt="logo"></a>
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
                                    <li><a class="dropdown-item" class="active" href=""></a></li>
                                </ul>
                            </li>
                            <li class="menu_item dropdown">
                                        <a class="menu_link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Shop by location</a>
                                        <ul class="dropdown-menu">
                                            <a href="roehamptonstore.php"><li class="dropdown-item" style="color: white;">
                                                Local Stores in Roehampton
                                            </li></a>

                                            <a href="croydonstore.php"><li class="dropdown-item" style="color: white;">
                                                Local Stores in Croydon
                                            </li></a>
                                        
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

                  <!--category -->
                        <section id="category">
                            
                            <!-- heading -->
                            <div class="category-heading">
                                <h2 style="color: white;">Category</h2>
                                <p><br><br>
                                <span>All</span>
                            </div>
                             <!-- Filter Form -->
                            <form action="categorylocate.php" method="GET" class="location-filter">
                                <label for="location" style="color:white;">Filter by Location:</label>
                                <select name="location" id="location" style="color: white;">
                                    <option value="Roehampton" style="text-decoration-color:white;">Roehampton</option>   
                                    <option value="croydon" style="color: white;">Croydon</option>
                                </select>
                                <button type="submit" class="filter-button">Filter</button>
                            </form>

                            <!--box-container -->
                            <div class="category-container">

                                <!--box -->
                                <a href="category.php?category=meat"class="category-box"> 
                                <img src="images/fishy.png" alt="Fish">
                                <span>Fish & Meat</span>
                                </a>

                                <!--box -->
                                 <a href="category.php?category=vegetables"class="category-box"> 
                                <img src="images/vegetable.png" alt="Fish">
                                <span>Vegetables</span>
                                </a>

                                <!--box -->
                             <a href="category.php?category=fruits"class="category-box"> 
                                <img src="images/fruits.png" alt="Fish">
                                <span>Fruits</span>
                                </a>

                                <!--box -->
                                 <a href="category.php?category=drinks"class="category-box"> 
                                <img src="images/drinks.png" alt="Fish">
                                <span>Drinks</span>
                                </a>

                                <!--box -->
                                 <a href="category.php?category=bread"class="category-box"> 
                                <img src="images/bread.png" alt="Fish">
                                <span>Bread</span>
                                </a>

                                <!--box -->
                                 <a href="category.php?category=snacks"class="category-box"> 
                                <img src="images/snacks.png" alt="Fish">
                                <span>Snacks</span>
                                </a>

                            </div>
                        </section>

                        <!-- Cateogry-End ---->



<center><section class="products" align="center">

   <h1 class="title" style="color: black;">Latest Products</h1>
   <p><br>

   <div class="box-container">


<?php
// Check if a category filter is provided in the URL
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

// Prepare SQL statement with optional category filter
$select_products = $conn->prepare("SELECT * FROM `products`" . ($category_filter ? " WHERE category = :category" : ""));
if ($category_filter) {
    $select_products->bindParam(':category', $category_filter);
}
$select_products->execute();

if ($select_products->rowCount() > 0) {
    while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) { 
?>
    <form action="" class="box" method="POST">
        <p><?= $fetch_products['store_name']; ?></p>
        <div class="price" style="color: red;">Original Price : £<span><?= $fetch_products['price']; ?></span>/-</div>
        <div class="price">Final Price : £<span><?= $fetch_products['finalPrice']; ?></span>/-</div>
        <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
        <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
        <div class="name"><?= $fetch_products['name']; ?></div>
        <strong><h2><input type="hidden" name="pid" value="<?= $fetch_products['store_name']; ?>"></h2></strong>
        <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
        <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
        <input type="hidden" name="p_final" value="<?= $fetch_products['finalPrice']; ?>">
        <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
        <input type="hidden" name="store_id" value="<?= $fetch_products['store_name']; ?>">
        <input type="number" min="1" value="1" name="p_qty" class="qty">
        <input type="submit" value="Add to wishlist" class="option-btn" name="add_to_wishlist">
        <input type="submit" value="Add to cart" class="btn" name="add_to_cart">
    </form>

<?php
    }
} else {
    echo '<p class="empty" style="color: white;">No products available in this category!</p>';
}
?>

    
   </div>

</section></center>



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

   <script src="assets/js/jquery-3.6.3.min.js"></script>
    
    <script src="assets/js/plugins.js"></script>
    <!-- main js -->
    <script src="assets/js/main.js"></script>
    

</body>
</html>