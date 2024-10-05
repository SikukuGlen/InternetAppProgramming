<?php

include '../config/Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inputCode = trim($_POST['2fa_code']);
    $email = $_POST['email'];

    // Database connection
    $database = new Database();
    $conn = $database->connect();

    // Fetch stored 2FA code and expiry from the database
    $stmt = $conn->prepare("SELECT 2fa_code, 2fa_expires FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $storedCode = $user['2fa_code'];
        $expiry = $user['2fa_expires'];

        if ($inputCode === $storedCode && strtotime($expiry) > time()) {
            echo "2FA verified successfully!";
        } else {
            echo "Invalid or expired 2FA code!";
        }
    } else {
        echo "User not found!";
    }
}