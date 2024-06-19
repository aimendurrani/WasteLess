<?php
include ('db.php');
?>

<header class="header">

   <div class="flex">

      <a href="#" class="logo">WasteLess - Reciever</a>

      <nav class="navbar">
         <!-- <a href="admin-page.php">Donate Food</a> -->
         <a href="charts.php">Back</a>
         <a href="logout.php">Logout</a>
          <!-- <a href="product.php">view products</a> -->
      </nav>

      <?php
      
      //SELECTS ALL ROWS FROM REQUEST FOOD TABLE
      $select_rows = mysqli_query($con, "SELECT * FROM `requestfood`") or die('query failed');
      $row_count = mysqli_num_rows($select_rows); //count rows

      ?>

      <a href="cart.php" class="cart">cart <span><?php echo $row_count; ?></span> </a>

      <div id="menu-btn" class="fas fa-bars"></div>

   </div>

</header>
