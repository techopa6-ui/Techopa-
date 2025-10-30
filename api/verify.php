<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
$conn = new mysqli("localhost", "root", "", "techopa");
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

// Razorpay keys
$keyId = 'rzp_live_R9H5r4XYiThUSR';
$keySecret = 'lorEXL0DfcivUUxHYPJ29VKP';

$api = new Api($keyId, $keySecret);

// Get POST data
$razorpayPaymentId = $_POST['razorpay_payment_id'] ?? '';
$razorpayOrderId   = $_POST['razorpay_order_id'] ?? '';
$razorpaySignature = $_POST['razorpay_signature'] ?? '';

$attributes = [
    'razorpay_order_id'   => $razorpayOrderId,
    'razorpay_payment_id' => $razorpayPaymentId,
    'razorpay_signature'  => $razorpaySignature
];

// Email Credentials
$fromEmail    = "techopa6@gmail.com";      
$fromName     = "Techopa";                  
$fromPassword = "your-app-password";        
$toEmail      = $_POST['email'] ;     
$toName       = "Customer";                
$subject      = "Order Confirmation - Techopa";
$body         = "<h3>Thank you for your order!</h3><p>We will process it soon. Your order id is </p> <b>" . $razorpayOrderId . "</b> <br> <p>We will contact you when your order is shipped for shipping details and traking id.</p>";

try {
    $api->utility->verifyPaymentSignature($attributes);

    // Insert into orders
    $stmt = $conn->prepare("INSERT INTO orders (name,email,address,city,state,pincode,payment_id) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssis", $_POST['name'], $_POST['email'], $_POST['address'], $_POST['city'], $_POST['state'], $_POST['pincode'], $razorpayPaymentId);
    $stmt->execute();
    $orderId = $stmt->insert_id;

    // Insert items
    foreach ($_SESSION['cart'] as $item) {
        $stmt2 = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?,?,?,?)");
        $stmt2->bind_param("iiii", $orderId, $item['id'], $item['qty'], $item['price']);
        $stmt2->execute();
    }
    //MARK:  Send confirmation email
    $mail = new PHPMailer(true);

try {
    //NOTE: SMTP Settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = $fromEmail;
    $mail->Password   = $fromPassword;
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    //NOTE: Recipients
    $mail->setFrom($fromEmail, $fromName);
    $mail->addAddress($toEmail, $toName);

    //NOTE: Content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $body;

    $mail->send();
    echo "✅ Mail sent successfully to $toEmail";
}

// NOTE: Clear cart
    unset($_SESSION['cart']);
    echo "<h2 style='color:green;text-align:center;'>✅ Payment Verified & Order Placed!</h2>";

} catch (SignatureVerificationError $e) {
    echo "<h2 style='color:red;text-align:center;'>❌ Payment verification failed: " . $e->getMessage() . "</h2>";
}
?>
