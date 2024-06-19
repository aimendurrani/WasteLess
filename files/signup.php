<?php
session_start();
include('db.php');
include('navbar.php');

if($_SERVER['REQUEST_METHOD'] == "POST"){ //sends vals to sql database
    $full_name = $_POST['fullname'];
    $cc = $_POST['cnic'];
    $mail = $_POST['email'];
    $phone_num = $_POST['phone'];
    $pw = $_POST['pass'];

    if(!empty($mail) && !empty($pw) && !is_numeric($mail)){
        //CHECK IF EMAIL OR CNIC ALREADY EXISTS
        $check_query = "SELECT * FROM users WHERE email = '$mail' OR cnic = '$cc' LIMIT 1";
        $result = mysqli_query($con, $check_query);
        $user = mysqli_fetch_assoc($result);

        if($user){
            if($user['email'] === $mail){ //alert if email or cnic exists already
                echo "<script type='text/javascript'> alert('Email already exists') </script>";
            } elseif($user['cnic'] === $cc){
                echo "<script type='text/javascript'> alert('CNIC already exists') </script>";
            }
        } else {
            // INSERT NEW USER
            $query = "INSERT INTO users(fullname, cnic, email, phone, pass)
                      VALUES('$full_name', '$cc', '$mail', '$phone_num', '$pw')";

            mysqli_query($con, $query);

            // sets cnic in session after registration
            $_SESSION['cnic'] = $cc;
            
            echo "<script type='text/javascript'> alert('Successfully Registered') </script>";
        }
    } else {
        echo "<script type='text/javascript'> alert('Please enter valid information') </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Login and Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="signup">
        <h1>Sign Up</h1>
        <form method="POST">
            <label>Full Name</label>
            <input type="text" name="fullname" required>
            <label>Cnic</label>
            <input type="text" name="cnic" pattern="\d{5}-\d{7}-\d{1}" maxlength="15" required>
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Phone Number</label>
            <input type="tel" name="phone" required>
            <label>Password</label>
            <input type="password" name="pass" required>
            <input type="submit" value="Signup">
        </form>
        <p>By clicking the Sign Up button, you agree to our <br>
        <a href="terms.html">Terms and Conditions</a> </p>
        <p>Already have an account? <a href="login.php">Login Here</a> </p>
    </div>


    <div class="controller">
    </div>

</body>
</html>