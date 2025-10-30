
<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "techopa";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT id, name, image, short_desc, price FROM products WHERE id = " . (isset($_GET['id']) ? intval($_GET['id']) : 0 ) ;

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $id = $row['id'];
    $name = $row['name'];
    $image = $row['image'];
    $price = $row['price'];
} else {
    die("Product not found.");
}
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if(isset($_GET['action']) && $_GET['action'] == 'remove') {
    $id = intval($_GET['id']);
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['qty'] -= 1;
        if ($_SESSION['cart'][$id]['qty'] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
        
    }
}else if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['qty'] += 1;
} else {
    $_SESSION['cart'][$id] = [
        'id'  => $id,
        'name' => $name,
        'image' => $image,
        'price' => $price,
        'qty'   => 1
    ];
}


if (isset($_GET['action']) && $_GET['action'] == 'redirect') {
    echo "hi";
    header("Location: cart.php");
}else {
    header("Location: product.php?id=" . $id);
}
exit;
?>
