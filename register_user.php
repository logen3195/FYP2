<style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            padding: 20px;
        }

        .container h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
        }

        .container p {
            font-size: 16px;
            margin-bottom: 15px;
        }


        .container .success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .container .error {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        .container .debug {
            font-size: 14px;
            color: #6c757d;
            margin-top: 10px;
        }

        .container a {
            text-decoration: none;
            color: #007bff;
        }

        .container a:hover {
            text-decoration: underline;
        }

        /* Email Feedback */
        .email-feedback {
            font-size: 14px;
            margin-top: 5px;
            color: red;
        }

        .container .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .container .button:hover {
            background-color: #0056b3;
        }
    </style>


<div class="container">
<?php
// Database connection settings
$host = 'localhost';
$dbname = 'fyp';
$username = 'root';
$password = '';

// Create a database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p class='success'>Connected to the database.</p>";
} catch (PDOException $e) {
    die("<p class='error'>Database connection failed: " . $e->getMessage() . "</p>");
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username'], $_POST['email'], $_POST['passphrase'])) {

        $user = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $passphrase = htmlspecialchars($_POST['passphrase']);
        $confirmPassphrase = htmlspecialchars($_POST['confirm_passphrase']);

        if ($passphrase !== $confirmPassphrase) {
            die("<p class='error'>Passphrases do not match! Registration failed.</p>");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("<p class='error'>Invalid email format.</p>");
        }

        // Image Upload Logic
        $uploadDir = 'images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $savedImages = [];

        // 1. Process selected images from carousel
        if (!empty($_POST['image_password'])) {
            $selected = explode(',', $_POST['image_password']);
            foreach ($selected as $imgUrl) {
                // OPTIONAL: validate it starts with your domain path
                if (strpos($imgUrl, 'images/') !== false) {
                    $savedImages[] = $imgUrl;
                }
            }
        }

        // 2. Process uploaded images (from file input)
        if (!empty($_FILES['uploaded_images']['name'][0])) {
            $uploadDir = 'images/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            foreach ($_FILES['uploaded_images']['tmp_name'] as $key => $tmpName) {
                $originalName = $_FILES['uploaded_images']['name'][$key];
                $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp', 'avif'];
        
                if (in_array($ext, $allowed)) {
                    $newName = uniqid('user_', true) . ".$ext";
                    $destination = $uploadDir . $newName;
                    if (move_uploaded_file($tmpName, $destination)) {
                        $savedImages[] = $destination;
                    }else{
                    echo "<p class='error'>Failed to upload image: $originalName</p>";
                }
                
            } else {
                echo "<p class='error'>Invalid file type: $originalName</p>";
            }
        }
        }

        if (count($savedImages) < 3) {
            die("<p class='error'>At least 3 images must be uploaded.</p>");
        }

        // Check if email already exists
        $checkEmailSql = "SELECT COUNT(*) FROM users WHERE email = :email";
        $checkStmt = $pdo->prepare($checkEmailSql);
        $checkStmt->execute([':email' => $email]);
        $emailExists = $checkStmt->fetchColumn();

        if ($emailExists) {
            die("<p class='error'>This email is already registered. Please use a different email.</p>");
        }

        // Check if username already exists
        $checkUsernameSql = "SELECT COUNT(*) FROM users WHERE username = :username";
        $checkUsernameStmt = $pdo->prepare($checkUsernameSql);
        $checkUsernameStmt->execute([':username' => $user]);
        $usernameExists = $checkUsernameStmt->fetchColumn();

        if ($usernameExists) {
            die("<p class='error'>This username is already taken. Please choose another.</p>");
        }

        // Hash the passphrase securely
        $passphraseHash = password_hash($passphrase, PASSWORD_DEFAULT);

        // Store user data
        $sql = "INSERT INTO users (username, email, passphrase_hash, image_password) VALUES (:username, :email, :passphrase_hash, :image_password)";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([
                ':username' => $user,
                ':email' => $email,
                ':passphrase_hash' => $passphraseHash,
                ':image_password' => implode(',', $savedImages), // Save paths separated by commas
            ]);
            echo "<p class='success'>User registered successfully.</p>";
        } catch (PDOException $e) {
            echo "<p class='error'>Error storing data: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p class='error'>Please fill in all fields and upload at least 3 images.</p>";
    } 
}



?>
<div style="margin-top: 20px; text-align: center;">
    <a href="registration.php" class="button">Register</a>
    <a href="login.php" class="button">Login</a>
</div>
