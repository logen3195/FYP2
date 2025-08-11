<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php'; // Load PHPMailer (adjust path if needed)

// Database connection
$host = 'localhost';
$dbname = 'fyp';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);


    // Check if email exists in database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    

    // Generate reset token & expiry time
    $reset_token = bin2hex(random_bytes(32));
    $expiry_time = date('Y-m-d H:i:s', strtotime('+1 hour'));

    

    // Prepare reset link
    $reset_link = "http://localhost/FYP2/update_password.php";

    // Send email using PHPMailer
    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Use Gmail SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'logen3195@gmail.com'; // Your email
        $mail->Password = 'aawr oela hvvd qndr'; // Use an app password (not your actual password)
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
        );



        
        
        // Email details
        $mail->setFrom('your_email@gmail.com', 'Support Team');
        $mail->addAddress($email);
        $mail->Subject = 'Password Reset Request';
        $mail->Body = "Click the link below to reset your passphrase:\n\n" . $reset_link . "\n\nThis link will expire in 1 hour.";

        $mail->send();
        echo '<div class="container"><div class="message success">Password reset email sent. Please check your inbox.</div></div>';
    } catch (Exception $e) {
        echo '<div class="container"><div class="message error">Failed to send email: ' . $mail->ErrorInfo . '</div></div>';

    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Passphrase</title>
    <style>
         * {
                box-sizing: border-box;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                margin: 0;
                padding: 0;
            }

            body {
                background: linear-gradient(to right, #6a11cb, #2575fc);
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 20px;
            }

            .container {
                background: #fff;
                padding: 30px 25px;
                max-width: 450px;
                width: 100%;
                border-radius: 10px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
                text-align: center;
                transition: transform 0.3s ease;
            }

            .container:hover {
                transform: scale(1.02);
            }

            .container h2 {
                margin-bottom: 20px;
                color: #333;
                font-size: 28px;
            }

            label {
                font-size: 16px;
                color: #333;
                display: block;
                margin-bottom: 8px;
                text-align: left;
            }

            input[type="email"] {
                width: 100%;
                padding: 12px;
                border-radius: 6px;
                border: 1px solid #ccc;
                margin-bottom: 20px;
                font-size: 15px;
                transition: border-color 0.3s;
            }

            input[type="email"]:focus {
                outline: none;
                border-color: #007bff;
            }

            button {
                background-color: #007bff;
                color: #fff;
                padding: 12px 20px;
                font-size: 16px;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                width: 100%;
                transition: background-color 0.3s;
            }

            button:hover {
                background-color: #0056b3;
            }

   .message {
                padding: 15px;
                margin-bottom: 20px;
                border-radius: 5px;
                font-size: 16px;
                text-align: center;
            }

            .success {
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }

            .error {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }


    </style>
</head>
<body>
    <div class="container">
        <h2>Forgot Passphrase</h2>
        <form method="POST">
            <label for="email">Enter your email:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">Reset Passphrase</button>
        </form>
    </div>
</body>
</html>
