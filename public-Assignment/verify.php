<?php

include '../config-Assignment/Database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if  email and verification_code fields are set
    if (isset($_POST['email']) && isset($_POST['verification_code'])) {

        // Trim and normalize the inputs
        $submittedEmail = trim(strtolower($_POST['email']));
        $verificationCode = trim($_POST['verification_code']);
        
        // Output the submitted values for debugging
        echo "Submitted email: " . $submittedEmail . "<br>";
        echo "Submitted verification code: " . $verificationCode . "<br>";

        // Check if the email is empty
        if (empty($submittedEmail)) {
            echo "Email is empty!";
            exit;
        }
        
        // Database connection
        $database = new Database();
        $conn = $database->connect();
        
       
        $stmt = $conn->prepare("SELECT * FROM users WHERE LOWER(email) = :email AND verification_code = :code");
        $stmt->bindParam(':email', $submittedEmail);
        $stmt->bindParam(':code', $verificationCode);
        
        if (!$stmt->execute()) {
            print_r($stmt->errorInfo());  // Output SQL errors
        } else {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                echo "User found!";
            } else {
                echo "User not found!";
            }
        }
    } else {
        echo "Email or verification code is missing.";
    }
}