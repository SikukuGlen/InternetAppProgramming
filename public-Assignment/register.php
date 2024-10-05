
<?php
// File: public/register.php

include '../config/Database.php';

function generate2FACode() {
    return rand(100000, 999999); // 6-digit random code
}

function send2FACode($email, $code) {
    require '../includes/PHPMailer.php'; // Include PHPMailer

    // PHPMailer setup (example configuration)
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'johndoe@gmail.com';
        $mail->Password = 'password123@';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;


          // Email content
          $mail->setFrom('ics@gmail.com', 'ICS 2024');
          $mail->addAddress($email);
          $mail->isHTML(true);
          $mail->Subject = 'Your 2FA Code';
          $mail->Body = 'Your 2FA verification code is: ' . $code;

        $mail->send();
        echo '2FA code sent to your email';
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        echo "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Database connection
        $database = new Database();
        $conn = $database->connect();

        // Generate 2FA code
        $code = generate2FACode();
        $expiry_time = date('Y-m-d H:i:s', strtotime('+10 minutes')); // Code expires in 10 minutes

        // Insert user into database
        $stmt = $conn->prepare("
            INSERT INTO users (username, email, password, verification_code, verification_expiry, created_at, updated_at) 
            VALUES (:username, :email, :password, :verification_code, :verification_expiry, NOW(), NOW())
        ");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':verification_code', $code);
        $stmt->bindParam(':verification_expiry', $expiry_time);

        if ($stmt->execute()) {
            // Send the 2FA code via email
            send2FACode($email, $code);

            // Redirect to verify page
            header("Location: verify.php?email=" . urlencode($email));
        } else {
            echo "Error in registration.";
        }
    }
}