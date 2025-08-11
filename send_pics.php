<?php
// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Adjust path if needed

// Database connection
$conn = new mysqli("localhost", "root", "", "fyp");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Step 1: Fetch images
    $stmt = $conn->prepare("SELECT image_password_hash FROM users WHERE email = ?");
    if (!$stmt) {
        die("Database prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    $images = [];
    while ($row = $result->fetch_assoc()) {
        $images[] = $row['image_password_hash'];
    }

    if (empty($images)) {
        die("No images found for this email.");
    }

    // Step 2: Setup PHPMailer
    $mail = new PHPMailer(true);
    $tempFiles = [];

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'logen3195@gmail.com'; // Your Gmail
        $mail->Password = 'aawr oela hvvd qndr';  // App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('logen3195@gmail.com', 'FYP System'); // Adjust as needed
        $mail->addAddress($email);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Your Requested Images from FYP';
        $mail->Body    = 'Hi,<br><br>Please find your images attached below.<br><br>Regards,<br>FYP Team.';

        // Step 3: Create custom temp folder
        $tempDir = __DIR__ . '/temp_uploads/';
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        // Step 4: Attach images properly
        foreach ($images as $imgData) {
            // CASE 1: Base64 + URL mixed
            if (strpos($imgData, 'data:image/') !== false && strpos($imgData, 'http://') !== false) {
                list($urlPart, $base64Part) = explode(',', $imgData, 2);

                // Handle URL
                $urlWithoutHost = str_replace('http://localhost/', $_SERVER['DOCUMENT_ROOT'] . '/', $urlPart);
                if (file_exists($urlWithoutHost)) {
                    $mail->addAttachment($urlWithoutHost);
                } else {
                    echo "File not found: $urlWithoutHost<br>";
                }

                // Handle base64
                if (preg_match('/data:image\/(\w+);base64,(.+)/', 'data:image/' . $base64Part, $matches)) {
                    $ext = $matches[1];
                    $data = base64_decode($matches[2], true);

                    if ($data !== false) {
                        $tempFile = tempnam($tempDir, 'img_') . '.' . $ext;
                        if (file_put_contents($tempFile, $data) !== false) {
                            $mail->addAttachment($tempFile);
                            $tempFiles[] = $tempFile;
                        }
                    }
                }
            }
            // CASE 2: Only base64
            elseif (strpos($imgData, 'data:image/') === 0) {
                if (preg_match('/data:image\/(\w+);base64,(.+)/', $imgData, $matches)) {
                    $ext = $matches[1];
                    $data = base64_decode($matches[2], true);

                    if ($data !== false) {
                        $tempFile = tempnam($tempDir, 'img_') . '.' . $ext;
                        if (file_put_contents($tempFile, $data) !== false) {
                            $mail->addAttachment($tempFile);
                            $tempFiles[] = $tempFile;
                        }
                    }
                }
            }
            // CASE 3: Only URL
            elseif (strpos($imgData, 'http://') === 0 || strpos($imgData, 'https://') === 0) {
                $filePath = str_replace('http://localhost/', $_SERVER['DOCUMENT_ROOT'] . '/', $imgData);
                if (file_exists($filePath)) {
                    $mail->addAttachment($filePath);
                } else {
                    echo "File not found: $filePath<br>";
                }
            }
            else {
                echo "Unknown image format skipped.<br>";
            }
        }

        // Step 5: Send email
        $mail->send();
        echo '<h3 style="color:green;">Email sent successfully!</h3>';

    } catch (Exception $e) {
        echo '<h3 style="color:red;">Message could not be sent. Mailer Error: ' . $mail->ErrorInfo . '</h3>';
    } finally {
        // Clean up temp files
        foreach ($tempFiles as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }
}
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html>
<head>
    <title>Send My Images</title>
</head>
<body>
    <h2>Request Your Images</h2>
    <form method="POST" style="margin-top:20px;">
        <label>Enter your email address:</label><br>
        <input type="email" name="email" required style="padding:5px; margin:10px 0;"><br>
        <button type="submit" style="padding:8px 15px;">Send Images</button>
    </form>
</body>
</html>
