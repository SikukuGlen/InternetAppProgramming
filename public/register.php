<?php


include '../config/Database.php';

function generate2FACode() {
    return rand(100000, 999999); // 6-digit random code
}

function send2FACode($email, $code) {
    require '../includes/PHPMailer.php'; // Include PHPMailer

    // PHPMailer setup
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

        // Insert user into database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        
        if ($stmt->execute()) {
            // Generate and send 2FA code
            $code = generate2FACode();
            send2FACode($email, $code);

            // Store the 2FA code in the database (with an expiration)
            $stmt = $conn->prepare("UPDATE users SET 2fa_code = :code, 2fa_expires = :expires WHERE email = :email");
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':expires', date('Y-m-d H:i:s', strtotime('+10 minutes')));
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Redirect to verify page
            header("Location: verify.php?email=" . urlencode($email));
        } else {
            echo "Error in registration.";
        }
    }
}
