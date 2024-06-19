<?php
include('db.php'); 
include('navbar2.php'); 


// FETCHES ALL DONATIONS
$select = mysqli_query($con, "SELECT * FROM donations");


if (!$select) {
    die('Query failed: ' . mysqli_error($con));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style2.css">
    <title>Display Food</title>
</head>
<body>
<div class="product-display">
      <table class="product-display-table">
         <thead>
            <tr>
               <th>Food Image</th>
               <th>Food Type</th>
               <th>Food Name</th>
               <th>Quantity</th>
               <th>Expiration Date</th>
            </tr>
         </thead>
         <tbody>
            <?php while($row = mysqli_fetch_assoc($select)){ ?>
               <tr>
                  <td><img src="uploaded_img/<?php echo $row['image']; ?>" height="100" alt=""></td>
                  <td><?php echo $row['foodtype']; ?></td>
                  <td><?php echo $row['foodname']; ?></td>
                  <td><?php echo $row['quantity']; ?></td>
                  <td><?php echo $row['expiration_date']; ?></td>
               </tr>
            <?php } ?>
         </tbody>
      </table>
      <a href="admin-page.php" class="btn">Go Back</a>
   </div> 
</body>
</html>
