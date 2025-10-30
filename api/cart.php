<?php
session_start();
$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link rel="stylesheet" href="static/css/cart.css">
</head>
<body>

<h2>Your Shopping Cart</h2>

<table>
    <tr>
        <th>Image</th><th>Name</th><th>Price</th><th>Qty</th><th>Subtotal</th><th>Action</th>
    </tr>
    <?php foreach ($_SESSION['cart'] as $id => $item): 
        $subtotal = $item['price'] * $item['qty'];
        $total += $subtotal;
    ?>
    <tr>
        <td><img src="<?= $item['image'] ?>" width="60"></td>
        <td><?= $item['name'] ?></td>
        <td>₹<?= $item['price'] ?></td>
        <td><?= $item['qty'] ?></td>
        <td>₹<?= $subtotal ?></td>
        <td><a href="addtocart.php?action=remove&id=<?= $id ?>" class="remove-btn">Remove</a></td>
    </tr>
    <?php endforeach; ?>
</table>

<div class="total"><strong>Total: ₹<?= $total ?></strong></div>
    <a href="checkout.php" class="checkout-btn">Checkout</a>
<script>


</script>
</body>
</html>
