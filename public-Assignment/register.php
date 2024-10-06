
<?php
include '../config-Assignment/Database.php';
require '../plugins/PHPMailer/src/PHPMailer.php';
require '../plugins/PHPMailer/src/SMTP.php';
require '../plugins/PHPMailer/src/Exception.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// Function to generate a random 6-digit 2FA code
function generate2FACode() {
    return rand(100000, 999999); 
}


function send2FACode($email, $code) {
  
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'johndoe@gmail.com'; //Gmail address
        $mail->Password = 'password123@'; // Gmail password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Email content
        $mail->setFrom('ics@gmail.com', 'ICS 2024');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Your 2FA Code';
        $mail->Body = 'Your 2FA verification code is: ' . $code;

        $mail->send();
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = array(); // Array to store errors

    // Input fields
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

   
    
    // Check if fields are empty
    if (empty($username) || empty($email) || empty($password)) {
        $errors['fields_err'] = "All fields are required!";
    }

    // Validate the username (letters only, no spaces)
    if (!ctype_alpha($username)) {
        $errors['username_err'] = "Invalid username format. Username must contain letters only without space";
    }

    // Validate the email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email_format_err'] = "Invalid email format!";
    }

    //Verify that the email doesn't already exist in the database
    $database = new Database();
    $conn = $database->connect();

    $stmt = $conn->prepare("SELECT email FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $errors['email_exists_err'] = "Email already exists!";
    }

    //Verify that the username doesn't already exist in the database
    $stmt = $conn->prepare("SELECT username FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $errors['username_exists_err'] = "Username already exists!";
    }

    // If there are no errors, proceed with registration
    if (empty($errors)) {
        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Generate 2FA code
        $code = generate2FACode();

        // Insert user into the database with a 10-minute verification expiry
        $stmt = $conn->prepare("
            INSERT INTO users (username, email, password, verification_code, verification_expiry, created_at, updated_at) 
            VALUES (:username, :email, :password, :verification_code, DATE_ADD(NOW(), INTERVAL 10 MINUTE), NOW(), NOW())
        ");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':verification_code', $code);

        if ($stmt->execute()) {
            // Send the 2FA code via email
            send2FACode($email, $code);

            // Redirect to the verify page
            header("Location: verify.php?email=" . urlencode($email));
            exit(); // Always exit after redirecting
        } else {
            echo "Error in registration.";
        }
    } else {
        // Display the errors to the user
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
}
?>