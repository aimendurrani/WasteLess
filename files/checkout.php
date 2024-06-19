<?php
session_start();
include ('db.php');

if (!isset($_SESSION['cnic'])) {
    header("Location: login.php");
    exit;
}
//retirves cnic 
$cnic = $_SESSION['cnic'];

if (isset($_POST['order_btn'])) {

   $name = $_POST['name'];
   $number = $_POST['number'];
   $email = $_POST['email'];
   $street = $_POST['street'];
   $city = $_POST['city'];
   $state = $_POST['state'];
   $country = $_POST['country'];
   $pin_code = $_POST['pin_code'];

   // GETS ITEMS IN THE CART FOR LOGGED IN USER
   $cart_query = mysqli_query($con, "SELECT * FROM requestfood WHERE cnic = '$cnic'");
   $quantity_total = 0;
   $product_name = [];
   //calculates total quantity and product names
   if (mysqli_num_rows($cart_query) > 0) {
      while ($product_item = mysqli_fetch_assoc($cart_query)) {
         $product_name[] = $product_item['foodname'] . ' (' . $product_item['quantity'] . ') ';
         $quantity_total += $product_item['quantity'];
      }
   }

   $total_product = implode(', ', $product_name);

   // INSERTS INTO ORDERS TABLE
   $detail_query = mysqli_query($con, "INSERT INTO orders (cnic, name, number, email, street, city, state, country, pin_code, quantity) 
   VALUES ('$cnic', '$name', '$number', '$email', '$street', '$city', '$state', '$country', '$pin_code', '$quantity_total')") or die('query failed');

   if ($detail_query) {
      // DELETES ITEMS FROM REQUEST FOOD TABLE
      $delete_query = mysqli_query($con, "DELETE FROM requestfood WHERE cnic = '$cnic'");
      
      if ($delete_query) {
         echo "
         <div class='order-message-container'>
            <div class='message-container'>
               <h3>Enjoy!</h3>
               <div class='order-detail'>
                  <span>" . $total_product . "</span>
                  <span class='total'> Total Quantity: " . $quantity_total . "</span>
               </div>
               <div class='customer-details'>
                  <p> Your Name: <span>" . $name . "</span> </p>
                  <p> Your Number: <span>" . $number . "</span> </p>
                  <p> Your Email: <span>" . $email . "</span> </p>
               </div>
               <a href='product.php' class='btn'>Continue Shopping</a>
            </div>
         </div>
         ";
      } else {
         echo "Failed to delete items from cart.";
      }
   } else {
      echo "Failed to insert order details.";
   }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="checkout.css">
   <title>Checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'header.php'; ?>

<div class="container">

<section class="checkout-form">

   <h1 class="heading">Complete Your Order</h1>

   <form action="" method="post">

   <div class="display-order">
      <?php

         //GETS ITEMS IN CART FOR LOGGED IN USERS
         $select_cart = mysqli_query($con, "SELECT * FROM requestfood WHERE cnic = '$cnic'");
         $total_quantity = 0;

         //displays cart items
         if (mysqli_num_rows($select_cart) > 0) {
            while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
               $total_quantity += $fetch_cart['quantity'];
      ?>
      <span><?= $fetch_cart['foodname']; ?>(<?= $fetch_cart['quantity']; ?>)</span>
      <?php
            }
      ?>
      <span class="total-quantity">Total Quantity: <?= $total_quantity; ?></span>
      <?php
         } else {
            echo "<div class='display-order'><span>Your cart is empty!</span></div>";
         }
      ?>
   </div>

      <div class="flex">
         <div class="inputBox">
            <span>Your Name</span>
            <input type="text" placeholder="Enter your name" name="name" required>
         </div>
         <div class="inputBox">
            <span>Your Number</span>
            <input type="number" placeholder="Enter your number" name="number" required>
         </div>
         <div class="inputBox">
            <span>Your Email</span>
            <input type="email" placeholder="Enter your email" name="email" required>
         </div>
         <div class="inputBox">
            <span>Address Line 1</span>
            <input type="text" placeholder="Enter Flat number if any" name="street">
         </div>
         <div class="inputBox">
            <span>Address Line 2</span>
            <input type="text" placeholder="Enter your complete address" name="street" required>
         </div>
         <div class="inputBox">
            <span>City</span>
            <input type="text" placeholder="Enter your city" name="city" required>
         </div>
         <div class="inputBox">
            <span>State</span>
            <input type="text" placeholder="Enter State" name="state" required>
         </div>
         <div class="inputBox">
            <span>Country</span>
            <input type="text" placeholder="Enter Country" name="country" required>
         </div>
         <div class="inputBox">
            <span>Pin Code</span>
            <input type="text" placeholder="Enter Pin Code" name="pin_code" required>
         </div>
      </div>
      <input type="submit" value="Request Food Item " name="order_btn" class="btn">
   </form>

</section>

</div>
   
</body>
</html>