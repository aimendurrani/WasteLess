<?php
session_start();
include('db.php'); 
include('header1.php');

// handles delete request
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    

    //GETS THE FOOD ITEM FROM 'REQUESTFOOD' TO ADD BACK TO DONATIONS
    $fetch_food_query = "SELECT * FROM requestfood WHERE requestid = '$id'";
    $fetch_food_result = mysqli_query($con, $fetch_food_query);

    if($fetch_food_result && mysqli_num_rows($fetch_food_result) > 0) {
        $food_item = mysqli_fetch_assoc($fetch_food_result);

        // INSERTS FOOD BACK TO THE DONATIONS TABLE
        $insert_query = "INSERT INTO donations (cnic, foodtype, foodname, quantity, expiration_date, image)
                         VALUES ('{$food_item['cnic']}', 'Food', '{$food_item['foodname']}', '{$food_item['quantity']}', '{$food_item['expiration_date']}', '{$food_item['image']}')";
        $insert_result = mysqli_query($con, $insert_query);

        if($insert_result) {
            // DELETES THE REQUESTED ITEM FROM THE TABLE
            $delete_query = "DELETE FROM requestfood WHERE requestid = '$id'";
            $delete_result = mysqli_query($con, $delete_query);

            if($delete_result) {
                $message[] = 'Food item deleted successfully and added back to donations';
                // redirects/ refresh
                header('Location: cart.php');
                exit;
            } else {
                $message[] = 'Failed to delete food item from cart';
            }
        } else {
            $message[] = 'Failed to add food item back to donations';
        }
    } else {
        $message[] = 'Food item not found in cart';
    }
}


//GETS ALL ITEMS IN THE CART
$select = mysqli_query($con, "SELECT * FROM requestfood");


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
    <?php
    if(isset($message)){
        foreach($message as $msg){
            echo '<span class="message">'.$msg.'</span>';
        }
    }
    ?>
    <table class="product-display-table">
        <thead>
        <tr>
            <th>Food Image</th>
            <th>Food Name</th>
            <th>Quantity</th>
            <th>Expiration Date</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php while($row = mysqli_fetch_assoc($select)){ ?>
            <tr>
                <td><img src="uploaded_img/<?php echo $row['image']; ?>" height="100" alt=""></td>
                <td><?php echo $row['foodname']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td><?php echo $row['expiration_date']; ?></td>
                <td>
                    <a href="cart.php?delete=<?php echo $row['requestid']; ?>" class="btn">Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <a href="checkout.php" class="btn">CheckOut</a>
</div>
</body>
</html>
