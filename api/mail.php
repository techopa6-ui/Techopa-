<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';   // path to vendor folder from Composer

// ========== CONFIGURE HERE ==========
$fromEmail    = "techopa6@gmail.com";      // Your Gmail
$fromName     = "Techopa";                  // Sender Name
$fromPassword = "your-app-password";        // Gmail App Password (not normal password)
$toEmail      = "customer@example.com";     // Receiver Email
$toName       = "Customer";                 // Receiver Name
$subject      = "Order Confirmation - Techopa";
$body         = "<h3>Thank you for your order!</h3><p>We will process it soon.</p>";
// ====================================

$mail = new PHPMailer(true);

try {
    // SMTP Settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = $fromEmail;
    $mail->Password   = $fromPassword;
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom($fromEmail, $fromName);
    $mail->addAddress($toEmail, $toName);

    // Content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $body;

    $mail->send();
    echo "✅ Mail sent successfully to $toEmail";
} catch (Exception $e) {
    echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
