<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f4f7f9;
        color: #333;
        margin: 0;
        padding: 0;
    }

    h1 {
        text-align: center;
        color: #2c3e50;
        margin-top: 40px;
        font-size: 28px;
    }

    form {
        max-width: 400px;
        margin: 30px auto;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    label {
        font-size: 16px;
        color: #34495e;
        display: block;
        margin-bottom: 8px;
    }

    input[type="email"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-sizing: border-box;
        font-size: 15px;
        margin-bottom: 20px;
        transition: border 0.3s;
    }

    input[type="email"]:focus {
        border-color: #3498db;
        outline: none;
    }

    button {
        width: 100%;
        padding: 12px;
        background: #3498db;
        color: white;
        font-size: 16px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s;
    }

    button:hover {
        background: #2980b9;
    }

    p {
        max-width: 600px;
        margin: 20px auto;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        font-size: 16px;
    }

    p[style*="color:green"] {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    p[style*="color:red"] {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 10px auto;
    }
</style>


<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer library (adjust to your project path)
require 'vendor/autoload.php';

// Database connection (adjust to your server)
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

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_email = trim($_POST['email']);

    if (empty($user_email)) {
        die("<p style='color:red;'>Email is required!</p>");
    }

    // Find user
    $stmt = $pdo->prepare("SELECT image_password FROM users WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $user_email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("<p style='color:red;'>No user found with that email address!</p>");
    }

    $imageUrlsString = $user['image_password'];
    $imageUrls = explode(',', $imageUrlsString);

    // Initialize PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Change this
        $mail->SMTPAuth   = true;
        $mail->Username   = 'logen3195@gmail.com'; // Change this
        $mail->Password   = 'aawr oela hvvd qndr'; // Change this
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];

        // Recipients
        $mail->setFrom('your_email@example.com', 'Admin');
        $mail->addAddress($user_email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Selected Images';

        $htmlBody = "<h2>Your Images:</h2><div style='display:flex; flex-wrap:wrap;'>";

        $i = 1;
        foreach ($imageUrls as $url) {
            $url = trim($url);

            if (empty($url)) {
                continue;
            }

            $imageContent = @file_get_contents($url);
            if ($imageContent === false) {
                continue; // Skip if can't fetch
            }

            $finfo = finfo_open();
            $mimeType = finfo_buffer($finfo, $imageContent, FILEINFO_MIME_TYPE);
            finfo_close($finfo);

            $mimeMap = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                'image/webp' => 'webp',
                'image/bmp' => 'bmp',
                'image/svg+xml' => 'svg'
            ];

            if (!isset($mimeMap[$mimeType])) {
                continue;
            }

            $ext = $mimeMap[$mimeType];
            $tmpFile = sys_get_temp_dir() . "/img_$i.$ext";
            file_put_contents($tmpFile, $imageContent);

            $cid = "img$i";
            $mail->addEmbeddedImage($tmpFile, $cid);

            $htmlBody .= "<img src='cid:$cid' style='max-width:150px; margin:10px;' />";
            $i++;
        }

        $htmlBody .= "</div>";

        $mail->Body = $htmlBody;

        $mail->send();
        echo "<p style='color:green;'>Images successfully emailed to $user_email!</p>";

    } catch (Exception $e) {
        echo "<p style='color:red;'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</p>";
    }
}
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send User Images</title>
</head>
<body>
    <h1>Send User's Images by Email</h1>
    <form method="POST" action="">
        <label for="email">Enter User's Email:</label><br>
        <input type="email" name="email" required><br><br>
        <button type="submit">Send Images</button>
    </form>
</body>
</html>
