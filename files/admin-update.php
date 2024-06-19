<?php
session_start();
include('db.php');

if (!isset($_SESSION['cnic'])) {
    header("Location: login.php");
    exit;
}

//for editing
if(isset($_GET['edit'])) {
    $id = $_GET['edit']; // Assuming 'edit' is the parameter for the donation ID

    // FETCHES DONATION DETAILS
    $select = mysqli_query($con, "SELECT * FROM donations WHERE donationid = '$id'");
    $row = mysqli_fetch_assoc($select);

    if(!$row) {
        die("Donation not found");
    }

    //CHECKS IF THE LOGGED IN USER MATCHES THE DONOR OF THE DONATION
    if ($_SESSION['cnic'] !== $row['cnic']) {
        die("You are not authorized to edit this donation");
    }
} else {
    die("Invalid request");
}

// updateS donation details
if(isset($_POST['update_product'])){
    $food_type = isset($_POST['foodtype']) ? $_POST['foodtype'] : '';
    $food_name = isset($_POST['foodname']) ? $_POST['foodname'] : '';
    $food_quantity = isset($_POST['quantity']) ? $_POST['quantity'] : '';
    $exp_date = isset($_POST['expiration_date']) ? $_POST['expiration_date'] : '';
    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
    $product_image_folder = 'uploaded_img/'.$product_image;

    // validation
    if(empty($food_type) || empty($food_name) || empty($food_quantity) || empty($exp_date) || empty($product_image)){
        $message[] = 'Please fill out all fields';
    } else {
        // UPDATES VALUES
        $update_data = "UPDATE donations 
                        SET foodtype='$food_type',
                            foodname='$food_name', 
                            quantity='$food_quantity', 
                            expiration_date='$exp_date', 
                            image='$product_image'  
                        WHERE donationid = '$id'";

        $upload = mysqli_query($con, $update_data);

        if($upload){
            // moves uploaded file to destination folder
            move_uploaded_file($product_image_tmp_name, $product_image_folder);
            header('Location: admin-page.php');
            exit;
        } else {
            $message[] = 'Failed to update product';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="style2.css">
   <title>Update Product</title>
</head>
<body>

<?php
if(isset($message)){
   foreach($message as $msg){
      echo '<span class="message">'.$msg.'</span>';
   }
}
?>

<div class="container">
   <div class="admin-product-form-container centered">

      <form action="admin-update.php?edit=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
         <h3 class="title">Update Product</h3>
         <input type="text" class="box" name="foodtype" value="<?php echo $row['foodtype']; ?>" placeholder="Enter the food type">
         <input type="text" class="box" name="foodname" value="<?php echo $row['foodname']; ?>" placeholder="Enter the food name">
         <input type="number" min="0" class="box" name="quantity" value="<?php echo $row['quantity']; ?>" placeholder="Enter the product quantity">
         <input type="date" class="box" name="expiration_date" value="<?php echo $row['expiration_date']; ?>" placeholder="Expiration date">
         <input type="file" class="box" name="product_image" accept="image/png, image/jpeg, image/jpg">
         <input type="submit" value="Update Product" name="update_product" class="btn">
         <a href="admin-page.php" class="btn">Go Back</a>
      </form>

   </div>
</div>

</body>
</html>
