<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "techopa";
$conn = new mysqli($servername, $username, $password, $dbname);



if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="static/css/index.css">
<title>Techopa | Home</title>
</head>

<body>
<header>
<nav>
<ul>
    <li><a href="#">Home</a></li>
    <li><a href="#">Catagory</a></li>
    <li><a href="#">Support</a></li>
    <li><a href="#">About</a></li>
</ul>
</nav>
<div id="hero">
<div id="logo-container">
    <h1 id="logo">
        Techopa
    </h1>

</div>
</div>
<a href="#main" id="explore">Explore</a>

</header>
<main id="main">

<!-- NOTE: Recent Innovations -->
<div class="recent-innovations">
<h2>Recent Innovations</h2>
<div class="products">
    <?php
    $sql = "SELECT id, name, image, short_desc, price FROM products ORDER BY id DESC LIMIT 3";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $name = $row["name"];
            $words = explode(" ", $name);
            $shortName = implode(" ", array_slice($words, 0, 2));
            echo '<div class="product"><a target="_blank" href=" product.php?id=' . $row["id"] .'">
<img src="' . $row["image"] . '" alt="' . $shortName . '">
<h3>' . $row["name"] . '</h3>
<p class="description">' . $row["short_desc"] . '</p>
<p class="price">₹' . $row["price"] . '</p>
</a></div>';
        }
    } else {
        echo "0 results";
    }
    ?>
</div>
</div>
<!-- NOTE: Featured Products -->
<div class="featured-products">
<h2>Featured Products</h2>
<div class="products featured-products-list">
    <?php
    $sql = "SELECT id, name, image, price FROM products ORDER BY id DESC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $name = $row["name"];
            $words = explode(" ", $name);
            $shortName = implode(" ", array_slice($words, 0, 2));
            echo '<div class="product "><a  href=" product.php?id=' . $row["id"] .'" target="_blank" >
<img src="' . $row["image"] . '" alt="' . $shortName . '">
<h3 class="f-name">' . $shortName . '</h3>
<p class="f-price">₹' . $row["price"] . '</p>
</a></div>';
        }
    } else {
        echo "0 results";
    }
    ?>
</div>
</div>

<aside>
<section class="about" id="about">
  <div class="about-container">
    <h2>About Techopa</h2>
    <p>
      At <span class="highlight">Techopa</span>, we’re building the future of
      wearable technology. Starting with smart glasses, our mission is to make
      everyday life more connected, seamless, and exciting. We blend innovation,
      design, and user comfort to bring technology closer to you.
    </p>
    <p>
      From advanced gadgets to powerful accessories, Techopa is not just a brand
      — it’s a movement towards smarter living.
    </p>
  </div>
</section>

</aside>

</main>
<footer class="footer">
  <div class="footer-container">
    <div class="footer-brand">
      <h2>Techopa</h2>
      
    </div>
    <div class="footer-links">
      <h4>Quick Links</h4>
      <ul>
        <li><a href="/">Home</a></li>
        <li><a href="/shop">Shop</a></li>
        <li><a href="/about">About Us</a></li>
        <li><a href="/contact">Contact</a></li>
      </ul>
    </div>
    <div class="footer-contact">
      <h4>Contact</h4>
      <p>Email: techopa6@gmail.com</p>
      <p>Phone: +91 9130199191</p>
      <p>Location: Nashik, India</p>
    </div>
    <div class="footer-social">
      <h4>Follow Us</h4>
      <a href="https://www.youtube.com/@Techopa6">🔗 YouTube</a><br>
      <a href="#">🔗 Instagram</a><br>
      <a href="#">🔗 Twitter</a>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2025 Techopa. All rights reserved.</p>
  </div>
</footer>
</body>
</html>