<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: #f4f6fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #007bff;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn:hover {
            background: #0056b3;
        }

        .message {
            margin-top: 15px;
            font-size: 14px;
            text-align: center;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }
    </style>

    <script>
        function validatePasswords() {
            const newPass = document.getElementById("new_password").value;
            const confirmPass = document.getElementById("confirm_password").value;
            if (newPass !== confirmPass) {
                alert("Passwords do not match.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Update Password</h2>

        <?php
        // DB config
        $host = 'localhost';
        $dbname = 'fyp';
        $username = 'root';
        $password = '';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("<p class='error'>DB connection failed: " . $e->getMessage() . "</p>");
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $user = $_POST['username'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($user) || empty($newPassword) || empty($confirmPassword)) {
                echo "<p class='message error'>All fields are required.</p>";
            } elseif ($newPassword !== $confirmPassword) {
                echo "<p class='message error'>Passwords do not match.</p>";
            } else {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET passphrase_hash = :hash WHERE username = :username");

                session_start();

                try {
                    $stmt->execute([
                        ':hash' => $hashedPassword,
                        ':username' => $user
                    ]);
                
                    if ($stmt->rowCount() > 0) {
                        $_SESSION['feedback'] = [
                            'type' => 'success',
                            'text' => "Password updated successfully for <strong>" . htmlspecialchars($user) . "</strong>."
                        ];
                    } else {
                        $_SESSION['feedback'] = [
                            'type' => 'error',
                            'text' => "Username not found. No update performed."
                        ];
                    }
                
                    // Redirect to feedback page
                    header('Location: feedback.php');
                    exit;
                
                } catch (PDOException $e) {
                    $_SESSION['feedback'] = [
                        'type' => 'error',
                        'text' => "Update failed: " . $e->getMessage()
                    ];
                    header('Location: feedback.php');
                    exit;
                }
                
            }
        }
        ?>

        <form method="POST" onsubmit="return validatePasswords();">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" id="new_password" name="new_password" placeholder="New Password" required>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit" class="btn">Update Password</button>
        </form>
    </div>
</body>
</html>
