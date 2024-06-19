<?php
session_start();
include('db.php');
include('navbar.php');

//checks if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $mail = $_POST['email'];
    $pw = $_POST['pass'];

    if (!empty($mail) && !empty($pw) && !is_numeric($mail)) {
        //SELECTS USER WITH THE GIVEN EMAIL
        $query = "SELECT * FROM users WHERE email = '$mail' LIMIT 1";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);

            if ($user_data['pass'] == $pw) { //password check
                $_SESSION['cnic'] = $user_data['cnic']; // stores cnic in session
                header("Location: charts.php");
                die;
            }
        }//alert
        echo "<script type='text/javascript'> alert('Wrong email or password') </script>";
    } else {
        echo "<script type='text/javascript'> alert('Please enter valid email and password') </script>";
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
    <div class="login">
        <h1>Login</h1>
        <form method="POST">
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Password</label>
            <input type="password" name="pass" required>
            <input type="submit" value="Login">
        </form>
        <p>Don't have an account? <a href="signup.php">SignUp Here</a></p>
    </div>
    <div class="controller">
    </div>
</body>
</html>
