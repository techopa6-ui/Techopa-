<?php
session_start();

// Razorpay API credentials (use test keys while testing!)
$keyId = 'rzp_live_R9H5r4XYiThUSR';
$keySecret = 'lorEXL0DfcivUUxHYPJ29VKP';

// Calculate cart total
$total = 0;
foreach ($_SESSION['cart'] as $id => $item) {
    $subtotal = $item['price'] * $item['qty'];
    $total += $subtotal;
}

$amount = $total * 100; // in paise
$currency = 'INR';
$receipt = 'order_rcptid_' . time();

// Create order via Razorpay API (using cURL)
$apiUrl = 'https://api.razorpay.com/v1/orders';
$orderData = [
    'amount' => $amount,
    'currency' => $currency,
    'receipt' => $receipt,
    'payment_capture' => 1
];

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_USERPWD, $keyId . ':' . $keySecret);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$order = json_decode($response, true);
$orderId = $order['id'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout - Techopa</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        body { font-family: Arial, sans-serif; background:#f5f5f5; display:flex; justify-content:center; align-items:center; height:100vh; margin:0; }
        .checkout-container { background:#fff; padding:30px 40px; border-radius:12px; box-shadow:0 8px 25px rgba(0,0,0,0.1); width:350px; text-align:center; }
        h2 { margin-bottom:20px; color:#333; }
        input[type="text"], input[type="email"], input[type="number"] { width:100%; padding:10px 12px; margin:8px 0 16px 0; border-radius:6px; border:1px solid #ccc; box-sizing:border-box; }
        #rzp-button { background-color:#3399cc; color:#fff; border:none; padding:12px 20px; border-radius:8px; cursor:pointer; font-size:16px; width:100%; }
        #rzp-button:hover { background-color:#287aa6; }
    </style>
</head>
<body>
    <div class="checkout-container">
        <h2>Checkout</h2>
        <form id="checkout-form" method="POST" action="verify.php" >
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="text" name="address" placeholder="Address" required>
            <input type="text" name="city" placeholder="City" required>
            <input type="text" name="state" placeholder="State" required>
            <input type="number" name="pincode" placeholder="Pincode" required>
            <button type="button" id="rzp-button">
                <?= "Pay ₹" . ($amount/100) . ".00" ?>
            </button>
        </form>
    </div>

    <script>
        var options = {
            "key": "<?= $keyId ?>",
            "amount": "<?= $amount ?>",
            "currency": "<?= $currency ?>",
            "name": "Techopa",
            "description": "Order Payment",
            "order_id": "<?= $orderId ?>",
            "handler": function (response){
                // Auto-submit to verify.php
                var form = document.createElement('form');
                form.method = "POST";
                form.action = "verify.php";

                var fields = {
                    "razorpay_payment_id": response.razorpay_payment_id,
                    "razorpay_order_id": response.razorpay_order_id,
                    "razorpay_signature": response.razorpay_signature,
                    "name": document.querySelector('[name="name"]').value,
                    "email": document.querySelector('[name="email"]').value,
                    "address": document.querySelector('[name="address"]').value,
                    "city": document.querySelector('[name="city"]').value,
                    "state": document.querySelector('[name="state"]').value,
                    "pincode": document.querySelector('[name="pincode"]').value
                };

                for (var key in fields) {
                    if (fields.hasOwnProperty(key)) {
                        var hidden = document.createElement("input");
                        hidden.type = "hidden";
                        hidden.name = key;
                        hidden.value = fields[key];
                        form.appendChild(hidden);
                    }
                }
                document.body.appendChild(form);
                form.submit();
            },
            "theme": { "color": "#3399cc" }
        };
        var rzp = new Razorpay(options);
        document.getElementById('rzp-button').onclick = function(e){
            rzp.open();
            e.preventDefault();
        }
    </script>
</body>
</html>
