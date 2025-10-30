<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "techopa";
$conn = new mysqli($servername, $username, $password, $dbname);
session_start();
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page </title>
    <link rel="stylesheet" href="static/css/product.css">
    </head>
<body>
  <?php

    $sql = "SELECT id, name, image, short_desc, price FROM products WHERE id = " . (isset($_GET['id']) ? intval($_GET['id']) : 0 ) ;
    $result = $conn->query($sql);
 $quantity = 0;

    if ($result->num_rows > 0) {
      
        while ($row = $result->fetch_assoc()) {
              if (isset($_SESSION['cart'][$row["id"]])) {
                $item = $_SESSION['cart'][$row["id"]];  
                $quantity = $item['qty'];
    }
          echo '    <div class="container">
    <div class="image-box"></div>
    <div class="details">
      <h2>'. $row["name"] .'</h2>
      <p>' .$row["short_desc"]. '</p>
      <div class="price">₹' . $row["price"] . '</div>
      <div class="quantity">
        <a href="addtocart.php?action=remove&id=' . $row["id"] .'">-</a>
        <span id="qty">'.$quantity . '</span>
        <a href="addtocart.php?id=' . $row["id"] .'">+</a>
      </div>
      <div class="buttons">
        <a class="btn add-cart" href="addtocart.php?action=redirect&id=' . $row["id"] .'" >Add to cart</a>
        <form method="POST" action="checkout.php">
        <input type="text" name="amount" value="' . $row["price"] * $quantity * 100 . '" hidden> 
        <button type="submit"  class="btn buy-now" >Buy Now</button>
        </form>
        <a class="btn buy-now" href="cart.php" >Show cart</a>
      </div>
    </div>
  </div>';
        }
      } else {
        echo " ";
      }
  ?>

</body>
</html>
