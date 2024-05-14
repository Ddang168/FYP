<?php
error_reporting(0);
@include 'db.php';
session_start();


$admin_id = $_SESSION['admin_id'];
 $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
$store_owner = $conn->prepare("SELECT * FROM `store_owners` WHERE email = ?");
$store_owner->execute([$fetch_profile['email']]);
$fetch_owner = $store_owner->fetch(PDO::FETCH_ASSOC);
if(isset($fetch_owner['store_owner_id'])){
   $store_owner_id = $fetch_owner['store_owner_id'];
}
else{
   $store_owner_id = -1;
}
 


 function applyDiscount($price, $discount) {
    $discountedPrice = $price - ($price * ($discount / 100));
    return $discountedPrice;
}
// Include your database connection here
// $conn = new PDO("mysql:host=localhost;dbname=your_database", "username", "password");

if (isset($_POST['add_product'])) {
   // Retrieve form data
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $discount = $_POST['discount'];
    $store_name = $_POST['store_name'];
    $location = $_POST['location'];
    $details = $_POST['details'];
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;
    $image_size = $_FILES['image']['size'];
    
    // Calculate discounted price
    $finalPrice = $price;
    if (!empty($discount)) {
        $finalPrice = applyDiscount($price, $discount);
    }

   

   

   // Check if product with same name exists
   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);


   if ($select_products->rowCount() > 0) {
      $message[] = 'Product name already exists!';
   } else {
      // Insert product into database with store_owner_id
      $insert_product = $conn->prepare("INSERT INTO `products` (name, category, price, discount, finalPrice, image, store_name, location, details, store_owner_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insert_product->execute([$name, $category, $price, $discount, $finalPrice, $image, $store_name, $location, $details, $store_owner_id]);

      if ($insert_product) {
         if ($image_size > 20000000000) {
            $message[] = 'Image size is too large!';
         } else {
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'New product added!';
         }
      }
   }
}




if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $select_delete_image = $conn->prepare("SELECT image FROM `products` WHERE id = ?");
   $select_delete_image->execute([$delete_id]);
   $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   $delete_products = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_products->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   header('location:admin_products.php');


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
   

    <link rel="stylesheet" type="text/css" href="style.css">
      




    <link rel="stylesheet" type="text/css" href="style.css">
    
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <link rel="stylesheet" type="text/css" href="assets/css/style.css">

    <link rel="stylesheet" type="text/css" href="css/admin_style.css">
   <link rel="stylesheet" href="css/admin_style.css">

     <style>
    /* Styling for add new product section */
.add-products {
  background-color: #f9f9f9;
  padding: 20px;
  border-radius: 5px;
  text-align: center;
  max-width: 600px; /* Limiting the width */
  margin: 0 auto; /* Centering horizontally */
  margin-bottom: 20px;
}

.add-products .title {
  font-size: 24px;
  color: #333;
  margin-bottom: 20px;
}

.add-products form {
  display: table;
  margin: 0 auto;
}

.add-products .inputBox {
  display: table-row;
}

.add-products .inputBox label {
  display: table-cell;
  padding: 10px;
}

.add-products .inputBox input,
.add-products .inputBox select,
.add-products .inputBox textarea {
  display: table-cell;
  padding: 10px;
  width: 100%;
  border: 1px solid #ccc;
  border-radius: 5px;
  margin-bottom: 10px;
}

.add-products .inputBox input[type="file"] {
  border: none;
  padding: 5px;
  width: 70%; /* Adjusting the width */
  margin-top: 10px;
}

.add-products .inputBox textarea {
  border: 1px solid #ccc;
  border-radius: 5px;
  padding: 10px;
  width: 100%;
  margin-bottom: 10px;
  background-color: #333; /* Darker background */
  color: #fff; /* Text color */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Darker shadow */
}

.add-products .btn {
  background-color: #007bff;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
}

.add-products .btn:hover {
  background-color: #0056b3;
}

/* Styling for products added section */
.show-products {
  background-color: #f9f9f9;
  padding: 20px;
  border-radius: 5px;
  text-align: center;
}

.show-products .title {
  font-size: 24px;
  color: #333;
  margin-bottom: 20px;
}

.show-products .box-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
}

.show-products .product-container {
  padding: 10px; /* Padding for each product container */
}

.show-products .box {
  background-color: #fff; /* Background color for the box */
  border-radius: 5px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Shadow for the box */
  margin: 10px;
  max-width: 250px; /* Limiting the width of each box */
}

.show-products .price,
.show-products .name,
.show-products .cat,
.show-products .details {
  color: #333;
  margin-bottom: 10px;
}

.show-products .price {
  font-size: 18px;
}

.show-products .name {
  font-size: 20px;
}

.show-products .cat {
  font-size: 16px;
  text-transform: uppercase;
  font-weight: bold;
}

.show-products .details {
  font-size: 16px;
}

.show-products img {
  max-width: 100%;
  height: auto;
  margin-bottom: 10px;
  border-radius: 5px; /* Rounded corners for the image */
}

.show-products .flex-btn {
  display: flex;
  justify-content: space-between;
}

.show-products .option-btn,
.show-products .delete-btn {
  padding: 5px 10px;
  border-radius: 5px;
  text-decoration: none;
  cursor: pointer;
}

.show-products .option-btn {
  background-color: #007bff;
  color: #fff;
}

.show-products .delete-btn {
  background-color: #dc3545;
  color: #fff;
}

.show-products .option-btn:hover,
.show-products .delete-btn:hover {
  opacity: 0.8;
}
.preserve-format {
  white-space: pre-wrap;
}



    </style>

</head>
<body style="background-color:  #03211B;">

  
   <?php

if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span style="color: black;">'.$message.'</span>
         <i style="color: black;" class="fas fa-times" onclick="this.parentElement.remove();"></i>
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
                                        <a class="nav-link" href="admin_products.php">List Products</a>
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
         <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
         
       
         <button class="small-button"><a href="admin_update_profile.php">Profile</a></button>
            <p>   <br>
        <button class="small-button"><a href="logout-store.php">Logout</a></button>

   
         
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
                                <a class="menu_link dropdown-toggle" href="admin_products.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">Products </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" class="active" href=""></a></li>
                                </ul>
                            </li>
                            <li class="menu_item dropdown">
                                <a class="menu_link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">About Us</a>
                                <ul class="dropdown-menu">
                                </ul>
                            </li>
                             <li class="menu_item">
                                <a class="menu_link" href="contact.html">Contact us</a>
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


  <section class="hero hero--secondary">
      <div class="container">
         <div class="row gy-5 gy-lg-0 align-items-center justify-content-center justify-content-lg-between">
            <div class="col-12 col-lg-7 col-xxl-6">
               <div class="section__content">
                  
                        


                        </section>
               </div>

    <section class="add-products">


  

<!-- HTML form for adding a new product -->
<h1 class="title">Add New Product</h1>
<form action="" method="POST" enctype="multipart/form-data">
    <div class="flex">
        <div class="inputBox">
            <input type="text" name="name" class="box" required placeholder="Enter product name">
            <select name="category" class="box" required>
                <option value="" selected disabled>Select category</option>
                <option value="Fish">Fish & Meat</option>
                <option value="Vegetables">Vegetables</option>
                <option value="Fruits">Fruits</option>
                <option value="Drinks">Drinks</option>
                <option value="Bread">Bread</option>
                <option value="Snacks">Snacks</option>
            </select>
            <input type="number" name="discount" value="" class="box" required placeholder="Enter discount percentage">
        </div>
        <div class="inputBox">
            <input type="number" min="0" step="0.01" name="price" class="box" required placeholder="Enter product price">
            <input type="file" name="image" required class="box" accept="image/jpg, image/jpeg, image/png">
            <input type="text" name="store_name" class="box" required placeholder="Enter your Store name">
        </div>
        
            <select name="location" class="box" required>
                <option value="" selected disabled>Select location</option>
                <option value="Roehampton">Roehampton</option>
                <option value="Croydon">Croydon</option>
            </select>
    </div>
    <textarea name="details" class="box" required placeholder="Enter product description" cols="30" rows="10"></textarea>
    <input type="submit" class="btn" value="Add product" name="add_product">
</form>
</section>

<?php
// Include your database connection here
// $conn = new PDO("mysql:host=localhost;dbname=your_database", "username", "password");

// Fetch products from the database
$show_products = $conn->prepare("SELECT * FROM `products` WHERE store_owner_id = ?");
$show_products->execute([$store_owner_id]);
?>

<section class="show-products">
    <h1 class="title">List of Products Added</h1>
    <div class="box-container">
        <?php
        // Check if products are available
        if ($show_products->rowCount() > 0) {
            // Loop through each product
            while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <div class="box">
                    <div class="name" style="font-size:100%; color: red;">Original Price : £<?= $fetch_products['price']; ?></div>
                    <p><br>

                    <div class="name" style="font-size:100%;">Discount Price : £<?= $fetch_products['finalPrice']; ?></div>
                    <p><br>

                    <p><br>

                    <div class="name"><?= $fetch_products['name']; ?></div>
                    <p><br>
                    <div class="name"><?= $fetch_products['store_name']; ?></div>
                    <p><br>
      
                    <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
                    <p><br>
                    <div class="cat"><?= $fetch_products['category']; ?></div>
                    <div class="details" style="white-space: pre-wrap;"><?= $fetch_products['details']; ?></div>

                    <div class="flex-btn">
                        <!-- Add links for update and delete actions if needed -->
                        <!-- For example: -->
                        <a href="admin_update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a> 
                        <a href="admin_products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
                    </div>
                </div>
                <?php
            }
        } else {
            // If no products are available, display a message
            echo '<p class="empty">No products added yet!</p>';
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
                            <a href="index.html">
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="about-us.html">
                                About
                            </a>
                        </li>
                        <li>
                            <a href="contact.html">
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