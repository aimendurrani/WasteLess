<?php
session_start();
include('db.php');


if (!isset($_SESSION['cnic'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['add_to_cart'])) {
    //get values
    $product_name = $_POST['foodname'];
    $product_quantity = $_POST['quantity'];
    $exp_date = $_POST['expiration_date'];
    $product_image = $_POST['product_image'];

    // retrieves cnic 
    $cnic = $_SESSION['cnic'];

    // QUERY TO CHECK IF THE FOOD IS ALREADY IN CART
    $select_cart = mysqli_query($con, "SELECT * FROM requestfood WHERE cnic = '$cnic' AND foodname = '$product_name'");
    
    if (mysqli_num_rows($select_cart) > 0) {
        $message[] = 'Product already added to cart';
    } else {
       
        mysqli_autocommit($con, false);

        // INSERTS FOOD INTO THE REQUEST FOOD TABLE
        $insert_product = mysqli_query($con, "INSERT INTO requestfood (cnic, foodname, quantity, expiration_date, image) 
        VALUES ('$cnic', '$product_name', '$product_quantity', '$exp_date', '$product_image')");
        
        if ($insert_product) {
            // DELETES FOOD FROM THE DONATION TABLE 
            $delete_product = mysqli_query($con, "DELETE FROM donations WHERE foodname = '$product_name'");

            if ($delete_product) {
                
                mysqli_commit($con);
                $message[] = 'Product added to cart successfully';
            } else {
                
                mysqli_rollback($con);
                $message[] = 'Failed to mark product as out of stock';
            }
        } else {
            $message[] = 'Failed to add product to cart';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <link rel="stylesheet" href="checkout.css">
</head>
<body>

<?php
if(isset($message)){
   foreach($message as $msg){
      echo '<div class="message"><span>'.$msg.'</span> <i class="fas fa-times" onclick="this.parentElement.style.display = none;"></i> </div>';
   }
}
?>

<?php include ('header.php'); ?>
<div class="container">
    <section class="products">
        <h1 class="heading">All Donations</h1>
        <div class="box-container">
            <?php
                //SELECTS ALL PRODUCTS FROM DONATIONS TABLE
                $select_products = mysqli_query($con, "SELECT * FROM donations");

                if(mysqli_num_rows($select_products) > 0){ 
                    while($fetch_product = mysqli_fetch_assoc($select_products)){ //disp prod
            ?>
            <form action="" method="post">
                <div class="box">
                    <img src="uploaded_img/<?php echo $fetch_product['image']; ?>" alt="">
                    <h3><?php echo $fetch_product['foodname']; ?></h3>
                    <h3><?php echo $fetch_product['quantity']; ?></h3>
                    <h3><?php echo $fetch_product['expiration_date']; ?></h3>

                    <!-- sends prod details w the form  -->
                    <input type="hidden" name="foodname" value="<?php echo $fetch_product['foodname']; ?>">
                    <input type="hidden" name="quantity" value="<?php echo $fetch_product['quantity']; ?>">
                    <input type="hidden" name="expiration_date" value="<?php echo $fetch_product['expiration_date']; ?>">
                    <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">

                    <input type="submit" class="btn" value="Request Food" name="add_to_cart">
                </div>
            </form>
            <?php 
                    }
                }
            ?>
        </div>
    </section>
</div>

</body>
</html>