<?php
session_start();
include('db.php');
include('navbar2.php');

// redirects to login if user is not logged in
if (!isset($_SESSION['cnic'])) {
    header("Location: login.php");
    exit;
}


if(isset($_POST['add_product'])){
   $food_type = $_POST['foodtype'];
   $food_name = $_POST['foodname'];
   $food_quantity = $_POST['quantity'];
   $exp_date = $_POST['expiration_date'];
   $product_image = $_FILES['product_image']['name'];
   $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
   $product_image_folder = 'uploaded_img/'.$product_image;
   // retrieves cnic from session
   $cnic = $_SESSION['cnic'];

   // validation
   if(empty($food_type) || empty($food_name) || empty($food_quantity) || empty($exp_date) || empty($product_image)){
      $message[] = 'Please fill out all fields';
   } else {
      //INSERT DATA INTO DONATIONS TABLE
      $insert = "INSERT INTO donations(cnic, foodtype, foodname, quantity, expiration_date, image)
                 VALUES('$cnic', '$food_type', '$food_name', '$food_quantity', '$exp_date', '$product_image')";
      
      $upload = mysqli_query($con, $insert);

      if($upload){
         // moves uploaded file to destination folder
         move_uploaded_file($product_image_tmp_name, $product_image_folder);
         $message[] = 'New donation added successfully';
      } else {
         $message[] = 'Could not add the donation';
      }
   }
}

// handles delete request
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    
    //GETS DONATION DETAILS & CHECKS IF THE LOGGED IN USER CAN DELETE IT
    $select = mysqli_query($con, "SELECT * FROM donations WHERE donationid = '$id'");
    $donation = mysqli_fetch_assoc($select);

    if (!$donation) {
        $message[] = 'Donation not found';
    } else {

        //CHECKS IF THE LOGGED IN USER MATCHES THE DONOR OF THE DONATION
        if ($_SESSION['cnic'] === $donation['cnic']) {
            $delete_query = "DELETE FROM donations WHERE donationid = '$id'";
            $delete_result = mysqli_query($con, $delete_query);

            if($delete_result) {
                // redirects to admin page after deletingg
                header('Location: admin-page.php');
                exit; 
            } else {
                $message[] = 'Failed to delete donation';
            }
        } else {
            $message[] = 'You are not authorized to delete this donation';
        }
    }
}

// DISPLAYS ALL DONATIONS FROM THE TABLE
$select = mysqli_query($con, "SELECT * FROM donations");

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="style2.css">
   <title>Admin Page</title>
</head>
<body>

<?php
// Display messages if any
if(isset($message)){
   foreach($message as $msg){
      echo '<span class="message">'.$msg.'</span>';
   }
}
?>

<div class="container">
   <div class="admin-product-form-container">
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
         <h3>Add Food Item</h3>
         <input type="text" placeholder="Enter food type" name="foodtype" class="box" required>
         <input type="text" placeholder="Enter food name" name="foodname" class="box" required>
         <input type="number" placeholder="Enter product quantity" name="quantity" class="box" required>
         <input type="date" placeholder="Expiration date" name="expiration_date" class="box" required>
         <input type="file" accept="image/png, image/jpeg, image/jpg" name="product_image" class="box" required>
         <button id="add-food-button" type="submit" name="add_product" class="btn">Add Food</button>
      </form>
   </div>

   <div class="product-display">
      <table class="product-display-table">
         <thead>
            <tr>
               <th>Food Image</th>
               <th>Food Type</th>
               <th>Food Name</th>
               <th>Quantity</th>
               <th>Expiration Date</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            <?php while($row = mysqli_fetch_assoc($select)){ ?>
               <tr>
                  <td><img src="uploaded_img/<?php echo htmlspecialchars($row['image']); ?>" height="100" alt=""></td>
                  <td><?php echo htmlspecialchars($row['foodtype']); ?></td>
                  <td><?php echo htmlspecialchars($row['foodname']); ?></td>
                  <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                  <td><?php echo htmlspecialchars($row['expiration_date']); ?></td>
                  <td>
                     <?php if ($_SESSION['cnic'] === $row['cnic']): ?>
                        <a href="admin-update.php?edit=<?php echo $row['donationid']; ?>" class="btn">Edit</a>
                        <a href="admin-page.php?delete=<?php echo $row['donationid']; ?>" class="btn">Delete</a>
                     <?php else: ?>
                        <span class="btn disabled">Edit</span>
                        <span class="btn disabled">Delete</span>
                     <?php endif; ?>
                  </td>
               </tr>
            <?php } ?>
         </tbody>
      </table>
   </div> 
</div>

</body>
</html>
